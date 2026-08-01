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
            ->with(['undangan' => fn($q) => $q->where('user_id', $user->id)->with('user.instansi'), 'survey'
            ])
            ->orderBy('waktu_mulai','desc')
            ->get();

        return view('tamu.rapat.index', compact('rapatSaya'));
    }
    private function validateWaktu(Rapat $rapat): string|bool
    {
        if (!$rapat->waktu_mulai || !$rapat->waktu_selesai) {
            return 'Waktu rapat belum ditentukan.';
        }

        $now     = now();
        $mulai   = Carbon::parse($rapat->waktu_mulai);
        $selesai = Carbon::parse($rapat->waktu_selesai);

        if ($now->lt($mulai->copy()->subMinutes(15))) {
            return 'Check-in belum dibuka.';
        }

        if ($rapat->status === 'selesai' || $now->gt($selesai)) {
            return 'Rapat sudah selesai.';
        }

        return true;
    }

    // ✅ Helper untuk validasi instansi (return string|bool)
    private function checkInstansi(Rapat $rapat, User $user): string|bool
    {
        $instansiId = $user->instansi_id;

        $undanganInstansi = $rapat->undanganInstansi()
            ->where('instansi_id', $instansiId)
            ->first();

        if (!$undanganInstansi) {
            return 'Instansi Anda tidak diundang dalam rapat ini.';
        }
        if ($undanganInstansi->jumlah_hadir >= $undanganInstansi->kuota) {
            return 'Kuota instansi Anda sudah penuh.';
        }
        return true;
    }

    // ✅ Endpoint JSON untuk scan QR
    public function validateInstansi(Rapat $rapat)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole('tamu')) {
            return response()->json(['success'=>false,'message'=>'Anda harus login sebagai tamu.'],403);
        }

        $result = $this->checkInstansi($rapat, $user);
        if ($result !== true) {
            return response()->json(['success'=>false,'message'=>$result],403);
        }

        return response()->json(['success'=>true,'message'=>'Instansi diundang, silakan lanjut check-in.']);
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

    // Jika tamu sudah login
    if (Auth::check() && Auth::user()->hasRole('tamu')) {
        $user = Auth::user();

        // 🚨 Kalau user belum punya instansi_id → langsung tampilkan form isi peserta
        if (!$user->instansi_id) {
            return view('tamu.rapat.form', compact('rapat','token','instansiList'));
        }

        // 🚨 Kalau user sudah punya instansi_id → baru validasi
        $result = $this->checkInstansi($rapat, $user);
        if ($result !== true) {
            return redirect()->route('tamu.rapat.checkin.failed')->with('error',$result);
        }

        // Kalau lolos validasi → tampilkan form
        return view('tamu.rapat.form', compact('rapat','token','instansiList'));
    }

    // Default: tampilkan form publik
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

            // 🔎 cek apakah user sudah ada
            $user = User::where('email', $data['email'])->first();
            if (!$user) {
                // user baru → buat akun
                $user = User::create([
                    'name'              => $data['nama'],
                    'email'             => $data['email'],
                    'instansi_id'       => $data['instansi_id'], // hanya sekali di awal
                    'password'          => Hash::make('Password123!'),
                    'email_verified_at' => null,
                ]);
                $user->assignRole('tamu');
            } else {
                // user lama → update data dasar (tanpa paksa instansi berubah)
                $updateData = [
                    'name' => $data['nama'],
                ];

                // 🚨 kalau instansi_id masih null → isi dari form
                if (!$user->instansi_id) {
                    $updateData['instansi_id'] = $data['instansi_id'];
                }

                $user->update($updateData);
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

            // 🔑 kalau user sudah diverifikasi → langsung hadir
            if ($user->email_verified_at) {
                RapatUndangan::updateOrCreate(
                    ['rapat_id' => $rapat->id, 'user_id' => $user->id],
                    [
                        'rapat_undangan_instansi_id' => $undanganInstansi->id,
                        'jabatan'          => $data['jabatan'],
                        'instansi_id'      => $data['instansi_id'], // 🚨 selalu pakai dari form
                        'email'            => $data['email'],
                        'status_kehadiran' => 'hadir',
                        'checked_in_at'    => now(),
                        'checkin_latitude' => $data['latitude'],
                        'checkin_longitude'=> $data['longitude'],
                        'checkin_distance' => $distance,
                        'keterlambatan_menit' => $delayMinutes,
                        'created_id'       => $user->id,
                        'method_checkin'   => 'qr',
                        'status_survey'    => 'belum_isi',
                    ]
                );

                return redirect()->route('tamu.rapat.saya')
                    ->with('success','Check-in berhasil, status Anda tercatat hadir.');
            }

            // 🔑 kalau user baru / belum diverifikasi → flow pending + email verifikasi
            $tokenVerif = Str::random(64);
            $undangan = RapatUndangan::create([
                'rapat_id'                   => $rapat->id,
                'rapat_undangan_instansi_id' => $undanganInstansi->id,
                'user_id'                    => $user->id,
                'jabatan'                    => $data['jabatan'],
                'instansi_id'                => $data['instansi_id'],
                'status_kehadiran'           => 'pending',
                'checkin_token_hash'         => hash('sha256',$tokenVerif),
                'checkin_latitude'           => $data['latitude'],
                'checkin_longitude'          => $data['longitude'],
                'checkin_distance'           => $distance,
                'keterlambatan_menit'        => $delayMinutes,
                'created_id'                 => $user->id,
            ]);

            try {
                Mail::to($data['email'])->send(new CheckinVerificationMail($rapat, $undangan, $tokenVerif));
                Log::info('CheckinVerificationMail sent', [
                    'undangan_id' => $undangan->id,
                    'email'       => $data['email'],
                    'rapat_id'    => $rapat->id,
                ]);
            } catch (Exception $mailError) {
                Log::error('CheckinVerificationMail FAILED', [
                    'undangan_id' => $undangan->id,
                    'email'       => $data['email'],
                    'error'       => $mailError->getMessage(),
                ]);
                // Tetap lanjutkan ke halaman pending, user bisa resend manual
            }

            return redirect()->route('tamu.rapat.checkin.pending', [
                    'rapat_id'    => $rapat->id,
                    'undangan_id' => $undangan->id,
                    'rapat_name'  => $rapat->judul,
                ])
                ->with('email', $data['email']);
        } catch (Exception $e) {
            Log::error('Checkin error', ['error' => $e->getMessage()]);
            return back()->with('error','Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }

    // ✅ Resend email verifikasi check-in
    public function resendVerificationEmail(Request $request)
    {
        $rapatId    = $request->input('rapat_id');
        $undanganId = $request->input('undangan_id');

        $undangan = RapatUndangan::where('id', $undanganId)
            ->where('rapat_id', $rapatId)
            ->where('status_kehadiran', 'pending')
            ->whereNull('checkin_verified_at')
            ->first();

        if (!$undangan) {
            return back()->with('error', 'Data check-in tidak ditemukan atau sudah diverifikasi.');
        }

        $rapat = Rapat::find($rapatId);
        $user  = User::find($undangan->user_id);

        if (!$rapat || !$user) {
            return back()->with('error', 'Data rapat atau user tidak ditemukan.');
        }

        // Generate token baru
        $newToken = Str::random(64);
        $undangan->update(['checkin_token_hash' => hash('sha256', $newToken)]);

        try {
            Mail::to($user->email)->send(new CheckinVerificationMail($rapat, $undangan, $newToken));
            Log::info('CheckinVerificationMail resent', [
                'undangan_id' => $undangan->id,
                'email'       => $user->email,
            ]);
            return back()->with('success', 'Email verifikasi berhasil dikirim ulang ke ' . $user->email . '. Silakan cek inbox dan folder Spam.');
        } catch (Exception $e) {
            Log::error('Resend CheckinVerificationMail FAILED', [
                'undangan_id' => $undangan->id,
                'error'       => $e->getMessage(),
            ]);
            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
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
            'method_checkin'      => 'qr',          // 👈 audit trail
            'status_survey'       => 'belum_isi',
        ]);

        // ✅ Update email_verified_at pada user agar status verifikasi email tercatat
        $user = User::find($undangan->user_id);
        if ($user && !$user->email_verified_at) {
            $user->update(['email_verified_at' => now()]);
            Log::info('VerifyCheckin: email_verified_at updated', ['user_id' => $user->id]);
        }

        // ✅ Auto-login user agar bisa redirect ke halaman sukses yang butuh auth
        if (!Auth::check() && $user) {
            Auth::login($user);
        }

        Log::info('VerifyCheckin: Success', [
            'undangan_id'  => $undangan->id,
            'rapat_id'     => $rapat->id,
            'user_id'      => $undangan->user_id,
            'status_survey'=> 'belum_isi',
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
                'status_survey'    => 'belum_isi',
            ]);

            // 🚨 Logika baru: langsung arahkan ke form survey eksternal
            if ($rapat->survey && $rapat->survey->tipe === 'Eksternal') {
                return redirect()->route('tamu.survey.rapat.form.eksternal', $rapat->survey->slug)
                    ->with('success','Checkout berhasil, silakan isi survey rapat eksternal.');
            }

            return redirect()->route('tamu.rapat.saya')->with('success','Checkout berhasil.');
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

    public function formDashboard(Rapat $rapat)
    {
        $user = Auth::user();
        $instansiList = $rapat->undanganInstansi()->with('instansi')->get();

        return view('tamu.rapat.checkin-dashboard', compact('rapat','user','instansiList'));
    }

    // ✅ Submit dashboard (redirect, bukan JSON)
    public function submitDashboard(Request $request, Rapat $rapat)
{
    $user = Auth::user();

    $data = $request->validate([
        'jabatan'     => 'nullable|string|max:255',
        'latitude'    => 'required|numeric|between:-90,90',
        'longitude'   => 'required|numeric|between:-180,180',
        'instansi_id' => 'required|exists:instansi,id',
    ]);

    // ✅ Update akun tamu jika instansi_id masih null
    if (!$user->instansi_id) {
        $user->update(['instansi_id' => $data['instansi_id']]);
    }

    // ✅ Validasi instansi pakai data form, bukan akun
    $result = $this->checkInstansi($rapat, (object)['instansi_id' => $data['instansi_id']]);
    if ($result !== true) {
        return back()->with('error',$result);
    }

    $validWaktu = $this->validateWaktu($rapat);
    if ($validWaktu !== true) {
        return back()->with('error',$validWaktu);
    }

    $sudahCheckin = RapatUndangan::where('rapat_id',$rapat->id)
        ->where('user_id',$user->id)
        ->where('status_kehadiran','hadir')
        ->exists();
    if ($sudahCheckin) {
        return redirect()->route('tamu.rapat.saya')
            ->with('warning','Anda sudah melakukan check-in sebelumnya.');
    }

    $distance = $this->calculateDistance(
        $data['latitude'], $data['longitude'],
        $rapat->latitude, $rapat->longitude
    );
    if ($distance > $rapat->radius) {
        $km = number_format($distance/1000,2,',','.');
        return back()->with('error',"Anda di luar radius, jarak sekitar {$km} km.");
    }

    $delayMinutes = now()->greaterThan($rapat->waktu_mulai)
        ? now()->diffInMinutes($rapat->waktu_mulai)
        : 0;

    RapatUndangan::updateOrCreate(
        ['rapat_id' => $rapat->id, 'user_id' => $user->id],
        [
            'jabatan'          => $data['jabatan'],
            'instansi_id'      => $data['instansi_id'],
            'status_kehadiran' => 'hadir',
            'checked_in_at'    => now(),
            'checkin_latitude' => $data['latitude'],
            'checkin_longitude'=> $data['longitude'],
            'checkin_distance' => $distance,
            'keterlambatan_menit' => $delayMinutes,
        ]
    );

    return redirect()->route('tamu.rapat.saya')
        ->with('success','Check-in berhasil via dashboard.');
}


    public function scanSurveyPage(Rapat $rapat)
    {
        return view('tamu.rapat.scan-survey', compact('rapat'));
    }

    // Proses checkout via QR survey eksternal (slug dari query), lalu redirect ke form
    public function scanSurveyEksternal(Rapat $rapat, Request $request)
    {
        $slug = $request->query('slug');
        if (!$slug) {
            return redirect()->route('tamu.rapat.saya')->with('error', 'Slug survey tidak ditemukan.');
        }

        if (!$rapat->survey || $rapat->survey->slug !== $slug || $rapat->survey->tipe !== 'Eksternal') {
            return redirect()->route('tamu.rapat.saya')->with('error', 'Survey eksternal tidak valid untuk rapat ini.');
        }

        $user = $request->user();
        $undangan = $rapat->undangan()->where('user_id', $user->id)->first();

        if (!$undangan) {
            return redirect()->route('tamu.rapat.saya')->with('error','Data kehadiran tidak ditemukan.');
        }

        if ($undangan->status_kehadiran === 'tidak_hadir') {
            return redirect()->route('tamu.rapat.saya')->with('error','Anda tidak tercatat hadir, tidak bisa isi survey.');
        }

        // Jika masih "hadir", lakukan auto-checkout dan tandai belum isi survey
        if ($undangan->status_kehadiran === 'hadir') {
            $undangan->update([
                'status_kehadiran' => 'selesai',
                'checked_out_at'   => now(),
                'updated_id'       => $user->id,
                'status_survey'    => 'belum_isi',
            ]);
        }

        return redirect()->route('tamu.survey.rapat.form.eksternal', $slug)
            ->with('success','Checkout berhasil, silakan isi survey rapat eksternal.');
    }

}
