<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    public function __construct(public Order $order) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $items = $this->order->items()->with('product')->get();

        $mail = (new MailMessage)
            ->subject("New Order Received — #{$this->order->id}")
            ->greeting("Hello {$notifiable->name},")
            ->line('You have a new order on Voxura!')
            ->line("**Order #:** #{$this->order->id}")
            ->line('**Customer:** ' . ($this->order->user?->name ?? 'Guest'))
            ->line('**Total:** ' . number_format((float) $this->order->grand_total, 2));

        foreach ($items as $item) {
            $mail->line("• {$item->product->name} × {$item->quantity}");
        }

        $mail->action('View Order in Dashboard', url('/store/dashboard/orders'))
            ->salutation('Voxura Team');

        return $mail;
    }

    public function toArray($notifiable): array
    {
        return [
            'type'     => 'new_order',
            'order_id' => $this->order->id,
            'total'    => $this->order->grand_total,
            'customer' => $this->order->user?->name ?? 'Guest',
        ];
    }
}
