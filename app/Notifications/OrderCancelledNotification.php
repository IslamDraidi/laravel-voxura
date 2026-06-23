<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCancelledNotification extends Notification
{
    public function __construct(
        public Order $order,
        public string $reason = ''
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $store     = $this->order->store;
        $storeName = $store?->name ?? 'Voxura';

        $mail = (new MailMessage)
            ->subject("Order Cancelled — #{$this->order->id}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your order #{$this->order->id} from **{$storeName}** has been cancelled.");

        if ($this->reason) {
            $mail->line("**Reason:** {$this->reason}");
        }

        $mail->line('If you paid online, a refund will be processed within 5–7 business days.')
            ->action('Shop Again', url('/stores/' . ($store?->slug ?? '')))
            ->line("We're sorry for the inconvenience. You can contact {$storeName} through Voxura for more details.")
            ->salutation('Voxura Team');

        return $mail;
    }
}
