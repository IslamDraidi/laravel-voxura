<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminPaymentBlockedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private Order $order,
        private Payment $payment,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $customer = $this->order->user;
        $adminUrl = route('admin.orders.show', $this->order);

        return (new MailMessage)
            ->subject("⚠️ Payment Blocked — Order #{$this->order->id}")
            ->greeting('Payment Alert')
            ->line("A customer has reached the maximum payment attempts and their order has been blocked.")
            ->line("**Customer:** {$customer->name} ({$customer->email})")
            ->line("**Order:** #{$this->order->id}")
            ->line("**Amount:** $" . number_format($this->order->grand_total, 2))
            ->line("**Last Failure:** {$this->payment->failure_reason}")
            ->line("**Gateway:** " . ucfirst($this->payment->gateway))
            ->line("**Total Attempts:** {$this->payment->attempts}")
            ->action('View Order', $adminUrl)
            ->salutation('— Voxura System');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_blocked',
            'order_id' => $this->order->id,
            'customer_name' => $this->order->user->name ?? 'Unknown',
            'customer_email' => $this->order->user->email ?? '',
            'amount' => $this->order->grand_total,
            'failure_reason' => $this->payment->failure_reason,
            'gateway' => $this->payment->gateway,
            'attempts' => $this->payment->attempts,
        ];
    }
}
