<?php

namespace App\Console\Commands;

use App\Models\Store;
use App\Services\Store3DCreditService;
use Illuminate\Console\Command;

class TopUp3DCredits extends Command
{
    protected $signature   = 'stores:topup-3d-credits';
    protected $description = 'Add monthly 3D generation credits to all active Pro and Premium stores';

    public function __construct(private Store3DCreditService $credits)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->info('Starting monthly 3D credit top-up...');

        $stores = Store::where('status', 'approved')
            ->where('subscription_active', true)
            ->whereIn('plan_type', ['pro', 'premium', 'custom'])
            ->get();

        $count = 0;

        foreach ($stores as $store) {
            $monthly = $store->monthlyCredits();

            if ($monthly <= 0) {
                continue;
            }

            $this->credits->topUpMonthly($store);
            $count++;

            $this->line("  ✓ {$store->name}: +{$monthly} credits (balance: {$store->fresh()->credits_balance})");
        }

        $this->info("Done. Topped up {$count} stores.");
    }
}
