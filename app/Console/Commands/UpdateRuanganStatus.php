<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rapat;
use App\Models\Ruangan;

class UpdateRuanganStatus extends Command
{
    protected $signature = 'ruangan:update-status';
    protected $description = 'Update status dipakai ruangan berdasarkan jadwal rapat';

    public function handle()
    {
        $now = now();

        // Reset semua ruangan
        Ruangan::query()->update(['dipakai' => 0]);

        // Set dipakai untuk ruangan yang sedang ada rapat
        $ruanganDipakai = Rapat::where('waktu_mulai','<=',$now)
            ->where('waktu_selesai','>=',$now)
            ->where('status','!=','dibatalkan')
            ->pluck('ruangan_id')
            ->unique();

        Ruangan::whereIn('id', $ruanganDipakai)->update(['dipakai' => 1]);

        $this->info('Status ruangan berhasil diperbarui.');
    }
}

