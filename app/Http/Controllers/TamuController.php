<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\Kunjungan;
use App\Models\Bidang;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TamuController extends Controller
{
    public function create()
    {
        $bidang  = Bidang::all();
        $user    = Auth::user();

        return view('tamu.form', compact('bidang','user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'instansi'   => 'nullable|string|max:150',
            'no_hp'      => 'nullable|string|max:20',
            'alamat'     => 'nullable|string',
            'keperluan'  => 'required|string|max:255',
            'pegawai_id' => 'required|exists:pegawai,id', // wajib pilih pegawai
        ]);

        $user = Auth::user();

        // Update atau buat data tamu
        $tamu = Tamu::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama'     => $user->name,
                'email'    => $user->email,
                'instansi' => $validated['instansi'] ?? null,
                'no_hp'    => $validated['no_hp'] ?? null,
                'alamat'   => $validated['alamat'] ?? null,
            ]
        );

        // Buat kunjungan baru (tanpa bidang_id)
        Kunjungan::create([
            'tamu_id'     => $tamu->id,
            'pegawai_id'  => $validated['pegawai_id'],
            'keperluan'   => $validated['keperluan'],
            'waktu_masuk' => now(),
            'status'      => 'menunggu',
        ]);

        // Tambahkan role tamu jika user belum punya role apapun
        if ($user && $user->roles()->count() === 0) {
            $user->assignRole('tamu');
        }

        $request->session()->forget('tamu_scanned');

        return redirect()->route('tamu.thanks');
    }

    public function status()
    {
        $user = Auth::user();
        $tamu = Tamu::where('user_id', $user->id)->first();

        $kunjungan = $tamu
            ? Kunjungan::with(['pegawai.user'])
                ->where('tamu_id', $tamu->id)
                ->orderBy('waktu_masuk','desc')
                ->get()
            : collect();

        return view('tamu.status', compact('kunjungan'));
    }

    // Endpoint AJAX untuk filter pegawai berdasarkan bidang
    public function getPegawaiByBidang($bidangId)
    {
        $pegawai = Pegawai::with('user')
            ->where('bidang_id', $bidangId)
            ->get();

        return response()->json($pegawai);
    }
}
