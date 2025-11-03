<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Rapat;
use App\Models\Ruangan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateRuanganDipakai extends Command
{
    protected $signature = 'ruangan:update-dipakai';
    protected $description = 'Update status dipakai ruangan berdasarkan jadwal rapat aktif saat ini';

    public function handle()
    {

        // Pakai timezone app (sesuaikan di config/app.php 'timezone' => 'Asia/Jakarta')
        $now = Carbon::now();

        // Ambil ID ruangan yang sedang ada rapat aktif (mulai <= now <= selesai)
        $ruanganAktif = Rapat::where('waktu_mulai', '<=', $now)
            ->where('waktu_selesai', '>=', $now)
            ->pluck('ruangan_id')
            ->filter()
            ->unique();

            $sql = Rapat::where('waktu_mulai', '<=', $now)
                ->where('waktu_selesai', '>=', $now)
                ->toSql();

            dd($sql);

        // Ambil semua ruangan beserta status sekarang
        $allRuangan = Ruangan::select('id', 'dipakai')->get();

        // Tentukan target status untuk masing-masing ruangan
        $targetOn  = collect($ruanganAktif);
        $targetOff = $allRuangan->pluck('id')->diff($targetOn);

        DB::beginTransaction();
        try {
            if ($targetOn->isNotEmpty()) {
                Ruangan::whereIn('id', $targetOn)->where('dipakai', 0)->update(['dipakai' => 1]);
            }
            if ($targetOff->isNotEmpty()) {
                Ruangan::whereIn('id', $targetOff)->where('dipakai', 1)->update(['dipakai' => 0]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Gagal update status ruangan: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->info(sprintf(
            'Selesai. Aktif: %d | Nonaktif: %d | Waktu: %s',
            $targetOn->count(),
            $targetOff->count(),
            $now->toDateTimeString()
        ));

        return self::SUCCESS;
    }
}
