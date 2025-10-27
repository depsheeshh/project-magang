<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RapatUndangan;
use Carbon\Carbon;

class MarkAbsentRapat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rapat:mark-absent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menandai peserta rapat yang belum check-in sebagai Tidak Hadir setelah rapat selesai';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Cari undangan yang masih pending tapi rapatnya sudah selesai
        $count = RapatUndangan::where('status_kehadiran', 'pending')
            ->whereHas('rapat', function($q) use ($now) {
                $q->where('waktu_selesai', '<', $now);
            })
            ->update([
                'status_kehadiran' => 'tidak hadir',
                'updated_id'       => 0, // bisa isi 0/system
            ]);

        $this->info("Berhasil menandai {$count} undangan sebagai Tidak Hadir.");
    }
}
