<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderShippedNotification extends Notification
{
    public function __construct(public Order $order) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $store     = $this->order->store;
        $storeName = $store?->name ?? 'Voxura';

        $mail = (new MailMessage)
            ->subject("Your order is on its way! — #{$this->order->id}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Great news! Your order #{$this->order->id} has been shipped.")
            ->line("**Store:** {$storeName}");

        if ($this->order->tracking_number ?? null) {
            $mail->line("**Tracking Number:** {$this->order->tracking_number}");
        }

        $mail->line('**Estimated Delivery:** 3–5 business days')
            ->action('Track My Order', url('/orders/' . $this->order->id))
            ->line("If you have any questions, contact {$storeName} through Voxura.")
            ->salutation('Voxura Team');

        return $mail;
    }
}
