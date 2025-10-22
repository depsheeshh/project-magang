<?php

namespace App\Http\Controllers;

use App\Models\Rapat;
use App\Models\RapatUndangan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RapatCheckinController extends Controller
{
    // Halaman daftar rapat user
    public function index(Request $request)
    {
        $user = $request->user();

        $rapatSaya = Rapat::whereHas('undangan', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with(['undangan' => function($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->orderBy('waktu_mulai','desc')
            ->get();

        return view('tamu.rapat.index', compact('rapatSaya'));
    }

    private function isWithinRadius($lat1, $lon1, $lat2, $lon2, $radiusMeters): bool
    {
        $earth = 6371000; // meter
        $dLat  = deg2rad($lat2 - $lat1);
        $dLon  = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon/2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earth * $c;

        return $distance <= $radiusMeters;
    }

    // Check-in manual via tombol
    public function checkin(Request $request, Rapat $rapat)
    {
        $user = $request->user();
        $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $undangan = RapatUndangan::where('rapat_id', $rapat->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $now = Carbon::now();
        $mulai = Carbon::parse($rapat->waktu_mulai)->subMinutes(15);
        $selesai = Carbon::parse($rapat->waktu_selesai)->addMinutes(30);

        if (! $now->between($mulai, $selesai)) {
            return back()->with('error','Check-in hanya H-15 s/d H+30 dari jadwal rapat.');
        }

        // Validasi lokasi rapat (geo-fencing)
        if ($rapat->latitude && $rapat->longitude && $rapat->radius) {
            if (! $this->isWithinRadius(
                $request->latitude, $request->longitude,
                $rapat->latitude, $rapat->longitude,
                $rapat->radius
            )) {
                return view('tamu.rapat.checkin_result', [
                    'status'  => 'error',
                    'message' => 'Lokasi Anda di luar radius check-in rapat.',
                    'rapat'   => $rapat,
                ]);
            }
        }

        $undangan->update([
            'status_kehadiran' => 'hadir',
            'checked_in_at'    => $now,
            'checkin_latitude' => $request->latitude,
            'checkin_longitude'=> $request->longitude,
            'updated_id'       => $user->id,
        ]);

        return back()->with('success','Check-in berhasil dan lokasi valid.');
    }

    // Check-in via QR token
    public function checkinByToken(Request $request, $token)
    {
        $hash = hash('sha256', $token);

        $undangan = RapatUndangan::where('checkin_token_hash', $hash)->firstOrFail();
        $rapat    = $undangan->rapat;
        $user     = $request->user();

        if ($undangan->user_id !== $user->id) {
            abort(403, 'QR ini bukan milik Anda.');
        }

        $now     = now();
        $mulai   = Carbon::parse($rapat->waktu_mulai)->subMinutes(15);
        $selesai = Carbon::parse($rapat->waktu_selesai)->addMinutes(30);

        if (! $now->between($mulai, $selesai)) {
            return view('tamu.rapat.checkin_result', [
                'status'  => 'error',
                'message' => 'Check-in hanya bisa dilakukan H-15 sampai H+30 dari jadwal rapat.',
                'rapat'   => $rapat,
            ]);
        }

        // Validasi lokasi (opsional, jika user kirim lat/long)
        if ($rapat->latitude && $rapat->longitude && $rapat->radius) {
            $request->validate([
                'latitude'  => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
            ]);

            if ($request->filled(['latitude','longitude'])) {
                if (! $this->isWithinRadius(
                    $request->latitude, $request->longitude,
                    $rapat->latitude, $rapat->longitude,
                    $rapat->radius
                )) {
                    return view('tamu.rapat.checkin_result', [
                        'status'  => 'error',
                        'message' => 'Lokasi Anda di luar radius check-in rapat.',
                        'rapat'   => $rapat,
                    ]);
                }
            }
        }

        $undangan->update([
            'status_kehadiran' => 'hadir',
            'checked_in_at'    => $now,
            'qr_scanned_at'    => $now,
            'updated_id'       => $user->id,
        ]);

        return view('tamu.rapat.checkin_result', [
            'status'  => 'success',
            'message' => 'Check-in berhasil, status Anda tercatat hadir.',
            'rapat'   => $rapat,
        ]);
    }
}
