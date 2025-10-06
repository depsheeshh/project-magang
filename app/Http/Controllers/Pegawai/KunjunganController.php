<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    // Riwayat kunjungan tamu ke pegawai ini
    public function riwayat(Request $request)
    {
        $pegawaiId = Auth::user()->pegawai->id;

        $query = Kunjungan::with('tamu')
            ->where('pegawai_id', $pegawaiId)
            ->whereIn('status', ['selesai','ditolak']) // default: tampilkan selesai & ditolak
            ->orderByDesc('waktu_masuk');

        // Filter berdasarkan tab
        if ($request->filter === 'selesai') {
            $query->where('status', 'selesai');
        } elseif ($request->filter === 'ditolak') {
            $query->where('status', 'ditolak');
        }

        $riwayat = $query->get();

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

    // Konfirmasi pegawai: bisa menerima atau tidak
    public function konfirmasi(Request $request, Kunjungan $kunjungan)
    {
        $pegawaiId = Auth::user()->pegawai->id;

        // Pastikan kunjungan memang untuk pegawai ini
        if ($kunjungan->pegawai_id !== $pegawaiId) {
            abort(403, 'Tidak berhak mengubah kunjungan ini.');
        }

        if ($request->aksi === 'terima') {
            $kunjungan->status = 'sedang_bertamu';
        } elseif ($request->aksi === 'tolak') {
            $kunjungan->status = 'ditolak';
        }

        $kunjungan->save();

        return back()->with('success', 'Konfirmasi berhasil diperbarui.');
    }
}
