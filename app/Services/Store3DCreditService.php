<?php

namespace App\Services;

use App\Models\Store;
use App\Notifications\Store3DLimitReachedNotification;
use Illuminate\Support\Facades\Log;

class Store3DCreditService
{
    public function canGenerate(Store $store): array
    {
        if (! $store->has3DAccess()) {
            return [
                'allowed' => false,
                'reason'  => 'no_3d_plan',
                'message' => 'Your current plan does not include 3D generation. Upgrade to Pro or Premium.',
            ];
        }

        if ($store->credits_balance <= 0) {
            return [
                'allowed' => false,
                'reason'  => 'no_credits',
                'message' => 'You have used all your 3D generation credits. Credits accumulate — check back next month or upgrade your plan for more.',
                'balance' => 0,
                'monthly' => $store->monthlyCredits(),
            ];
        }

        return [
            'allowed' => true,
            'balance' => $store->credits_balance,
        ];
    }

    public function deductCredit(Store $store): bool
    {
        if ($store->credits_balance <= 0) {
            return false;
        }

        $store->decrement('credits_balance');
        $store->increment('credits_used_total');
        $store->refresh();

        Log::info('3D credit deducted', [
            'store_id'        => $store->id,
            'store_name'      => $store->name,
            'credits_balance' => $store->credits_balance,
        ]);

        if ($store->credits_balance === 0) {
            $store->owner?->notify(new Store3DLimitReachedNotification($store));
        }

        return true;
    }

    public function refundCredit(Store $store): void
    {
        $store->increment('credits_balance');
        $store->decrement('credits_used_total');

        Log::info('3D credit refunded', [
            'store_id'   => $store->id,
            'store_name' => $store->name,
            'reason'     => 'generation_failed',
        ]);
    }

    public function grantCredits(Store $store, int $amount, string $reason = ''): void
    {
        $store->increment('credits_balance', $amount);
        $store->increment('credits_bonus', $amount);
        $store->increment('credits_granted_total', $amount);

        Log::info('3D bonus credits granted', [
            'store_id' => $store->id,
            'amount'   => $amount,
            'reason'   => $reason,
            'admin_id' => auth()->id(),
        ]);
    }

    public function resetUsageCounter(Store $store): void
    {
        $store->update(['credits_used_total' => 0]);

        Log::info('3D credit counter reset', [
            'store_id' => $store->id,
            'admin_id' => auth()->id(),
        ]);
    }

    public function topUpMonthly(Store $store): void
    {
        $monthly = $store->monthlyCredits();

        if ($monthly <= 0) {
            return;
        }

        $store->increment('credits_balance', $monthly);
        $store->increment('credits_granted_total', $monthly);
        $store->update(['credits_last_topped_up_at' => now()]);

        Log::info('Monthly 3D credits added', [
            'store_id' => $store->id,
            'credits'  => $monthly,
            'balance'  => $store->fresh()->credits_balance,
        ]);
    }
}
