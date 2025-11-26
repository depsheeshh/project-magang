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

        // Tambahkan summary kehadiran agar frontliner langsung lihat angka
        $rapat->getCollection()->transform(function ($r) {
            $r->total = $r->undangan->count();
            $r->hadir = $r->undangan->whereIn('status_kehadiran', ['hadir','selesai'])->count();
            $r->tidak = $r->undangan->where('status_kehadiran','tidak_hadir')->count();
            $r->pending = $r->undangan->where('status_kehadiran','pending')->count();
            return $r;
        });

        return view('frontliner.rapat.index', compact('rapat'));
    }

    // ✅ Daftar rapat hari ini
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

        $rapat->transform(function ($r) {
            $r->total = $r->undangan->count();
            $r->hadir = $r->undangan->whereIn('status_kehadiran', ['hadir','selesai'])->count();
            $r->tidak = $r->undangan->where('status_kehadiran','tidak_hadir')->count();
            $r->pending = $r->undangan->where('status_kehadiran','pending')->count();
            return $r;
        });

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

        // Tambahkan summary agar detail juga jelas
        $rapat->total = $rapat->undangan->count();
        $rapat->hadir = $rapat->undangan->whereIn('status_kehadiran', ['hadir','selesai'])->count();
        $rapat->tidak = $rapat->undangan->where('status_kehadiran','tidak_hadir')->count();
        $rapat->pending = $rapat->undangan->where('status_kehadiran','pending')->count();

        return view('frontliner.rapat.show', compact('rapat'));
    }
}
