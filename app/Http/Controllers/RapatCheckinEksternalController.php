<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Rapat;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RapatUndangan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\CheckinVerificationMail;
use App\Models\RapatUndanganInstansi;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RapatCheckinEksternalController extends Controller
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
    private function validateWaktu(Rapat $rapat): string|bool
    {
        if (!$rapat->waktu_mulai || !$rapat->waktu_selesai) return 'Waktu rapat belum ditentukan.';

        $now     = now();
        $mulai   = Carbon::parse($rapat->waktu_mulai);
        $selesai = Carbon::parse($rapat->waktu_selesai);

        if ($now->lt($mulai->copy()->subMinutes(15))) return 'Check-in belum dibuka.';
        if ($rapat->status === 'selesai' || $now->gt($selesai)) return 'Rapat sudah selesai.';
        return true;
    }

    private function calculateDistance($latUser, $lonUser, $latRapat, $lonRapat): float
    {
        $earth = 6371000;
        $dLat = deg2rad($latRapat - $latUser);
        $dLon = deg2rad($lonRapat - $lonUser);

        $a = sin($dLat/2) ** 2 +
             cos(deg2rad($latUser)) * cos(deg2rad($latRapat)) *
             sin($dLon/2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earth * $c;
    }

    public function showForm(Rapat $rapat, $token)
    {
        if ($rapat->qr_token_hash !== hash('sha256', $token)) {
            return redirect()->route('tamu.rapat.checkin.failed')
                ->with('error','QR code rapat tidak valid.');
        }

        $instansiList = $rapat->undanganInstansi()->with('instansi')->get();

        return view('tamu.rapat.form', compact('rapat','token','instansiList'));
    }

    public function checkin(Request $request, Rapat $rapat, $token)
    {
        try {
            if ($rapat->qr_token_hash !== hash('sha256', $token)) {
                return back()->with('error','QR code rapat tidak valid.')->withInput();
            }

            $validWaktu = $this->validateWaktu($rapat);
            if ($validWaktu !== true) {
                return back()->with('error',$validWaktu)->withInput();
            }

            $data = $request->validate([
                'nama'        => 'required|string|max:255',
                'email'       => 'required|email',
                'instansi_id' => 'required|exists:instansi,id',
                'jabatan'     => 'required|string|max:255',
                'latitude'    => 'required|numeric|between:-90,90',
                'longitude'   => 'required|numeric|between:-180,180',
            ]);

            $undanganInstansi = RapatUndanganInstansi::where('rapat_id',$rapat->id)
                ->where('instansi_id',$data['instansi_id'])
                ->first();

            if (!$undanganInstansi) {
                return back()->with('error','Instansi tidak diundang dalam rapat ini.')->withInput();
            }

            if ($undanganInstansi->jumlah_hadir >= $undanganInstansi->kuota) {
                return back()->with('error','Kuota instansi Anda sudah penuh.')->withInput();
            }

            $user = User::where('email', $data['email'])->first();
            if (!$user) {
                $user = User::create([
                    'name'              => $data['nama'],
                    'email'             => $data['email'],
                    'instansi_id'       => $data['instansi_id'],
                    'password'          => Hash::make('Password123!'), // tetap default
                    'email_verified_at' => now(),
                ]);
                $user->assignRole('tamu');
            } else {
                $user->update([
                    'name'        => $data['nama'],
                    'instansi_id' => $data['instansi_id'],
                ]);
                $user->assignRole('tamu');
            }

            Auth::login($user);

            $sudahCheckin = RapatUndangan::where('rapat_id',$rapat->id)
                ->where('user_id',$user->id)
                ->where('status_kehadiran','hadir')
                ->exists();

            if ($sudahCheckin) {
                return back()->with('error','Anda sudah melakukan check-in sebelumnya.');
            }

            $distance = $this->calculateDistance(
                $data['latitude'], $data['longitude'],
                $rapat->latitude, $rapat->longitude
            );

            if ($distance > $rapat->radius) {
                $km = number_format($distance/1000,2,',','.');
                return back()->with('error',"Anda di luar radius, jarak sekitar {$km} km.")->withInput();
            }

            $delayMinutes = now()->greaterThan($rapat->waktu_mulai)
                ? now()->diffInMinutes($rapat->waktu_mulai)
                : 0;

            $tokenVerif = Str::random(64);

            $undangan = RapatUndangan::create([
                'rapat_id'                   => $rapat->id,
                'rapat_undangan_instansi_id' => $undanganInstansi->id,
                'user_id'                    => $user->id,
                'jabatan'                    => $data['jabatan'],
                'instansi_id'                => $data['instansi_id'],
                'email'                      => $data['email'],
                'status_kehadiran'           => 'pending',
                'checkin_token_hash'         => hash('sha256',$tokenVerif),
                'checkin_latitude'           => $data['latitude'],
                'checkin_longitude'          => $data['longitude'],
                'checkin_distance'           => $distance,
                'keterlambatan_menit'        => $delayMinutes,
                'created_id'                 => $user->id,
            ]);

            Mail::to($data['email'])->send(new CheckinVerificationMail($rapat, $undangan, $tokenVerif));

            return redirect()->route('tamu.rapat.checkin.pending')
                ->with('success','Check-in berhasil disubmit. Silakan verifikasi melalui email untuk menyelesaikan check-in.');
        } catch (Exception $e) {
            return back()->with('error','Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }


    public function verifyCheckin(Request $request, $rapatId)
    {
        Log::info('VerifyCheckin invoked', [
            'rapat_param' => $rapatId,
            'token_query' => $request->query('token'),
            'full_url'    => $request->fullUrl(),
        ]);

        // Cari rapat manual (hindari gagal binding)
        $rapat = Rapat::find($rapatId);
        if (!$rapat) {
            Log::warning('VerifyCheckin: Rapat not found', ['rapat_param' => $rapatId]);
            return redirect()->route('tamu.rapat.checkin.failed')
                ->with('error','Rapat tidak ditemukan.');
        }

        // Ambil token dari query
        $token = (string) $request->query('token', '');
        if ($token === '') {
            Log::warning('VerifyCheckin: Empty token', ['rapat_id' => $rapat->id]);
            return redirect()->route('tamu.rapat.checkin.failed')
                ->with('error','Token verifikasi tidak ditemukan.');
        }

        $tokenHash = hash('sha256', $token);

        // Cari undangan berdasarkan rapat + token
        $undangan = RapatUndangan::where('rapat_id', $rapat->id)
            ->where('checkin_token_hash', $tokenHash)
            ->first();

        if (!$undangan) {
            Log::warning('VerifyCheckin: Undangan not found by token', [
                'rapat_id'   => $rapat->id,
                'token_hash' => $tokenHash,
            ]);
            return redirect()->route('tamu.rapat.checkin.failed')
                ->with('error','Link verifikasi tidak valid atau sudah digunakan.');
        }

        // Cek apakah sudah diverifikasi sebelumnya
        if ($undangan->checkin_verified_at) {
            Log::info('VerifyCheckin: Token already used', [
                'undangan_id' => $undangan->id,
                'rapat_id'    => $rapat->id,
            ]);
            return redirect()->route('tamu.rapat.checkin.failed')
                ->with('error','Link verifikasi sudah pernah digunakan.');
        }

        // Update status kehadiran
        $undangan->update([
            'status_kehadiran'    => 'hadir',
            'checked_in_at'       => now(),
            'checkin_verified_at' => now(),
            'updated_id'          => optional(Auth::user())->id,
        ]);

        Log::info('VerifyCheckin: Success', [
            'undangan_id' => $undangan->id,
            'rapat_id'    => $rapat->id,
            'user_id'     => $undangan->user_id,
        ]);

        return redirect()->route('tamu.rapat.checkin.success')
            ->with('success','Check-in berhasil diverifikasi. Selamat mengikuti rapat.');
    }

    public function checkout(Request $request, Rapat $rapat)
    {
        try {
            $user = $request->user();
            $undangan = $rapat->undangan()->where('user_id',$user->id)->first();

            if (!$undangan) {
                return back()->with('error','Data kehadiran tidak ditemukan.');
            }

            if ($undangan->status_kehadiran !== 'hadir') {
                return back()->with('error','Anda belum melakukan check-in.');
            }

            $undangan->update([
                'status_kehadiran' => 'selesai',
                'checked_out_at'   => now(),
                'updated_id'       => $user->id,
            ]);

            return redirect()->route('tamu.rapat.saya')
                ->with('success','Checkout berhasil.');
        } catch (Exception $e) {
            return back()->with('error','Terjadi kesalahan: '.$e->getMessage());
        }
    }
    public function show(Rapat $rapat)
    {
        $user = Auth::user();

        // Ambil undangan untuk user tamu ini
    $undangan = $rapat->undangan()
        ->where('user_id', $user->id)
        ->with('user.instansi')
        ->first();

    if (!$undangan) {
        return redirect()->route('tamu.rapat.saya')
            ->with('error','Data undangan rapat tidak ditemukan.');
    }

    // Ambil daftar instansi undangan rapat eksternal (opsional untuk ditampilkan)
    $instansiList = $rapat->undanganInstansi()->with('instansi')->get();

    // Validasi waktu rapat (opsional, bisa ditampilkan di view)
    $validWaktu = $this->validateWaktu($rapat);

    return view('tamu.rapat.checkin', compact('rapat','undangan','instansiList','validWaktu'));
    }


}
