<?php

namespace App\Notifications;

use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StoreApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(public Store $store) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $planName   = ucfirst($this->store->plan_type ?? 'basic');
        $monthlyFee = number_format((float) ($this->store->monthly_fee ?? 0), 2);

        return (new MailMessage)
            ->subject("Your store has been approved — {$this->store->name}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Great news! Your store **{$this->store->name}** has been approved on Voxura.")
            ->line("Your store is now live and visible to customers.")
            ->line("**Your Plan:** {$planName}")
            ->line("**Monthly Fee:** \${$monthlyFee}")
            ->action('Visit Your Store', url("/stores/{$this->store->slug}"))
            ->line("Start adding products and reach thousands of customers.")
            ->salutation("Welcome to Voxura! — The Voxura Team");
    }

    public function toArray($notifiable): array
    {
        return [
            'type'       => 'store_approved',
            'store_id'   => $this->store->id,
            'store_name' => $this->store->name,
            'message'    => "Your store \"{$this->store->name}\" has been approved.",
        ];
    }
}
