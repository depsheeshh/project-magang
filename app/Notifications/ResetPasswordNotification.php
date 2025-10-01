<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends BaseResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reset Password - Buku Tamu Digital')
            ->greeting('Halo ' . $notifiable->name . ' 👋')
            ->line('Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.')
            ->action('Reset Password', $this->resetUrl($notifiable))
            ->line('Jika Anda tidak meminta reset password, abaikan email ini.');
    }
}
