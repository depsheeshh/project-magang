<?php

namespace App\Http\Controllers\Frontliner;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\HistoryLog;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    // Halaman semua kunjungan (dengan filter status opsional)
    public function index(Request $request)
    {
        $query = Kunjungan::with(['tamu.user','pegawai.user','pegawai.bidang'])
            ->orderBy('waktu_masuk','desc');

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

    // Setujui kunjungan
    public function approve(Kunjungan $kunjungan)
    {
        if ($kunjungan->status === 'menunggu') {
            $kunjungan->update(['status' => 'sedang_bertamu']);
        }
        return back()->with('success','Kunjungan disetujui, tamu dipersilakan masuk.');
    }

    // Tolak kunjungan (wajib alasan)
    public function reject(Request $request, Kunjungan $kunjungan)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        if ($kunjungan->status === 'menunggu') {
            $kunjungan->update([
                'status' => 'ditolak',
                'alasan_penolakan' => $request->reason,
            ]);

            // Simpan ke history log
            HistoryLog::create([
                'user_id'    => auth()->id(),
                'action'     => 'update',
                'table_name' => 'kunjungan',
                'record_id'  => $kunjungan->id,
                'reason'     => 'Menolak tamu dengan alasan: '.$request->reason,
                'old_values' => null,
                'new_values' => json_encode([
                    'status' => 'ditolak',
                    'alasan_penolakan' => $request->reason,
                ]),
            ]);
        }

        return back()->with('success','Kunjungan berhasil ditolak dengan alasan.');
    }

    // Checkout kunjungan
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
