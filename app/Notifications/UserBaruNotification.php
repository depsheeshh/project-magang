<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserBaruNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $source;

    public function __construct($user, $source = 'register')
    {
        $this->user = $user;
        $this->source = $source;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'event' => 'user_baru',
            'nama'  => $this->user->name,
            'email' => $this->user->email,
            'source'=> $this->source, // register / form_tamu
            'waktu' => now()->format('d-m-Y H:i'),
            'url'   => route('admin.users.index'),
        ];
    }
}
