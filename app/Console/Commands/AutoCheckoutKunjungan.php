<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kunjungan;
use Carbon\Carbon;

class AutoCheckoutKunjungan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kunjungan:auto-checkout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto checkout kunjungan yang belum checkout setelah batas waktu tertentu';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $batasJam = 2;
        $now = now();

        $affected = Kunjungan::whereNull('checkout_time')
            ->where('waktu_masuk', '<', $now->subHours($batasJam))
            ->update([
                'checkout_time' => now(),
                'status' => 'selesai'
            ]);

        $this->info("Auto checkout berhasil untuk {$affected} kunjungan.");
    }
}
