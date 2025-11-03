<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rapat;
use Carbon\Carbon;

class RapatReminder extends Command
{
    protected $signature = 'rapat:reminder';
    protected $description = 'Kirim notifikasi reminder rapat yang akan dimulai dalam 30 menit';

    public function handle()
    {
        $rapat = Rapat::whereBetween('waktu_mulai', [
            Carbon::now(),
            Carbon::now()->addMinutes(30)
        ])->get();

        foreach ($rapat as $r) {
            foreach ($r->undangan as $undangan) {
                $undangan->user?->notify(new \App\Notifications\RapatReminderNotification($r));
            }
        }

        $this->info('Reminder rapat terkirim: ' . $rapat->count());
    }
}
