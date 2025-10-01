<?php

namespace App\Http\Controllers\Tamu;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        Kunjungan::create([
            'tamu_id'     => $tamu->id,
            'pegawai_id'  => $request->pegawai_id,
            'keperluan'   => $request->keperluan,
            'status'      => 'menunggu',
            'waktu_masuk' => now(),
        ]);

        return redirect()->route('tamu.kunjungan.status')->with('success','Kunjungan berhasil ditambahkan');
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
    public function checkout(Kunjungan $kunjungan)
{
    $user = Auth::user();

    // Pastikan hanya tamu pemilik kunjungan atau frontliner yang bisa checkout
    if ($user->hasRole('tamu') && $kunjungan->tamu_id !== $user->tamu->id) {
        abort(403, 'Unauthorized');
    }

    if ($kunjungan->status === 'sedang_bertamu') {
        $kunjungan->update([
            'status' => 'selesai',
            'waktu_keluar' => now(),
        ]);
    }

    return back()->with('success','Kunjungan berhasil di-checkout');
}

}
