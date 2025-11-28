<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Str;
use App\Models\Pegawai;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('apel:generate-token', function () {
    $this->info('Generate token apel pagi...');

    if (now()->isMonday()) {
        Pegawai::all()->each(function($pegawai) {
            $pegawai->apel_token = Str::random(32);
            $pegawai->save();
        });
        $this->info('Token apel pagi berhasil digenerate ulang untuk semua pegawai.');
    } else {
        $this->warn('Hari ini bukan Senin, token tidak digenerate.');
    }
})->describe('Generate ulang token apel pagi setiap Senin');

Schedule::command('kunjungan:auto-checkout')->everyMinute();
Schedule::command('rapat:mark-absent')->hourly();

// Jalankan command reminder tiap menit
Schedule::command('rapat:reminder')->everyMinute();
// Contoh lain: generate laporan tiap malam jam 23:00
Schedule::command('rapat:generate-report')->dailyAt('23:00');
// Schedule::command('ruangan:update-dipakai')->everyMinute();
Schedule::command('ruangan:update-status')->everyMinute();
Schedule::command('rapat:update-status')->everyMinute();
Schedule::command('apel:generate-token')->weeklyOn(1, '05:00');

// Schedule::command('rapat:end-auto')->everyFiveMinutes();

