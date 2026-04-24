<?php

namespace App\Services\AI;

use App\Exceptions\Model3DGenerationException;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class Model3DPipeline
{
    public function __construct(
        private QwenVLService $qwen,
        private TrellisService $trellis,
    ) {
    }

    public function run(Product $product): void
    {
        $product->refresh()->load('images');

        if ($product->images->isEmpty()) {
            throw new Model3DGenerationException('No images available on product.');
        }

        $paths = $product->images
            ->map(fn ($img) => public_path("images/{$img->image}"))
            ->filter(fn ($p) => file_exists($p))
            ->values()
            ->all();

        if (empty($paths)) {
            throw new Model3DGenerationException('Product image files missing on disk.');
        }

        try {
            $selection = $this->qwen->selectBestImage($paths, $product->name);

            $product->update([
                'model3d_selected_image' => $selection['selected_image'],
                'model3d_status'         => 'processing',
            ]);

            $filename = $this->trellis->generateModel(
                $selection['selected_image'],
                $selection['description'],
                $product->id,
            );

            $product->update([
                'model3d_path'         => $filename,
                'has_3d_model'         => true,
                'model3d_status'       => 'ready',
                'model3d_generated_at' => now(),
                'model3d_error'        => null,
            ]);

            Log::info("3D model generated for product {$product->id}");
        } catch (\Throwable $e) {
            $product->update([
                'model3d_status' => 'failed',
                'model3d_error'  => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
