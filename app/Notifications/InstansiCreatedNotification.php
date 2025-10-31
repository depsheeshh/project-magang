<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InstansiCreatedNotification extends Notification
{
    use Queueable;

    public $instansi;

    public function __construct($instansi)
    {
        $this->instansi = $instansi;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'event' => 'instansi_baru',
            'id' => $this->instansi->id,
            'nama_instansi' => $this->instansi->nama,
            'user' => Auth::user()->name ?? 'Sistem',
            'waktu' => now()->format('d M Y H:i'),
        ];
    }
}
