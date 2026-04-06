<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFailedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private Order $order,
        private Payment $payment,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $retryUrl = route('payment.retry', $this->order);

        return (new MailMessage)
            ->subject("Having trouble paying? We can help — Voxura")
            ->greeting("Hi {$notifiable->name},")
            ->line("We noticed your payment for Order #{$this->order->id} didn't go through.")
            ->line("**Reason:** {$this->payment->failure_reason}")
            ->line("**Attempt:** {$this->payment->attempts} of " . config('payment.max_retry_attempts', 3))
            ->action('Retry Payment', $retryUrl)
            ->line('If you continue to have trouble, please don\'t hesitate to contact our support team.')
            ->salutation('— The Voxura Team');
    }
}
