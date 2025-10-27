<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Rapat;
use App\Models\Instansi;
use Illuminate\Http\Request;
use App\Models\RapatUndangan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Notifications\RapatInvitationNotification;

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

    private function isWithinRadius($latUser, $lonUser, $latRapat, $lonRapat, $radiusMeters): bool
    {
        $earth = 6371000; // meter
        $dLat = deg2rad($latRapat - $latUser);
        $dLon = deg2rad($lonRapat - $lonUser);

        $latUserRad  = deg2rad($latUser);
        $latRapatRad = deg2rad($latRapat);

        $a = sin($dLat/2) ** 2 +
            cos($latUserRad) * cos($latRapatRad) *
            sin($dLon/2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earth * $c;

        Log::info('Check-in distance calculation', [
            'user_lat' => $latUser,
            'user_lon' => $lonUser,
            'rapat_lat' => $latRapat,
            'rapat_lon' => $lonRapat,
            'distance_meters' => round($distance, 2),
            'radius_allowed' => $radiusMeters,
            'within_radius' => $distance <= $radiusMeters,
        ]);

        return $distance <= $radiusMeters;
    }

    // ✅ Check-in manual via tombol
    public function checkin(Request $request, Rapat $rapat)
    {
        $user = $request->user();
        $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if (!$user->instansi_id) {
            return redirect()->route('tamu.rapat.checkin.form', $rapat->id)
                ->with('error', 'Anda harus mengisi instansi terlebih dahulu sebelum check-in.');
        }

        $undangan = RapatUndangan::where('rapat_id', $rapat->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (!$rapat->waktu_mulai || !$rapat->waktu_selesai) {
            return back()->with('error','Waktu rapat belum ditentukan.');
        }

        $now    = now();
        $mulai  = Carbon::parse($rapat->waktu_mulai)->subMinutes(15);
        $selesai= Carbon::parse($rapat->waktu_selesai)->addMinutes(30);

        // Validasi waktu
        if ($now->lt($mulai)) {
            return back()->with('error','Check-in belum dibuka.');
        }
        if ($now->gt($selesai)) {
            $undangan->update(['status_kehadiran' => 'tidak_hadir','updated_id' => $user->id]);
            return back()->with('error','Check-in sudah ditutup, status dicatat sebagai Tidak Hadir.');
        }

        // Validasi lokasi rapat (geo-fencing)
        if ($rapat->latitude && $rapat->longitude && $rapat->radius) {
            if (! $this->isWithinRadius(
                $request->latitude, $request->longitude,
                $rapat->latitude, $rapat->longitude,
                $rapat->radius
            )) {
                $undangan->update([
                    'status_kehadiran'   => 'tidak_hadir',
                    'updated_id'         => $user->id,
                    'checkin_latitude'   => $request->latitude,
                    'checkin_longitude'  => $request->longitude,
                ]);
                return back()->with('error', 'Lokasi Anda di luar radius, status dicatat sebagai Tidak Hadir.');
            }
        }

        // ✅ Update undangan
        $undangan->update([
            'status_kehadiran'   => 'hadir',
            'checked_in_at'      => $now,
            'checkin_latitude'   => $request->latitude,
            'checkin_longitude'  => $request->longitude,
            'updated_id'         => $user->id,
            'instansi_id'        => $user->instansi_id,
            'checkin_token_hash' => null,
        ]);

        Log::info('Check-in success', ['rapat_id'=>$rapat->id,'user_id'=>$user->id]);

        return redirect()->route('tamu.rapat.saya')
            ->with('success','Check-in berhasil dan lokasi valid.');
    }

    // ✅ Check-in via QR token
    public function checkinByToken(Request $request, $token)
    {
        $hash = hash('sha256', $token);
        $undangan = RapatUndangan::where('checkin_token_hash', $hash)->first();

        if (!$undangan) {
            return view('tamu.rapat.checkin_result', [
                'status'=>'error',
                'message'=>'QR code tidak valid atau sudah digunakan.',
                'rapat'=>null
            ]);
        }

        $rapat = $undangan->rapat;
        $user  = $request->user();

        if ($undangan->user_id !== $user->id) {
            abort(403, 'QR ini bukan milik Anda.');
        }

        if (!$rapat->waktu_mulai || !$rapat->waktu_selesai) {
            return view('tamu.rapat.checkin_result', [
                'status'=>'error',
                'message'=>'Waktu rapat belum ditentukan.',
                'rapat'=>$rapat
            ]);
        }

        $now    = now();
        $mulai  = Carbon::parse($rapat->waktu_mulai)->subMinutes(15);
        $selesai= Carbon::parse($rapat->waktu_selesai)->addMinutes(30);

        if ($now->lt($mulai)) {
            return view('tamu.rapat.checkin_result', [
                'status'=>'error',
                'message'=>'Check-in belum dibuka.',
                'rapat'=>$rapat
            ]);
        }
        if ($now->gt($selesai)) {
            $undangan->update(['status_kehadiran'=>'tidak_hadir','updated_id'=>$user->id]);
            return view('tamu.rapat.checkin_result', [
                'status'=>'error',
                'message'=>'Check-in sudah ditutup, status dicatat sebagai Tidak Hadir.',
                'rapat'=>$rapat
            ]);
        }

        // Lokasi wajib kalau rapat punya geo-fencing
        if ($rapat->latitude && $rapat->longitude && $rapat->radius) {
            $request->validate([
                'latitude'  => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
            ]);
            if (! $this->isWithinRadius(
                $request->latitude, $request->longitude,
                $rapat->latitude, $rapat->longitude,
                $rapat->radius
            )) {
                $undangan->update([
                    'status_kehadiran'=>'tidak_hadir',
                    'updated_id'=>$user->id,
                    'checkin_latitude'=>$request->latitude,
                    'checkin_longitude'=>$request->longitude,
                ]);
                return view('tamu.rapat.checkin_result', [
                    'status'=>'error',
                    'message'=>'Lokasi Anda di luar radius, status dicatat sebagai Tidak Hadir.',
                    'rapat'=>$rapat
                ]);
            }
        }

        $undangan->update([
            'status_kehadiran'=>'hadir',
            'checked_in_at'=>$now,
            'qr_scanned_at'=>$now,
            'updated_id'=>$user->id,
            'instansi_id'=>$user->instansi_id,
            'checkin_token_hash'=>null
        ]);

        Log::info('Check-in success via QR', ['rapat_id'=>$rapat->id,'user_id'=>$user->id]);

        return view('tamu.rapat.checkin_result', [
            'status'=>'success',
            'message'=>'Check-in berhasil, status Anda tercatat hadir.',
            'rapat'=>$rapat
        ]);
    }


    // Detail rapat untuk tamu
    public function show(Rapat $rapat, Request $request)
    {
        $user = $request->user();

        // Ambil undangan user untuk rapat ini
        $undangan = RapatUndangan::where('rapat_id', $rapat->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Kalau user belum punya instansi, arahkan ke form isi instansi
        if (!$user->instansi_id) {
            return redirect()->route('tamu.rapat.checkin.form', $rapat->id)
                ->with('error', 'Anda harus mengisi instansi terlebih dahulu sebelum melihat detail rapat.');
        }

        // Kirim rapat + undangan ke view checkin.blade
        return view('tamu.rapat.checkin', compact('rapat', 'undangan'));
    }


    public function checkinForm(Rapat $rapat, Request $request)
    {
        $user = $request->user();

        $undangan = RapatUndangan::where('rapat_id', $rapat->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (!$user->instansi_id) {
            return view('tamu.rapat.fill_instansi', compact('rapat', 'undangan'));
        }

        return view('tamu.rapat.checkin', compact('rapat', 'undangan'));
    }

    public function storeInstansi(Request $request)
    {
        $request->validate([
            'rapat_id'          => 'required|exists:rapat,id',
            'mode'              => 'required|in:select,manual',
            'instansi_admin_id' => 'nullable|exists:instansi,id',
            'nama_instansi'     => 'nullable|string|max:255',
            'lokasi'            => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        $instansi = null;

        if ($request->mode === 'select') {
            // ✅ Pilih instansi dari admin
            $instansi = Instansi::findOrFail($request->instansi_admin_id);

        } else {
            // ✅ Isi manual: wajib nama_instansi
            if (!$request->filled('nama_instansi')) {
                return back()->withErrors(['nama_instansi' => 'Nama instansi wajib diisi jika mode manual.']);
            }

            $instansi = Instansi::firstOrCreate(
                ['nama_instansi' => $request->nama_instansi],
                [
                    'lokasi'     => $request->lokasi,
                    'created_id' => $user->id, // dibuat oleh peserta
                ]
            );
        }

        // ✅ Set instansi ke user
        $user->instansi_id = $instansi->id;
        $user->save();

        // ✅ Update undangan rapat agar konsisten
        RapatUndangan::where('rapat_id', $request->rapat_id)
            ->where('user_id', $user->id)
            ->update(['instansi_id' => $instansi->id]);

        return redirect()->route('tamu.rapat.checkin.form', $request->rapat_id)
            ->with('success', 'Instansi berhasil disimpan, silakan lanjut check-in.');
    }

    public function updateInstansi(Request $request)
    {
        $request->validate([
            'rapat_id'          => 'required|exists:rapat,id',
            'mode'              => 'required|in:select,manual',
            'instansi_admin_id' => 'nullable|exists:instansi,id',
            'nama_instansi'     => 'nullable|string|max:255',
            'lokasi'            => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        $instansi = null;

        if ($request->mode === 'select') {
            $instansi = Instansi::findOrFail($request->instansi_admin_id);
        } else {
            if (!$request->filled('nama_instansi')) {
                return back()->withErrors(['nama_instansi' => 'Nama instansi wajib diisi jika mode manual.']);
            }

            $instansi = Instansi::firstOrCreate(
                ['nama_instansi' => $request->nama_instansi],
                [
                    'lokasi'     => $request->lokasi,
                    'created_id' => $user->id,
                ]
            );
        }

        // Update instansi user
        $user->instansi_id = $instansi->id;
        $user->save();

        // Update undangan rapat
        RapatUndangan::where('rapat_id', $request->rapat_id)
            ->where('user_id', $user->id)
            ->update(['instansi_id' => $instansi->id]);

        return redirect()->route('tamu.rapat.saya')
            ->with('success', 'Instansi berhasil diperbarui.');
    }

}
