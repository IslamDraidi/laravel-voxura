<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends BaseResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $count = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);
        $actionUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject(Lang::get('Reset Password Notification'))
            ->view('emails.reset-password', [
                'actionUrl' => $actionUrl,
                'count'     => $count,
                'userName'  => $notifiable->name,
            ]);
    }
}
