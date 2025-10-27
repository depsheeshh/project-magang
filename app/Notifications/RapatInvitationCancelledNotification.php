<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Rapat;

class RapatInvitationCancelledNotification extends Notification
{
    use Queueable;

    protected $rapat;

    public function __construct(Rapat $rapat)
    {
        $this->rapat = $rapat;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'event'       => 'rapat_undangan_dibatalkan',
            'rapat_id'    => $this->rapat->id,
            'judul'       => $this->rapat->judul,
            'waktu'       => $this->rapat->waktu_mulai?->format('d-m-Y H:i'),
            'waktu_notif' => now()->format('d-m-Y H:i'),
        ];
    }
}
