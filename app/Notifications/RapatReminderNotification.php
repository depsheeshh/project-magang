<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Rapat;

class RapatReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $rapat;

    public function __construct(Rapat $rapat)
    {
        $this->rapat = $rapat;
    }

    /**
     * Tentukan channel notifikasi
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail']; // bisa tambah 'broadcast' kalau real-time
    }

    /**
     * Format notifikasi untuk email
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reminder Rapat: ' . $this->rapat->judul)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Anda diundang dalam rapat berikut:')
            ->line('Judul: ' . $this->rapat->judul)
            ->line('Waktu: ' . $this->rapat->waktu_mulai->format('d/m/Y H:i'))
            ->line('Lokasi: ' . $this->rapat->lokasi . ' - ' . ($this->rapat->ruangan->nama_ruangan ?? '-'))
            ->action('Lihat Detail Rapat', route('admin.rapat.show', $this->rapat->id))
            ->line('Mohon hadir tepat waktu.');
    }

    /**
     * Format notifikasi untuk database
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'rapat_id'   => $this->rapat->id,
            'judul'      => $this->rapat->judul,
            'waktu_mulai'=> $this->rapat->waktu_mulai->toDateTimeString(),
            'lokasi'     => $this->rapat->lokasi,
            'ruangan'    => $this->rapat->ruangan->nama_ruangan ?? '-',
            'pesan'      => 'Reminder rapat akan dimulai dalam 30 menit.',
        ];
    }
}
