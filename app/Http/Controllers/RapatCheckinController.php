<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Rapat;
use App\Models\Instansi;
use Illuminate\Http\Request;
use App\Models\RapatUndangan;

class RapatCheckinController extends Controller
{
    // 🔎 Helper validasi waktu rapat
    private function validateWaktu(Rapat $rapat, RapatUndangan $undangan, $user): string|bool
    {
        $now   = now();
        $mulai = Carbon::parse($rapat->waktu_mulai);
        $selesai = Carbon::parse($rapat->waktu_selesai);

        if ($now->lt($mulai->copy()->subMinutes(15))) return 'Check-in belum dibuka.';
        if ($rapat->status === 'selesai' || $now->gt($selesai)) return 'Rapat sudah selesai.';

        // ✅ Telat >30 menit → langsung tandai tidak hadir
        if ($now->gt($mulai->copy()->addMinutes(30))) {
            $undangan->update([
                'status_kehadiran' => 'tidak_hadir',
                'updated_id'       => $user->id,
            ]);
            return 'Anda terlambat lebih dari 30 menit, status dicatat sebagai Tidak Hadir.';
        }
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
        ->first();

        if (!$undangan) {
            return redirect()->route('pegawai.agenda.rapat')
                ->with('warning','Anda tidak diundang dalam rapat ini.');
        }


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
        if ($rapat->qr_token_hash !== hash('sha256',$token)) {
            return back()->with('error','QR code rapat tidak valid.');
        }

        $user = $request->user();
        $undangan = $rapat->undangan()->where('user_id',$user->id)->first();
        if (!$undangan) {
            return redirect()->route('pegawai.agenda.rapat')
                ->with('warning','Anda tidak diundang dalam rapat ini.');
        }

        $validWaktu = $this->validateWaktu($rapat,$undangan,$user);
        if ($validWaktu !== true) return back()->with('error',$validWaktu);

        $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $distance = $this->calculateDistance(
            $request->latitude,$request->longitude,
            $rapat->latitude,$rapat->longitude
        );
        if ($distance > $rapat->radius) {
            $km = number_format($distance/1000,2,',','.');
            return back()->with('error',"Anda di luar radius, jarak sekitar {$km} km.");
        }

        if (in_array($undangan->status_kehadiran,['hadir','selesai'])) {
            return back()->with('warning','Anda sudah melakukan check-in sebelumnya.');
        }

        if (!$request->has('latitude') || !$request->has('longitude')) {
            return redirect()->route('pegawai.rapat.scan')
                ->with('error','Data lokasi tidak lengkap, silakan ulangi scan.');
        }

        $undangan->update([
            'status_kehadiran'=>'hadir',
            'checked_in_at'=>now(),
            'qr_scanned_at'=>now(),
            'checkin_latitude'=>$request->latitude,
            'checkin_longitude'=>$request->longitude,
            'updated_id'=>$user->id,
            'instansi_id'=>$user->instansi_id,
            'method_checkin'=>'qr',          // 👈 audit trail
            'status_survey'=>'belum_isi',
        ]);

        return redirect()->route('pegawai.agenda.rapat')
            ->with('success','Check-in berhasil, status Anda tercatat hadir.');
    }

    // =========================
    // Pegawai: checkout
    // =========================

    // ✅ Checkout pegawai
    public function pegawaiCheckout(Request $request, Rapat $rapat)
    {
        $user = $request->user();
        $undangan = $rapat->undangan()->where('user_id',$user->id)->firstOrFail();

        if ($undangan->status_kehadiran !== 'hadir') {
            return back()->with('error','Anda belum melakukan check-in.');
        }

        if ($rapat->survey) {
            return back()->with('warning','Rapat ini memiliki survey, silakan checkout melalui Scan QR Survey.');
        }

        $undangan->update([
            'status_kehadiran'=>'selesai',
            'checked_out_at'=>now(),
            'updated_id'=>$user->id,
        ]);

        return redirect()->route('pegawai.agenda.rapat')->with('success','Checkout berhasil.');
    }
    public function scanSurveyPage(Rapat $rapat)
    {
        return view('pegawai.rapat.scan-survey', compact('rapat'));
    }

    // ✅ Checkout via scan survey (dipanggil setelah QR valid)
    public function scanSurveyInternal(Rapat $rapat, Request $request)
    {
        $user = $request->user();
        $undangan = $rapat->undangan()->where('user_id',$user->id)->firstOrFail();

        if ($undangan->status_kehadiran === 'hadir') {
            $undangan->update([
                'status_kehadiran' => 'selesai',
                'checked_out_at'   => now(),
                'updated_id'       => $user->id,
                'status_survey'    => 'belum_isi',
            ]);
        } elseif ($undangan->status_kehadiran === 'tidak_hadir') {
            return redirect()->route('pegawai.agenda.rapat')
                ->with('error','Anda tidak tercatat hadir, tidak bisa isi survey.');
        }

        return redirect()->route('pegawai.survey.rapat.form.internal',$rapat->survey->slug)
            ->with('success','Anda otomatis checkout, silakan isi survey rapat.');
    }

}
