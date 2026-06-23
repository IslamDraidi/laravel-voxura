<?php

namespace App\Notifications;

use App\Models\Store;
use App\Models\StoreMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageApprovedNotification extends Notification
{
    public function __construct(
        public StoreMessage $message,
        public Store $store
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New message for {$this->store->name} via Voxura")
            ->greeting("Hello {$notifiable->name},")
            ->line("You have a new customer message on Voxura.")
            ->line("**From:** {$this->message->customer_name} ({$this->message->customer_email})")
            ->line("**Subject:** " . ($this->message->subject ?? 'General inquiry'))
            ->line("**Message:**")
            ->line($this->message->message)
            ->action('Reply in Dashboard', url('/store/messages'))
            ->line("Reply via your store dashboard. The customer's email is kept private until you reply.")
            ->salutation("Voxura Team");
    }

    public function toArray($notifiable): array
    {
        return [
            'type'             => 'new_message',
            'store_message_id' => $this->message->id,
            'customer_name'    => $this->message->customer_name,
            'subject'          => $this->message->subject,
            'preview'          => substr($this->message->message, 0, 100),
        ];
    }
}
