<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Rapat;
use App\Models\Instansi;
use Illuminate\Http\Request;
use App\Models\RapatUndangan;

class RapatCheckinController extends Controller
{
    // Halaman daftar rapat user
    public function index(Request $request)
    {
        $user = $request->user();

        $rapatSaya = Rapat::whereHas('undangan', fn($q) => $q->where('user_id', $user->id))
            ->with(['undangan' => fn($q) => $q->where('user_id', $user->id)->with('user.instansi')])
            ->orderBy('waktu_mulai','desc')
            ->get();

        return view('tamu.rapat.index', compact('rapatSaya'));
    }

    // 🔎 Helper validasi waktu rapat
    private function validateWaktu(Rapat $rapat): string|bool
    {
        if (!$rapat->waktu_mulai || !$rapat->waktu_selesai) return 'Waktu rapat belum ditentukan.';

        $now     = now();
        $mulai   = Carbon::parse($rapat->waktu_mulai);
        $selesai = Carbon::parse($rapat->waktu_selesai);

        if ($now->lt($mulai->copy()->subMinutes(15))) return 'Check-in belum dibuka.';
        if ($rapat->status === 'selesai' || $now->gt($selesai)) return 'Rapat sudah selesai.';
        if ($now->gt($mulai->copy()->addMinutes(30))) return 'Anda terlambat lebih dari 30 menit.';
        return true;
    }

    private function calculateDistance($latUser, $lonUser, $latRapat, $lonRapat): float
    {
        $earth = 6371000; // meter
        $dLat = deg2rad($latRapat - $latUser);
        $dLon = deg2rad($lonRapat - $lonUser);

        $a = sin($dLat/2) ** 2 +
             cos(deg2rad($latUser)) * cos(deg2rad($latRapat)) *
             sin($dLon/2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earth * $c;
    }

    // =========================
    // Pegawai: daftar rapat saya
    // =========================
    public function agendaPegawai(Request $request)
    {
        $user = $request->user();

        $rapatSaya = Rapat::whereHas('undangan', fn($q) => $q->where('user_id', $user->id))
            ->with(['undangan' => fn($q) => $q->where('user_id', $user->id)])
            ->orderBy('waktu_mulai','desc')
            ->get();

        return view('pegawai.rapat.index', compact('rapatSaya'));
    }

    // =========================
    // Pegawai: detail rapat
    // =========================
    public function showPegawai(Rapat $rapat, Request $request)
    {
        $user = $request->user();

        $undangan = RapatUndangan::where('rapat_id', $rapat->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Instansi otomatis DKIS bila kosong
        if (!$user->instansi_id && $user->hasRole('pegawai')) {
            $instansi = Instansi::firstOrCreate(
                ['nama_instansi' => 'DKIS Kota Cirebon'],
                ['lokasi' => 'Jl. DR. Sudarsono No.40, Kesambi, Kota Cirebon']
            );
            $user->instansi_id = $instansi->id;
            $user->save();
            $undangan->update(['instansi_id' => $instansi->id]);
        }

        return view('pegawai.rapat.checkin', compact('rapat','undangan'));
    }

    // =========================
    // Pegawai: check-in via QR rapat
    // =========================
    public function checkinByRapatToken(Request $request, Rapat $rapat, $token)
    {
        if ($rapat->qr_token_hash !== hash('sha256', $token)) {
            return back()->with('error','QR code rapat tidak valid.');
        }

        $user = $request->user();
        $undangan = $rapat->undangan()->where('user_id', $user->id)->first();
        if (!$undangan) {
            return back()->with('error','Anda tidak terdaftar sebagai undangan rapat ini.');
        }

        $validWaktu = $this->validateWaktu($rapat);
        if ($validWaktu !== true) return back()->with('error',$validWaktu);

        $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $buffer   = $user->hasRole('pegawai') ? 20 : 0;
        $distance = $this->calculateDistance(
            $request->latitude, $request->longitude,
            $rapat->latitude, $rapat->longitude
        );

        if ($distance > (($rapat->radius ?? 0) + $buffer)) {
            $km = number_format($distance / 1000, 2, ',', '.');
            $allowed = number_format((($rapat->radius ?? 0) + $buffer) / 1000, 2, ',', '.');
            return back()->with('error',"❌ Anda di luar radius, jarak sekitar {$km} km (radius diizinkan {$allowed} km).");
        }

        $undangan->update([
            'status_kehadiran'  => 'hadir',
            'checked_in_at'     => now(),
            'qr_scanned_at'     => now(),
            'checkin_latitude'  => $request->latitude,
            'checkin_longitude' => $request->longitude,
            'updated_id'        => $user->id,
            'instansi_id'       => $user->instansi_id,
        ]);

        return back()->with('success','Check-in berhasil, status Anda tercatat hadir.');
    }

    // =========================
    // Pegawai: checkout
    // =========================



    // ✅ Checkout tamu
    public function tamuCheckout(Request $request, Rapat $rapat)
    {
        $user = $request->user();
        $undangan = $rapat->undangan()->where('user_id',$user->id)->firstOrFail();

        if ($undangan->status_kehadiran !== 'hadir') {
            return back()->with('error','Anda belum melakukan check-in.');
        }

        $undangan->update([
            'status_kehadiran'=>'selesai',
            'checked_out_at'=>now(),
            'updated_id'=>$user->id,
        ]);

        return redirect()->route('tamu.rapat.saya')->with('success','Checkout berhasil.');
    }

    // ✅ Checkout pegawai
    public function pegawaiCheckout(Request $request, Rapat $rapat)
    {
        $user = $request->user();
        $undangan = $rapat->undangan()->where('user_id',$user->id)->firstOrFail();

        if ($undangan->status_kehadiran !== 'hadir') {
            return back()->with('error','Anda belum melakukan check-in.');
        }

        $undangan->update([
            'status_kehadiran'=>'selesai',
            'checked_out_at'=>now(),
            'updated_id'=>$user->id,
        ]);

        return redirect()->route('pegawai.rapat.index')->with('success','Checkout berhasil.');
    }
}
