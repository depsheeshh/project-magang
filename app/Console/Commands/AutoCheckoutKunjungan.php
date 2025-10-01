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
    protected $signature = 'kunjungan:auto-checkout {--duration=2}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto checkout kunjungan setelah durasi tertentu (jam)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $duration = (int) $this->option('duration');
        $limit = Carbon::now()->subHours($duration);

        $kunjungan = Kunjungan::where('status','sedang_bertamu')
            ->where('waktu_masuk','<=',$limit)
            ->get();

        foreach ($kunjungan as $k) {
            $k->update([
                'status' => 'selesai',
                'waktu_keluar' => now(),
            ]);
        }

        $this->info("Auto checkout selesai untuk {$kunjungan->count()} kunjungan.");
    }
}
