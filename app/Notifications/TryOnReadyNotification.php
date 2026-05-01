<?php

namespace App\Notifications;

use App\Models\VirtualTryon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TryOnReadyNotification extends Notification
{
    use Queueable;

    public function __construct(private VirtualTryon $tryon) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $product = $this->tryon->product;
        $url = route('products.show', $product->slug).'#tryon-'.$this->tryon->id;

        return (new MailMessage)
            ->subject('Your virtual try-on is ready! — Voxura')
            ->greeting("Hi {$notifiable->name},")
            ->line("Your 3D virtual try-on for **{$product->name}** is ready to view.")
            ->line('Open the link below to rotate, zoom, and see how it looks on you.')
            ->action('View My Try-On', $url)
            ->line('3D fitting is approximate — actual fit may vary slightly.')
            ->salutation('— The Voxura Team');
    }
}
