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
        $bidang = Bidang::all();
        $user   = Auth::user();
        $tamu   = Tamu::where('user_id', $user->id)->first();

        return view('tamu.form', compact('bidang','user','tamu'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $tamu = Tamu::where('user_id', $user->id)->first();

        // Validasi
        $rules = [
            'keperluan'  => 'required|string|max:255',
            'pegawai_id' => 'required|exists:pegawai,id',
        ];

        // Kalau tamu belum punya data → wajib isi instansi, no_hp, alamat
        if (!$tamu) {
            $rules['instansi'] = 'required|string|max:150';
            $rules['no_hp']    = 'required|string|max:20';
            $rules['alamat']   = 'required|string';
        }

        $validated = $request->validate($rules);

        // Update atau buat data tamu
        $tamu = Tamu::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama'     => $user->name,
                'email'    => $user->email,
                'instansi' => $validated['instansi'] ?? $tamu->instansi ?? null,
                'no_hp'    => $validated['no_hp'] ?? $tamu->no_hp ?? null,
                'alamat'   => $validated['alamat'] ?? $tamu->alamat ?? null,
            ]
        );

        // Buat kunjungan baru
        Kunjungan::create([
            'tamu_id'     => $tamu->id,
            'pegawai_id'  => $validated['pegawai_id'],
            'keperluan'   => $validated['keperluan'],
            'waktu_masuk' => now(),
            'status'      => 'menunggu',
        ]);

        // Tambahkan role tamu jika user belum punya role
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

    public function getPegawaiByBidang($bidangId)
    {
        $pegawai = Pegawai::with('user')
            ->where('bidang_id', $bidangId)
            ->get();

        return response()->json($pegawai);
    }
}
