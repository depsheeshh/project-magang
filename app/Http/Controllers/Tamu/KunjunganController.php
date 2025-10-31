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
use App\Models\DaftarSurvey;

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

        // Kirim notifikasi
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
     * Checkout kunjungan.
     */
    public function checkout(Request $request, $id)
    {
        $user = Auth::user();
        $kunjungan = Kunjungan::findOrFail($id);

        // Validasi pemilik kunjungan
        if ($user->hasRole('tamu') && $kunjungan->tamu_id !== $user->tamu->id) {
            return abort(403, 'Unauthorized');
        }

        if ($kunjungan->status !== 'sedang_bertamu') {
            return back()->with('error','Kunjungan tidak dalam status sedang bertamu');
        }

        $kunjungan->update([
            'status'       => 'selesai',
            'waktu_keluar' => now(),
        ]);

        // ✅ Buat record survey kosong + generate link unik
        $survey = Survey::firstOrCreate(
            ['kunjungan_id' => $kunjungan->id, 'user_id' => $user->id],
            [
                'rating'   => null,
                'feedback' => null,
                'link'     => url('/survey/'.$kunjungan->id.'/'.\Illuminate\Support\Str::uuid())
            ]
        );

        // Bersihkan notifikasi pegawai
        $pegawai = $kunjungan->pegawai?->user;
        if ($pegawai) {
            $pegawai->notifications()
                ->whereJsonContains('data->kunjungan_id', $kunjungan->id)
                ->delete();
        }

        // Bersihkan notifikasi frontliner
        $frontliners = User::role('frontliner')->get();
        foreach ($frontliners as $f) {
            $f->notifications()
                ->whereJsonContains('data->kunjungan_id', $kunjungan->id)
                ->delete();
        }

        // Bersihkan notifikasi tamu juga
        $kunjungan->tamu?->user?->notifications()
            ->whereJsonContains('data->kunjungan_id', $kunjungan->id)
            ->delete();

        // ✅ Redirect ke link SKM aktif
        $surveyLink = DaftarSurvey::where('is_active', true)->first();
        if ($surveyLink) {
            return redirect()->away($surveyLink->link_survey);
        }

        // ✅ Fallback: kalau tidak ada SKM aktif, langsung ke survey internal publik
        if ($survey && $survey->link) {
            return redirect()->away($survey->link);
        }

        // Fallback terakhir
        return redirect()->route('tamu.kunjungan.status')
            ->with('info','Checkout berhasil, silakan isi survey pelayanan.');
    }

}
