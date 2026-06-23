<?php

namespace App\Notifications;

use App\Models\Store;
use App\Models\StoreMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminMessageReviewNotification extends Notification
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
        $flags = implode(', ', $this->message->filter_flags ?? []);

        return (new MailMessage)
            ->subject("[Review Required] Flagged message for {$this->store->name}")
            ->greeting("Hello {$notifiable->name},")
            ->line("A customer message was flagged for review.")
            ->line("**Store:** {$this->store->name}")
            ->line("**From:** {$this->message->customer_name}")
            ->line("**Flags:** {$flags}")
            ->line("**Message:**")
            ->line($this->message->message)
            ->action('Review in Admin Panel', url('/admin/messages/' . $this->message->id))
            ->salutation("Voxura System");
    }

    public function toArray($notifiable): array
    {
        return [
            'type'             => 'flagged_message',
            'store_message_id' => $this->message->id,
            'store_name'       => $this->store->name,
            'flags'            => $this->message->filter_flags,
        ];
    }
}
