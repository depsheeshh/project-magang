<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Rapat;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\RapatUndangan;
use App\Models\RapatUndanganInstansi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class RapatCheckinEksternalController extends Controller
{
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
            return redirect()->route('home')->with('error','QR code rapat tidak valid.');
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

            // cek kuota instansi
            $undanganInstansi = RapatUndanganInstansi::where('rapat_id',$rapat->id)
                ->where('instansi_id',$data['instansi_id'])
                ->first();

            if (!$undanganInstansi) {
                return back()->with('error','Instansi tidak diundang dalam rapat ini.')->withInput();
            }

            if ($undanganInstansi->jumlah_hadir >= $undanganInstansi->kuota) {
                return back()->with('error','Kuota instansi Anda sudah penuh.')->withInput();
            }

            // cari / buat user
            $user = User::where('email', $data['email'])->first();
            if (!$user) {
                $user = User::create([
                    'name'              => $data['nama'],
                    'email'             => $data['email'],
                    'instansi_id'       => $data['instansi_id'],
                    'password'          => Hash::make('Password123!'),
                    'email_verified_at' => now(),
                ]);
                $user->assignRole('tamu');
            } else {
                    $user->update([
                        'name'        => $data['nama'],
                        'instansi_id' => $data['instansi_id'],
                    ]);
                }

            Auth::login($user);

            // cek apakah sudah pernah check-in
            $sudahCheckin = RapatUndangan::where('rapat_id',$rapat->id)
                ->where('user_id',$user->id)
                ->where('status_kehadiran','hadir')
                ->exists();

            if ($sudahCheckin) {
                return back()->with('error','Anda sudah melakukan check-in sebelumnya.');
            }

            // validasi radius
            $distance = $this->calculateDistance(
                $data['latitude'], $data['longitude'],
                $rapat->latitude, $rapat->longitude
            );

            if ($distance > $rapat->radius) {
                $km = number_format($distance/1000,2,',','.');
                return back()->with('error',"Anda di luar radius, jarak sekitar {$km} km.")->withInput();
            }

            // hitung keterlambatan
            $delayMinutes = now()->greaterThan($rapat->waktu_mulai)
                ? now()->diffInMinutes($rapat->waktu_mulai)
                : 0;

            // simpan undangan peserta
            RapatUndangan::create([
                'rapat_id'                   => $rapat->id,
                'rapat_undangan_instansi_id' => $undanganInstansi->id,
                'user_id'                    => $user->id,
                'jabatan'                    => $data['jabatan'],
                'instansi_id'                => $data['instansi_id'],
                'status_kehadiran'           => 'hadir',
                'checked_in_at'              => now(),
                'checkin_latitude'           => $data['latitude'],
                'checkin_longitude'          => $data['longitude'],
                'checkin_distance'           => $distance,
                'delay_minutes'              => $delayMinutes,
            ]);


            return redirect()->route('tamu.rapat.checkin.success')
                ->with('success','Check-in berhasil. Selamat mengikuti rapat.');
        } catch (Exception $e) {
            return back()->with('error','Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
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
                'status_kehadiran'=>'selesai',
                'checked_out_at'=>now(),
                'updated_id'=>$user->id,
            ]);

            return redirect()->route('tamu.rapat.saya')->with('success','Checkout berhasil.');
        } catch (Exception $e) {
            return back()->with('error','Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
