<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Kunjungan;

class KunjunganSelesaiNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Kunjungan $kunjungan
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $tamu = $this->kunjungan->tamu;
        return [
            'event'         => 'selesai',
            'kunjungan_id'  => $this->kunjungan->id,
            'tamu_id'       => $tamu?->id,
            'nama'          => $tamu?->nama,
            'instansi'      => $tamu?->instansi,
            'keperluan'     => $this->kunjungan->keperluan,
            'waktu'         => now()->format('d-m-Y H:i'),
            'message'       => "Kunjungan Anda telah selesai • {$this->kunjungan->keperluan}",
        ];
    }
}
