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
        $request->validate([
            'status' => 'nullable|in:menunggu,sedang_bertamu,selesai,ditolak',
        ]);

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

            // Catat ke history log
            HistoryLog::create([
                'user_id'    => auth()->id(),
                'action'     => 'update',
                'table_name' => 'kunjungan',
                'record_id'  => $kunjungan->id,
                'reason'     => 'Frontliner menyetujui kunjungan',
                'old_values' => null,
                'new_values' => ['status' => 'sedang_bertamu'],
            ]);
        }
        return back()->with('success','Kunjungan disetujui, tamu dipersilakan masuk.');
    }

    // Tolak kunjungan (wajib alasan)
    public function reject(Request $request, Kunjungan $kunjungan)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // Sanitasi alasan agar tidak bisa sisipkan script
        $reason = strip_tags($validated['reason']);

        if ($kunjungan->status === 'menunggu') {
            $kunjungan->update([
                'status' => 'ditolak',
                'alasan_penolakan' => $reason,
            ]);

            // Simpan ke history log
            HistoryLog::create([
                'user_id'    => auth()->id(),
                'action'     => 'update',
                'table_name' => 'kunjungan',
                'record_id'  => $kunjungan->id,
                'reason'     => 'Menolak tamu dengan alasan: '.$reason,
                'old_values' => null,
                'new_values' => [
                    'status' => 'ditolak',
                    'alasan_penolakan' => $reason,
                ],
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

            // Catat ke history log
            HistoryLog::create([
                'user_id'    => auth()->id(),
                'action'     => 'update',
                'table_name' => 'kunjungan',
                'record_id'  => $kunjungan->id,
                'reason'     => 'Frontliner melakukan checkout kunjungan',
                'old_values' => null,
                'new_values' => [
                    'status' => 'selesai',
                    'waktu_keluar' => now()->toDateTimeString(),
                ],
            ]);
        }
        return back()->with('success','Kunjungan tamu berhasil di-checkout.');
    }
}
