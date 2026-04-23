<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\AI\Model3DPipeline;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class Generate3DModelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 180;
    public int $backoff = 30;

    public function __construct(public Product $product)
    {
    }

    public function handle(Model3DPipeline $pipeline): void
    {
        try {
            $this->product->refresh();
        } catch (ModelNotFoundException) {
            Log::info('Product deleted before 3D job ran — discarding.');

            return;
        }

        if ($this->product->model3d_status === 'ready') {
            return;
        }

        $this->product->update(['model3d_status' => 'processing']);

        $pipeline->run($this->product);
    }

    public function failed(?Throwable $exception): void
    {
        if (! $exception) {
            return;
        }

        try {
            $this->product->refresh()->update([
                'model3d_status' => 'failed',
                'model3d_error'  => $exception->getMessage(),
            ]);
        } catch (ModelNotFoundException) {
            // Product gone — nothing to update.
            return;
        }

        Log::error('3D generation job permanently failed', [
            'product_id' => $this->product->id,
            'error'      => $exception->getMessage(),
        ]);
    }
}