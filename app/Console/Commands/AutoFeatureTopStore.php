<?php

namespace App\Console\Commands;

use App\Models\Store;
use Illuminate\Console\Command;

class AutoFeatureTopStore extends Command
{
    protected $signature   = 'stores:auto-feature';
    protected $description = 'Auto-set featured store by visit count';

    public function handle(): void
    {
        $topStore = Store::where('status', 'approved')
            ->orderByDesc('visit_count')
            ->first();

        if (!$topStore) {
            $this->info('No approved stores found.');
            return;
        }

        Store::where('is_featured', true)->update([
            'is_featured'    => false,
            'featured_label' => null,
        ]);

        $topStore->update([
            'is_featured'    => true,
            'featured_label' => 'Store of the Week',
        ]);

        $this->info("Featured store set to: {$topStore->name}");
    }
}
