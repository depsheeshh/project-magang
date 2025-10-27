<?php

namespace App\Listeners;

use App\Events\InstansiCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Notifications\InstansiBaruNotification;

class SendInstansiNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(InstansiCreated $event)
    {
        // Cari semua admin
        $admins = User::role('admin')->get();

        // Kirim notifikasi ke semua admin
        foreach ($admins as $admin) {
            $admin->notify(new InstansiBaruNotification($event->instansi, $event->user));
        }
    }
}
