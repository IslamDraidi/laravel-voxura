<?php

namespace App\Console\Commands;

use App\Models\Store;
use App\Notifications\SubscriptionExpiryReminder;
use Illuminate\Console\Command;

class SendSubscriptionReminders extends Command
{
    protected $signature = 'stores:send-reminders';
    protected $description = 'Send subscription expiry reminders to stores expiring within 7 days';

    public function handle(): void
    {
        $stores = Store::approved()
            ->where('subscription_active', true)
            ->where('expiry_reminder_sent', false)
            ->where('subscription_expires_at', '<=', now()->addDays(7))
            ->where('subscription_expires_at', '>', now())
            ->get();

        foreach ($stores as $store) {
            $store->owner?->notify(new SubscriptionExpiryReminder($store));
            $store->update(['expiry_reminder_sent' => true]);
            $this->info("Reminder sent to: {$store->name}");
        }

        $this->info("Total reminders sent: {$stores->count()}");
    }
}
