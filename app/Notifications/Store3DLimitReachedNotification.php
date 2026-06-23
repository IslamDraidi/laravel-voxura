<?php

namespace App\Notifications;

use App\Models\Store;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Store3DLimitReachedNotification extends Notification
{
    public function __construct(public Store $store) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $monthly = $this->store->monthlyCredits();

        return (new MailMessage)
            ->subject("You've used all your 3D credits — {$this->store->name}")
            ->greeting("Hello {$notifiable->name},")
            ->line("You have used all **{$monthly}** of your monthly 3D generation credits for **{$this->store->name}**.")
            ->line('Your credits accumulate — new credits will be added at the start of next month.')
            ->line('Need more credits now? Upgrade to a higher plan or contact Voxura support.')
            ->action('View Your Dashboard', url('/store/dashboard'))
            ->line('Current plan: ' . ucfirst($this->store->plan_type) . " ({$monthly} credits/month)")
            ->salutation('Voxura Team');
    }
}
