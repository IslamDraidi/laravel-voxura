<?php

namespace App\Console\Commands;

use App\Models\Store;
use Illuminate\Console\Command;

class SuspendExpiredStores extends Command
{
    protected $signature = 'stores:suspend-expired';
    protected $description = 'Suspend stores with expired subscriptions';

    public function handle(): void
    {
        $stores = Store::approved()
            ->where('subscription_active', true)
            ->where('subscription_expires_at', '<', now())
            ->get();

        foreach ($stores as $store) {
            $store->update([
                'status'            => 'suspended',
                'suspended_at'      => now(),
                'suspension_reason' => 'Subscription expired on '
                    . $store->subscription_expires_at->format('d M Y'),
            ]);
            $this->info("Suspended (expired): {$store->name}");
        }

        $this->info("Total suspended: {$stores->count()}");
    }
}
