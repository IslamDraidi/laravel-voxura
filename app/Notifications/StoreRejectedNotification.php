<?php

namespace App\Notifications;

use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StoreRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(public Store $store, public string $reason) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Update on your Voxura store application — {$this->store->name}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Thank you for applying to join Voxura.")
            ->line("After reviewing your application for **{$this->store->name}**, we were unable to approve it at this time.")
            ->line("**Reason:** {$this->reason}")
            ->line("You are welcome to reapply after addressing the issues mentioned above.")
            ->salutation("The Voxura Team");
    }

    public function toArray($notifiable): array
    {
        return [
            'type'       => 'store_rejected',
            'store_id'   => $this->store->id,
            'store_name' => $this->store->name,
            'reason'     => $this->reason,
            'message'    => "Your store \"{$this->store->name}\" was not approved.",
        ];
    }
}
