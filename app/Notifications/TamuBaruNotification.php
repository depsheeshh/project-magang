<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TamuBaruNotification extends Notification
{
    use Queueable;

    protected $tamu;
    protected $kunjungan;

    public function __construct($tamu, $kunjungan)
    {
        $this->tamu = $tamu;
        $this->kunjungan = $kunjungan;
    }

    // Tentukan channel (database)
    public function via($notifiable)
    {
        return ['database'];
    }

    // Data yang disimpan di tabel notifications
    public function toDatabase($notifiable)
    {
        return [
            'tamu_id'   => $this->tamu->id,
            'nama'      => $this->tamu->nama,
            'instansi'  => $this->tamu->instansi,
            'keperluan' => $this->kunjungan->keperluan,
            'waktu'     => $this->kunjungan->waktu_masuk->format('d-m-Y H:i'),
            'message'   => "Tamu baru dari {$this->tamu->instansi} untuk keperluan {$this->kunjungan->keperluan}",
        ];
    }
}
