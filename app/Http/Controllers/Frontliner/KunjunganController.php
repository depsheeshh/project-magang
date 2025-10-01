<?php

namespace App\Http\Controllers\Frontliner;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function index()
    {
        // ambil kunjungan dengan status menunggu
        $kunjunganMenunggu = Kunjungan::with([
                'tamu',              // relasi ke tamu
                'pegawai.user',      // relasi pegawai + user
                'pegawai.bidang'     // kalau mau tampilkan bidang lewat pegawai
            ])
            ->where('status','menunggu')
            ->orderBy('waktu_masuk','desc')
            ->get();

        return view('frontliner.index', compact('kunjunganMenunggu'));
    }

    public function approve(Kunjungan $kunjungan)
    {
        $kunjungan->update([
            'status' => 'sedang_bertamu',
            'waktu_masuk' => now(),
        ]);

        return back()->with('status','Kunjungan disetujui, tamu dipersilakan masuk.');
    }

    public function reject(Kunjungan $kunjungan)
    {
        $kunjungan->update([
            'status' => 'ditolak',
        ]);

        return back()->with('status','Kunjungan ditolak.');
    }

    public function checkout(Kunjungan $kunjungan)
    {
        $kunjungan->update([
            'status' => 'selesai',
            'waktu_keluar' => now(),
        ]);

        return back()->with('status','Kunjungan selesai (checkout).');
    }
}
