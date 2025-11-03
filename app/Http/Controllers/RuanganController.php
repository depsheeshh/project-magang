<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;
use App\Models\Rapat;
use Carbon\Carbon;

class RuanganController extends Controller
{
    public function updateDipakai()
    {
        $now = Carbon::now();

        // Reset semua ruangan
        Ruangan::query()->update(['dipakai' => 0]);

        // Cari ruangan yang sedang dipakai
        $ruanganAktif = Rapat::where('waktu_mulai', '<=', $now)
            ->where('waktu_selesai', '>=', $now)
            ->pluck('ruangan_id')
            ->filter()
            ->unique();


        if ($ruanganAktif->isNotEmpty()) {
            Ruangan::whereIn('id', $ruanganAktif)->update(['dipakai' => 1]);


            dd(
                $ruanganAktif,
                Ruangan::whereIn('id', $ruanganAktif)->toSql(),
                Ruangan::whereIn('id', $ruanganAktif)->getBindings(),
                Ruangan::whereIn('id', $ruanganAktif)->update(['dipakai' => 1])
            );

        }

        return redirect()->back()->with('success', 'Status ruangan berhasil diperbarui.');
    }
}
