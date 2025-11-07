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

        $pegawaiRel = Auth::user()->pegawai;
        if (!$pegawaiRel) {
            abort(403, 'Akun ini tidak memiliki relasi pegawai.');
        }

        $query = Kunjungan::with('tamu')
            ->where('pegawai_id', $pegawaiRel->id)
            ->whereIn('status', ['selesai','ditolak'])
            ->orderByDesc('waktu_masuk');

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
        $pegawaiRel = Auth::user()->pegawai;
        if (!$pegawaiRel) {
            abort(403, 'Akun ini tidak memiliki relasi pegawai.');
        }

        $notifikasi = Kunjungan::with('tamu')
            ->where('pegawai_id', $pegawaiRel->id)
            ->whereIn('status', ['menunggu','sedang_bertamu'])
            ->orderByDesc('waktu_masuk')
            ->get();

        return view('pegawai.kunjungan.notifikasi', compact('notifikasi'));
    }

    // Konfirmasi pegawai: bisa menerima atau tidak
    public function konfirmasi(Request $request, Kunjungan $kunjungan)
    {
        $pegawaiRel = Auth::user()->pegawai;
        if (!$pegawaiRel) {
            abort(403, 'Akun ini tidak memiliki relasi pegawai.');
        }

        if ($kunjungan->pegawai_id !== $pegawaiRel->id) {
            abort(403, 'Tidak berhak mengubah kunjungan ini.');
        }

        if ($request->aksi === 'terima') {
            $kunjungan->status = 'sedang_bertamu';
            $kunjungan->alasan_penolakan = null;

            // Hapus notifikasi "tamu baru"
            Auth::user()->notifications()
                ->whereJsonContains('data->kunjungan_id', $kunjungan->id)
                ->delete();

            User::role('frontliner')->each(function($f) use ($kunjungan) {
                $f->notifications()
                  ->whereJsonContains('data->kunjungan_id', $kunjungan->id)
                  ->delete();
            });

            $kunjungan->tamu?->user?->notify(
                new KunjunganDisetujuiNotification($kunjungan)
            );

            HistoryLog::create([
                'user_id'    => Auth::id(),
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

            Auth::user()->notifications()
                ->whereJsonContains('data->kunjungan_id', $kunjungan->id)
                ->delete();

            User::role('frontliner')->each(function($f) use ($kunjungan) {
                $f->notifications()
                  ->whereJsonContains('data->kunjungan_id', $kunjungan->id)
                  ->delete();
            });

            $kunjungan->tamu?->user?->notify(
                new KunjunganDitolakNotification($kunjungan, $request->reason)
            );

            HistoryLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'update',
                'table_name' => 'kunjungan',
                'record_id'  => $kunjungan->id,
                'reason'     => 'Pegawai menolak tamu dengan alasan: '.$request->reason,
                'old_values' => null,
                'new_values' => [
                    'status' => 'ditolak',
                    'alasan_penolakan' => $request->reason,
                ],
            ]);
        }

        $kunjungan->save();

        return back()->with('success', 'Konfirmasi berhasil diperbarui.');
    }
}
