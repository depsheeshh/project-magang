<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Support\Facades\Auth;

class KunjunganController extends Controller
{
    // Riwayat kunjungan tamu ke pegawai ini
    public function riwayat()
    {
        $pegawaiId = Auth::user()->pegawai->id; // asumsi user punya relasi ke model Pegawai
        $riwayat = Kunjungan::with('tamu')
            ->where('pegawai_id', $pegawaiId)
            ->where('status', 'selesai')
            ->orderByDesc('waktu_masuk')
            ->get();

        return view('pegawai.kunjungan.riwayat', compact('riwayat'));
    }

    // Notifikasi tamu yang sedang datang
    public function notifikasi()
    {
        $pegawaiId = Auth::user()->pegawai->id;
        $notifikasi = Kunjungan::with('tamu')
            ->where('pegawai_id', $pegawaiId)
            ->whereIn('status', ['menunggu','sedang_bertamu'])
            ->orderBy('waktu_masuk','desc')
            ->get();

        return view('pegawai.kunjungan.notifikasi', compact('notifikasi'));
    }
}

