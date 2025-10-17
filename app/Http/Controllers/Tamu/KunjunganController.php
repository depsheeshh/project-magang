<?php

namespace App\Http\Controllers\Tamu;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TamuBaruNotification;
use App\Models\Survey;
use Illuminate\Support\Str;

class KunjunganController extends Controller
{
    public function create()
    {
        return view('tamu.kunjungan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'keperluan'  => 'required|string|max:255',
        ]);

        $tamu = Auth::user()->tamu;

        $kunjungan = Kunjungan::create([
            'tamu_id'     => $tamu->id,
            'pegawai_id'  => $request->pegawai_id,
            'keperluan'   => $request->keperluan,
            'status'      => 'menunggu',
            'waktu_masuk' => now(),
        ]);

        // === Kirim notifikasi ===
        $pegawai = Pegawai::with('user')->find($request->pegawai_id);
        if ($pegawai && $pegawai->user) {
            $pegawai->user->notify(new TamuBaruNotification($tamu, $kunjungan));
        }

        $frontliners = User::role('frontliner')->get();
        foreach ($frontliners as $f) {
            $f->notify(new TamuBaruNotification($tamu, $kunjungan));
        }

        return redirect()->route('tamu.kunjungan.status')
            ->with('success','Kunjungan berhasil ditambahkan');
    }

    public function status()
    {
        $tamu = Auth::user()->tamu;
        $kunjungan = Kunjungan::with('pegawai.user')
            ->where('tamu_id',$tamu->id)
            ->latest()
            ->get();

        return view('tamu.kunjungan.status', compact('kunjungan'));
    }

    /**
     * Checkout kunjungan (via AJAX).
     */
    public function checkout(Request $request, $id)
    {
        $user = Auth::user();
        $kunjungan = Kunjungan::findOrFail($id);

        // Validasi pemilik kunjungan
        if ($user->hasRole('tamu') && $kunjungan->tamu_id !== $user->tamu->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($kunjungan->status !== 'sedang_bertamu') {
            return response()->json(['success' => false, 'message' => 'Kunjungan tidak dalam status sedang bertamu'], 400);
        }

        $kunjungan->update([
            'status'       => 'selesai',
            'waktu_keluar' => now(),
        ]);

        // ✅ Buat record survey kosong TANPA link
        Survey::firstOrCreate(
            ['kunjungan_id' => $kunjungan->id, 'user_id' => $user->id],
            ['rating' => null, 'feedback' => null, 'link' => null]
        );

        // Bersihkan notifikasi
        $pegawai = $kunjungan->pegawai?->user;
        if ($pegawai) {
            $pegawai->notifications()
                ->whereJsonContains('data->kunjungan_id', $kunjungan->id)
                ->delete();
        }

        $frontliners = User::role('frontliner')->get();
        foreach ($frontliners as $f) {
            $f->notifications()
                ->whereJsonContains('data->kunjungan_id', $kunjungan->id)
                ->delete();
        }

        return response()->json(['success' => true, 'message' => 'Checkout berhasil.']);
    }

}
