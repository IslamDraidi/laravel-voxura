<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification
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
        $items     = $this->order->items()->with('product')->get();

        $mail = (new MailMessage)
            ->subject("Order Confirmed — #{$this->order->id}")
            ->greeting("Hello {$notifiable->name},")
            ->line('Your order has been placed successfully!')
            ->line("**Order #:** #{$this->order->id}")
            ->line("**Store:** {$storeName}")
            ->line('**Total:** ' . number_format((float) $this->order->grand_total, 2))
            ->line('**Status:** Processing');

        foreach ($items as $item) {
            $mail->line("• {$item->product->name} × {$item->quantity}");
        }

        $mail->action('View My Order', url('/orders/' . $this->order->id))
            ->line('Thank you for shopping on Voxura!');

        if ($store) {
            $mail->line("This order is fulfilled by **{$storeName}**. Powered by Voxura.");
        }

        return $mail->salutation('Voxura Team');
    }
}
