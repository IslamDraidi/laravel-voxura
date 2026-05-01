<?php

namespace App\Jobs;

use App\Models\VirtualTryon;
use App\Services\AI\TryOnPipeline;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessVirtualTryOnJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 300;
    public int $backoff = 60;

    public function __construct(public VirtualTryon $tryon) {}

    public function handle(TryOnPipeline $pipeline): void
    {
        try {
            $this->tryon->refresh();
        } catch (ModelNotFoundException) {
            Log::info('VirtualTryon deleted before job ran — discarding.');
            return;
        }

        if ($this->tryon->isReady()) {
            return;
        }

        $pipeline->run($this->tryon);
    }

    public function failed(?Throwable $exception): void
    {
        if (! $exception) {
            return;
        }

        try {
            $this->tryon->refresh()->update([
                'status'        => 'failed',
                'error_message' => $exception->getMessage(),
            ]);
        } catch (ModelNotFoundException) {
            return;
        }

        Log::error('Try-on job permanently failed', [
            'tryon_id' => $this->tryon->id,
            'error'    => $exception->getMessage(),
        ]);
    }
}
