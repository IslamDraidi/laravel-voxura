<?php

namespace App\Notifications;

use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiryReminder extends Notification
{
    use Queueable;

    public function __construct(public Store $store) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $days = $this->store->days_until_expiry;

        return (new MailMessage)
            ->subject("Your Voxura subscription expires in {$days} days")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your subscription for **{$this->store->name}** expires in **{$days} days**.")
            ->line("Expiry date: **{$this->store->subscription_expires_at->format('d M Y')}**")
            ->line("To keep your store active, please renew your subscription before the expiry date.")
            ->line("If your subscription expires, your store will be automatically suspended until renewed.")
            ->salutation("The Voxura Team");
    }

    public function toArray($notifiable): array
    {
        return [
            'type'       => 'subscription_expiry',
            'store_id'   => $this->store->id,
            'store_name' => $this->store->name,
            'days_left'  => $this->store->days_until_expiry,
            'message'    => "Your subscription for \"{$this->store->name}\" expires soon.",
        ];
    }
}
