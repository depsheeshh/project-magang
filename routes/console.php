<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('kunjungan:auto-checkout --duration=2')
    ->everyTenMinutes(); // bisa diganti everyMinute(), hourly(), dll
