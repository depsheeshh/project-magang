<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SurveyCreatedNotification extends Notification
{
    use Queueable;

    public $survey;

    public function __construct($survey)
    {
        $this->survey = $survey;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'event'  => 'survey_baru', // ✅ penting, biar match dengan JS
            'judul'  => $this->survey->judul ?? 'Survey',
            'user'   => $this->survey->user->name ?? 'Peserta',
            'waktu'  => now()->format('d-m-Y H:i'),
            'url'    => route('admin.surveys.index'),
        ];
    }
}
