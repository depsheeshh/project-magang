<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('kunjungan:auto-checkout')->everyMinute();
Schedule::command('rapat:mark-absent')->hourly();

// Jalankan command reminder tiap menit
Schedule::command('rapat:reminder')->everyMinute();
// Contoh lain: generate laporan tiap malam jam 23:00
Schedule::command('rapat:generate-report')->dailyAt('23:00');
Schedule::command('ruangan:update-dipakai')->everyMinute();
// Schedule::command('rapat:end-auto')->everyFiveMinutes();

