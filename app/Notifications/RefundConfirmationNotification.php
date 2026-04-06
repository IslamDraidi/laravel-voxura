<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundConfirmationNotification extends Notification
{
    use Queueable;

    public function __construct(
        private Order $order,
        private Refund $refund,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $timeline = match ($this->refund->gateway) {
            'paypal' => '3–5 business days',
            'tap' => '5���7 business days',
            default => '5–10 business days',
        };

        return (new MailMessage)
            ->subject("Your refund has been processed — Voxura")
            ->greeting("Hi {$notifiable->name},")
            ->line("We've processed a refund for your Order #{$this->order->id}.")
            ->line("**Refund Amount:** $" . number_format($this->refund->amount, 2))
            ->line("**Reason:** {$this->refund->reason}")
            ->line("**Expected Timeline:** Funds should appear in your account within {$timeline}.")
            ->action('View Order', route('orders.show', $this->order))
            ->line('If you have any questions about this refund, please contact our support team.')
            ->salutation('— The Voxura Team');
    }
}
