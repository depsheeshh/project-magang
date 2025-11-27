<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pegawai;
use Illuminate\Support\Str;

class RefreshApelQrCommand extends Command
{
    /**
     * Nama command artisan
     */
    protected $signature = 'apel:refresh-qr';

    /**
     * Deskripsi command
     */
    protected $description = 'Refresh QR Code pegawai untuk Apel Pagi setiap minggu';

    /**
     * Eksekusi command
     */
    public function handle()
    {
        $pegawai = Pegawai::all();

        foreach ($pegawai as $p) {
            // Generate token unik baru untuk QR
            $p->qr_token = Str::uuid(); // atau bisa pakai hash random
            $p->save();
        }

        $this->info('QR Code pegawai berhasil di-refresh.');
    }
}
