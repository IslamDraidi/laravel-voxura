<?php

namespace App\Console\Commands;

use App\Models\VirtualTryon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CleanExpiredTryOns extends Command
{
    protected $signature = 'tryon:cleanup';

    protected $description = 'Delete expired try-on records, photos, and result files';

    public function handle(): int
    {
        $disk = Storage::disk('public');
        $count = 0;

        $expired = VirtualTryon::query()
            ->where(function ($q) {
                $q->where('photo_consent', false)
                  ->whereNotNull('expires_at')
                  ->where('expires_at', '<', now());
            })
            ->orWhere(function ($q) {
                $q->where('status', 'failed')
                  ->where('created_at', '<', now()->subDays(7));
            })
            ->with('user')
            ->get();

        foreach ($expired as $tryon) {
            if ($tryon->photo_path && ! $tryon->photo_consent) {
                $disk->delete($tryon->photo_path);
            }

            $userBodyPath = $tryon->user?->body_model_path;
            if ($tryon->body_model_path && $tryon->body_model_path !== $userBodyPath) {
                $disk->delete($tryon->body_model_path);
            }

            if ($tryon->result_model_path) {
                $disk->delete($tryon->result_model_path);
            }

            $tryon->delete();
            $count++;
        }

        $this->info("Deleted {$count} expired try-on record(s).");
        Log::info("tryon:cleanup deleted {$count} records");

        return self::SUCCESS;
    }
}
