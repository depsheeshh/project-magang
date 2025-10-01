<?php

namespace App\Http\Controllers\Frontliner;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    // Halaman semua kunjungan (dengan filter status opsional)
    public function index(Request $request)
    {
        $query = Kunjungan::with(['tamu.user','pegawai.user','pegawai.bidang'])
            ->orderBy('waktu_masuk','desc');

        // filter status jika ada query string ?status=menunggu
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kunjungan = $query->get();

        return view('frontliner.kunjungan.index', compact('kunjungan'));
    }

    // Halaman khusus tamu menunggu
    public function menunggu()
    {
        $kunjungan = Kunjungan::with(['tamu','pegawai.user'])
            ->where('status','menunggu')
            ->orderBy('waktu_masuk','desc')
            ->get();

        return view('frontliner.index', compact('kunjungan'));
    }

    public function approve(Kunjungan $kunjungan)
    {
        if ($kunjungan->status === 'menunggu') {
            $kunjungan->update(['status' => 'sedang_bertamu']);
        }
        return back()->with('success','Kunjungan disetujui, tamu dipersilakan masuk.');
    }

    public function reject(Kunjungan $kunjungan)
    {
        if ($kunjungan->status === 'menunggu') {
            $kunjungan->update(['status' => 'ditolak']);
        }
        return back()->with('success','Kunjungan ditolak.');
    }

    public function checkout(Kunjungan $kunjungan)
    {
        if ($kunjungan->status === 'sedang_bertamu') {
            $kunjungan->update([
                'status' => 'selesai',
                'waktu_keluar' => now(),
            ]);
        }
        return back()->with('success','Kunjungan tamu berhasil di-checkout.');
    }
}
