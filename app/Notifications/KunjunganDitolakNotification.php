<?php

namespace App\Notifications;

use App\Models\Kunjungan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class KunjunganDitolakNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Kunjungan $kunjungan,
        public string $alasan
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $tamu = $this->kunjungan->tamu;
        return [
            'event'         => 'ditolak',
            'kunjungan_id'  => $this->kunjungan->id,
            'tamu_id'       => $tamu?->id,
            'nama'          => $tamu?->nama,
            'instansi'      => $tamu?->instansi,
            'keperluan'     => $this->kunjungan->keperluan,
            'waktu'         => now()->format('d-m-Y H:i'),
            'alasan'        => $this->alasan,
            'message'       => "Kunjungan ditolak • {$tamu?->instansi} • {$this->kunjungan->keperluan}",
        ];
    }
}
