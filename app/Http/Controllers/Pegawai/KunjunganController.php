<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\HistoryLog;
use Illuminate\Http\Request;
use App\Notifications\KunjunganDitolakNotification;
use App\Notifications\KunjunganDisetujuiNotification;


class KunjunganController extends Controller
{
    // Riwayat kunjungan tamu ke pegawai ini
    public function riwayat(Request $request)
    {
        $request->validate([
        'filter' => 'nullable|in:selesai,ditolak',
    ]);

        $pegawaiRel = Auth::user()->pegawai ?? null;
        if (!$pegawaiRel) {
            abort(403, 'Akun ini tidak memiliki relasi pegawai.');
        }
        $pegawaiId = $pegawaiRel->id;

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
        $pegawaiRel = Auth::user()->pegawai ?? null;
        if (!$pegawaiRel) {
            abort(403, 'Akun ini tidak memiliki relasi pegawai.');
        }
        $pegawaiId = $pegawaiRel->id;

        // Pastikan kunjungan memang untuk pegawai ini
        if ($kunjungan->pegawai_id !== $pegawaiId) {
            abort(403, 'Tidak berhak mengubah kunjungan ini.');
        }

        if ($request->aksi === 'terima') {
        $kunjungan->status = 'sedang_bertamu';
        $kunjungan->alasan_penolakan = null; // bersihkan jika sebelumnya pernah ditolak

        // Hapus notifikasi "tamu baru"
        Auth::user()->notifications()
            ->whereJsonContains('data->kunjungan_id', $kunjungan->id)
            ->delete();

            User::role('frontliner')->each(function($f) use ($kunjungan) {
                $f->notifications()
                ->whereJsonContains('data->kunjungan_id', $kunjungan->id)
                ->delete();
            });

        // Kirim notifikasi ke tamu
        $kunjungan->tamu?->user?->notify(
            new KunjunganDisetujuiNotification($kunjungan)
        );

        HistoryLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'update',
            'table_name' => 'kunjungan',
            'record_id'  => $kunjungan->id,
            'reason'     => 'Pegawai menerima tamu',
            'old_values' => null,
            'new_values' => ['status' => 'sedang_bertamu'],
        ]);

    } elseif ($request->aksi === 'tolak') {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $kunjungan->status = 'ditolak';
        $kunjungan->alasan_penolakan = $request->reason;

        // Hapus notifikasi "tamu baru"
        Auth::user()->notifications()
            ->whereJsonContains('data->kunjungan_id', $kunjungan->id)
            ->delete();

            User::role('frontliner')->each(function($f) use ($kunjungan) {
                $f->notifications()
                ->whereJsonContains('data->kunjungan_id', $kunjungan->id)
                ->delete();
            });

        // Kirim notifikasi ke tamu
        $kunjungan->tamu?->user?->notify(
            new KunjunganDitolakNotification($kunjungan, $request->reason)
        );

        // Simpan ke history log
        HistoryLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'update',
            'table_name' => 'kunjungan',
            'record_id'  => $kunjungan->id,
            'reason'     => 'Pegawai menolak tamu dengan alasan: '.$request->reason,
            'old_values' => null,
            'new_values' => json_encode([
                'status' => 'ditolak',
                'alasan_penolakan' => $request->reason,
            ]),
        ]);
    }

        $kunjungan->save();

        return back()->with('success', 'Konfirmasi berhasil diperbarui.');
    }

}
