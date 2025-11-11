<?php

namespace App\Http\Controllers\Frontliner;

use App\Http\Controllers\Controller;
use App\Models\Rapat;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RapatController extends Controller
{
    // ✅ Daftar semua rapat (internal & eksternal)
    public function index()
    {
        $rapat = Rapat::with([
                'ruangan',
                'undangan.user.pegawai.instansi',
                'undanganInstansi.instansi'
            ])
            ->orderByDesc('waktu_mulai')
            ->paginate(10);

        return view('frontliner.rapat.index', compact('rapat'));
    }

    // ✅ Daftar rapat hari ini (opsional filter)
    public function today()
    {
        $today = Carbon::today();

        $rapat = Rapat::with([
                'ruangan',
                'undangan.user.pegawai.instansi',
                'undanganInstansi.instansi'
            ])
            ->whereDate('waktu_mulai', $today)
            ->orderBy('waktu_mulai')
            ->get();

        return view('frontliner.rapat.today', compact('rapat'));
    }

    // ✅ Detail rapat + status kehadiran peserta
    public function show(Rapat $rapat)
    {
        $rapat->load([
            'ruangan',
            'undangan.user.pegawai.instansi',
            'undanganInstansi.instansi'
        ]);

        return view('frontliner.rapat.show', compact('rapat'));
    }
}
