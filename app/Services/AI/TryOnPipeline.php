<?php

namespace App\Services\AI;

use App\Exceptions\Model3DGenerationException;
use App\Models\VirtualTryon;
use App\Notifications\TryOnReadyNotification;
use App\Services\AI\Contracts\TryOnBodyProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class TryOnPipeline
{
    public function __construct(
        private TryOnBodyProvider $bodyProvider,
        private GarmentFittingService $fittingService,
    ) {}

    public function run(VirtualTryon $tryon): void
    {
        $tryon->loadMissing(['user', 'product.category']);

        $user = $tryon->user;
        $product = $tryon->product;

        if (! $user || ! $product) {
            throw new Model3DGenerationException('Try-on record is missing user or product.');
        }

        try {
            // ── Step 1–2: body generation (or reuse) ──────────────────
            $tryon->update(['status' => 'processing_body']);

            if ($user->hasReusableBodyModel()) {
                $bodyRelativePath = $user->body_model_path;
                $tryon->update([
                    'body_model_path'   => $bodyRelativePath,
                    'body_generated_at' => now(),
                ]);
            } else {
                if (! $tryon->photo_path) {
                    throw new Model3DGenerationException('No photo provided for body generation.');
                }

                $photoAbsPath = Storage::disk('public')->path($tryon->photo_path);

                $bodyRelativePath = $this->bodyProvider->generateBodyModel(
                    $photoAbsPath,
                    $tryon->height_cm,
                    $user->id
                );

                $tryon->update([
                    'body_model_path'   => $bodyRelativePath,
                    'body_generated_at' => now(),
                ]);

                if ($tryon->photo_consent) {
                    $user->update([
                        'has_body_model'          => true,
                        'body_model_path'         => $bodyRelativePath,
                        'body_model_generated_at' => now(),
                        'body_height_cm'          => $tryon->height_cm,
                    ]);
                }
            }

            // ── Step 3–6: garment fitting ─────────────────────────────
            $tryon->update(['status' => 'processing_fit']);

            if (! $product->is3DReady()) {
                throw new Model3DGenerationException('Product does not have a 3D model yet. Please try again later.');
            }

            // Product 3D model path: storage/app/public/models/{id}/{model3d_path}
            $productAbsPath = storage_path("app/public/models/{$product->id}/{$product->model3d_path}");

            $resultDir = trim((string) config('model3d.tryon.result_storage', 'tryon_results'), '/');
            $resultRelativePath = "{$resultDir}/{$user->id}/tryon_{$tryon->id}.glb";
            $resultAbsPath = Storage::disk('public')->path($resultRelativePath);

            $bodyAbsPath = Storage::disk('public')->path($bodyRelativePath);

            $category = $this->fittingService->mapCategoryToFitClass($product->category);

            $this->fittingService->fitGarment(
                $bodyAbsPath,
                $productAbsPath,
                $resultAbsPath,
                $category
            );

            // ── Step 7–8: persist result + notify ────────────────────
            $tryon->update([
                'result_model_path'   => $resultRelativePath,
                'status'              => 'ready',
                'result_generated_at' => now(),
                'expires_at'          => $tryon->photo_consent ? null : now()->addHours(
                    (int) config('model3d.tryon.temp_expiry_hours', 24)
                ),
            ]);

            try {
                $user->notify(new TryOnReadyNotification($tryon));
            } catch (Throwable $e) {
                Log::warning('TryOnReadyNotification failed to send', [
                    'tryon_id' => $tryon->id,
                    'error'    => $e->getMessage(),
                ]);
            }
        } catch (Throwable $e) {
            $tryon->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
