<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InstansiBaruNotification extends Notification
{
    use Queueable;

    protected $instansi;
    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($instansi, $user)
    {
        $this->instansi = $instansi;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'event' => 'instansi_baru',
            'nama_instansi' => $this->instansi->nama_instansi,
            'user' => $this->user->name,
            'waktu' => now()->toDateTimeString(),
        ];
    }
}
