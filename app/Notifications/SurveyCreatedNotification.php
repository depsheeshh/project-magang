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
            'title' => 'Survey Baru',
            'message' => "Survey '{$this->survey->judul}' baru saja dibuat.",
            'url' => route('admin.surveys.index'),
        ];
    }
}
