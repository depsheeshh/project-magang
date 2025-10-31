<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Rapat;
use App\Models\Instansi;
use Illuminate\Http\Request;
use App\Models\RapatUndangan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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

            if ($user->hasRole('pegawai')) {
                // otomatis set DKIS
                $user->instansi_id = Instansi::firstOrCreate(
                    ['nama_instansi' => 'DKIS Kota Cirebon'],
                    ['lokasi' => 'Jl. DR. Sudarsono No.40, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134']
                )->id;
                $user->save();
            } else {
                return redirect()->route('tamu.rapat.checkin.form', $rapat->id)
                    ->with('error', 'Anda harus mengisi instansi terlebih dahulu sebelum check-in.');
            }
        }

        $undangan = RapatUndangan::where('rapat_id', $rapat->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (!$rapat->waktu_mulai || !$rapat->waktu_selesai) {
            return back()->with('error','Waktu rapat belum ditentukan.');
        }

        $now     = now();
        $mulai   = Carbon::parse($rapat->waktu_mulai);
        $selesai = Carbon::parse($rapat->waktu_selesai);

        // 1. terlalu awal
        if ($now->lt($mulai->copy()->subMinutes(15))) {
            return back()->with('error','Check-in belum dibuka.');
        }

        // 2. rapat sudah selesai (admin akhiri atau waktu_selesai lewat)
        if ($rapat->status === 'selesai' || $now->gt($selesai)) {
            $undangan->update(['status_kehadiran'=>'tidak_hadir','updated_id'=>$user->id]);
            return back()->with('error','Rapat telah selesai, Anda dinyatakan Tidak Hadir.');
        }

        // 3. telat hadir (lebih dari 30 menit setelah mulai)
        if ($now->gt($mulai->copy()->addMinutes(30))) {
            $undangan->update(['status_kehadiran'=>'tidak_hadir','updated_id'=>$user->id]);
            return back()->with('error','Anda terlambat lebih dari 30 menit, status dicatat sebagai Tidak Hadir.');
        }

        // 4. validasi lokasi
        if ($rapat->latitude && $rapat->longitude && $rapat->radius) {
            if (! $this->isWithinRadius(
                $request->latitude, $request->longitude,
                $rapat->latitude, $rapat->longitude,
                $rapat->radius
            )) {
                return back()->with('error','Lokasi Anda masih di luar radius rapat. Silakan mendekat dan coba lagi.');
            }
        }

        // 5. sukses check-in
        $undangan->update([
            'status_kehadiran'=>'hadir',
            'checked_in_at'=>$now,
            'checkin_latitude'=>$request->latitude,
            'checkin_longitude'=>$request->longitude,
            'updated_id'=>$user->id,
            'instansi_id'=>$user->instansi_id,
            'checkin_token_hash'=>null
        ]);

        return redirect()->route('tamu.rapat.saya')
            ->with('success','Check-in berhasil, status Anda tercatat hadir.');
    }

    // ✅ Check-in manual via tombol (KHUSUS PEGAWAI)
    public function pegawaiCheckin(Request $request, Rapat $rapat)
    {
        $user = $request->user();

        $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Pastikan user memang diundang
        $undangan = RapatUndangan::where('rapat_id', $rapat->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Instansi otomatis DKIS jika pegawai belum punya
        if (!$user->instansi_id && $user->hasRole('pegawai')) {
            $instansi = Instansi::firstOrCreate(
                ['nama_instansi' => 'DKIS Kota Cirebon'],
                ['lokasi' => 'Jl. DR. Sudarsono No.40, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134']
            );
            $user->instansi_id = $instansi->id;
            $user->save();
            // konsistensi undangan
            $undangan->update(['instansi_id' => $instansi->id]);
        }

        // Validasi waktu rapat
        if (!$rapat->waktu_mulai || !$rapat->waktu_selesai) {
            return back()->with('error','Waktu rapat belum ditentukan.');
        }

        $now     = now();
        $mulai   = Carbon::parse($rapat->waktu_mulai);
        $selesai = Carbon::parse($rapat->waktu_selesai);

        if ($now->lt($mulai->copy()->subMinutes(15))) {
            return back()->with('error','Check-in belum dibuka.');
        }

        if ($rapat->status === 'selesai' || $now->gt($selesai)) {
            $undangan->update(['status_kehadiran'=>'tidak_hadir','updated_id'=>$user->id]);
            return back()->with('error','Rapat telah selesai, Anda dinyatakan Tidak Hadir.');
        }

        if ($now->gt($mulai->copy()->addMinutes(30))) {
            $undangan->update(['status_kehadiran'=>'tidak_hadir','updated_id'=>$user->id]);
            return back()->with('error','Anda terlambat lebih dari 30 menit, status dicatat sebagai Tidak Hadir.');
        }

        // Validasi lokasi dengan buffer radius (lebih toleran untuk GPS)
        if ($rapat->latitude && $rapat->longitude && $rapat->radius) {
            $allowedRadius = $rapat->radius + 20; // buffer 20 m
            if (! $this->isWithinRadius(
                $request->latitude, $request->longitude,
                $rapat->latitude, $rapat->longitude,
                $allowedRadius
            )) {
                return back()->with('error','Lokasi Anda masih di luar radius rapat. Silakan mendekat dan coba lagi.');
            }
        }

        // Sukses check-in
        $undangan->update([
            'status_kehadiran' => 'hadir',
            'checked_in_at'    => $now,
            'checkin_latitude' => $request->latitude,
            'checkin_longitude'=> $request->longitude,
            'updated_id'       => $user->id,
            'instansi_id'      => $user->instansi_id,
            'checkin_token_hash'=> null,
        ]);

        return redirect()->route('pegawai.rapat.index')
            ->with('success','Check-in berhasil, status Anda tercatat hadir.');
    }



    // ✅ Check-in via QR token (tamu & pegawai)
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

        $now     = now();
        $mulai   = Carbon::parse($rapat->waktu_mulai);
        $selesai = Carbon::parse($rapat->waktu_selesai);

        // 1. terlalu awal
        if ($now->lt($mulai->copy()->subMinutes(15))) {
            return view('tamu.rapat.checkin_result', [
                'status'=>'error',
                'message'=>'Check-in belum dibuka.',
                'rapat'=>$rapat
            ]);
        }

        // 2. rapat sudah selesai
        if ($rapat->status === 'selesai' || $now->gt($selesai)) {
            $undangan->update(['status_kehadiran'=>'tidak_hadir','updated_id'=>$user->id]);
            return view('tamu.rapat.checkin_result', [
                'status'=>'error',
                'message'=>'Rapat telah selesai, Anda dinyatakan Tidak Hadir.',
                'rapat'=>$rapat
            ]);
        }

        // 3. telat hadir
        if ($now->gt($mulai->copy()->addMinutes(30))) {
            $undangan->update(['status_kehadiran'=>'tidak_hadir','updated_id'=>$user->id]);
            return view('tamu.rapat.checkin_result', [
                'status'=>'error',
                'message'=>'Anda terlambat lebih dari 30 menit, status dicatat sebagai Tidak Hadir.',
                'rapat'=>$rapat
            ]);
        }

        // 4. validasi lokasi
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
                return view('tamu.rapat.checkin_result', [
                    'status'=>'error',
                    'message'=>'Lokasi Anda masih di luar radius rapat. Silakan mendekat dan coba lagi.',
                    'rapat'=>$rapat
                ]);
            }
        }

        // 5. Pastikan instansi
        if (!$user->instansi_id) {
            if ($user->hasRole('pegawai')) {
                // otomatis set DKIS
                $instansi = Instansi::firstOrCreate(
                    ['nama_instansi' => 'DKIS Kota Cirebon'],
                    ['lokasi' => 'Jl. DR. Sudarsono No.40, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134']
                );
                $user->instansi_id = $instansi->id;
                $user->save();
            } else {
                return view('tamu.rapat.checkin_result', [
                    'status'=>'error',
                    'message'=>'Anda harus mengisi instansi terlebih dahulu sebelum check-in.',
                    'rapat'=>$rapat
                ]);
            }
        }

        // 6. sukses check-in
        $undangan->update([
            'status_kehadiran'=>'hadir',
            'checked_in_at'=>$now,
            'qr_scanned_at'=>$now,
            'updated_id'=>$user->id,
            'instansi_id'=>$user->instansi_id, // otomatis DKIS untuk pegawai
            'checkin_token_hash'=>null
        ]);

        return view('tamu.rapat.checkin_result', [
            'status'=>'success',
            'message'=>'Check-in berhasil, status Anda tercatat hadir.',
            'rapat'=>$rapat
        ]);
    }

    // ✅ Check-in via QR token (KHUSUS PEGAWAI)
    public function pegawaiCheckinByToken(Request $request, $token)
    {
        $hash = hash('sha256', $token);
        $undangan = RapatUndangan::where('checkin_token_hash', $hash)->first();

        if (!$undangan) {
            return view('pegawai.rapat.checkin_result', [
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
            return view('pegawai.rapat.checkin_result', [
                'status'=>'error',
                'message'=>'Waktu rapat belum ditentukan.',
                'rapat'=>$rapat
            ]);
        }

        $now     = now();
        $mulai   = Carbon::parse($rapat->waktu_mulai);
        $selesai = Carbon::parse($rapat->waktu_selesai);

        if ($now->lt($mulai->copy()->subMinutes(15))) {
            return view('pegawai.rapat.checkin_result', [
                'status'=>'error',
                'message'=>'Check-in belum dibuka.',
                'rapat'=>$rapat
            ]);
        }

        if ($rapat->status === 'selesai' || $now->gt($selesai)) {
            $undangan->update(['status_kehadiran'=>'tidak_hadir','updated_id'=>$user->id]);
            return view('pegawai.rapat.checkin_result', [
                'status'=>'error',
                'message'=>'Rapat telah selesai, Anda dinyatakan Tidak Hadir.',
                'rapat'=>$rapat
            ]);
        }

        if ($now->gt($mulai->copy()->addMinutes(30))) {
            $undangan->update(['status_kehadiran'=>'tidak_hadir','updated_id'=>$user->id]);
            return view('pegawai.rapat.checkin_result', [
                'status'=>'error',
                'message'=>'Anda terlambat lebih dari 30 menit, status dicatat sebagai Tidak Hadir.',
                'rapat'=>$rapat
            ]);
        }

        // Validasi lokasi (QR) dengan buffer radius
        if ($rapat->latitude && $rapat->longitude && $rapat->radius) {
            $request->validate([
                'latitude'  => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
            ]);
            $allowedRadius = $rapat->radius + 20;
            if (! $this->isWithinRadius(
                $request->latitude, $request->longitude,
                $rapat->latitude, $rapat->longitude,
                $allowedRadius
            )) {
                return view('pegawai.rapat.checkin_result', [
                    'status'=>'error',
                    'message'=>'Lokasi Anda masih di luar radius rapat. Silakan mendekat dan coba lagi.',
                    'rapat'=>$rapat
                ]);
            }
        }

        // Instansi otomatis DKIS bila kosong
        if (!$user->instansi_id && $user->hasRole('pegawai')) {
            $instansi = Instansi::firstOrCreate(
                ['nama_instansi' => 'DKIS Kota Cirebon'],
                ['lokasi' => 'Jl. DR. Sudarsono No.40, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134']
            );
            $user->instansi_id = $instansi->id;
            $user->save();
            $undangan->update(['instansi_id' => $instansi->id]);
        }

        // Sukses check-in
        $undangan->update([
            'status_kehadiran'=>'hadir',
            'checked_in_at'=>$now,
            'qr_scanned_at'=>$now,
            'updated_id'=>$user->id,
            'instansi_id'=>$user->instansi_id,
            'checkin_token_hash'=>null
        ]);

        return view('pegawai.rapat.checkin_result', [
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

    public function showPegawai(Rapat $rapat, Request $request)
    {
        $user = $request->user();

        // Ambil undangan rapat untuk pegawai ini
        $undangan = RapatUndangan::where('rapat_id', $rapat->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Pastikan instansi otomatis DKIS kalau pegawai belum punya
        if (!$user->instansi_id && $user->hasRole('pegawai')) {
            $instansi = Instansi::firstOrCreate(
                ['nama_instansi' => 'DKIS Kota Cirebon'],
                ['lokasi' => 'Jl. DR. Sudarsono No.40, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134']
            );
            $user->instansi_id = $instansi->id;
            $user->save();

            // update undangan juga biar konsisten
            $undangan->update(['instansi_id' => $instansi->id]);
        }

        return view('pegawai.rapat.checkin', compact('rapat','undangan'));
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

    public function agendaPegawai(Request $request)
    {
        $user = $request->user();

        // Ambil rapat yang user (pegawai) diundang
        $rapatSaya = Rapat::whereHas('undangan', fn($q) => $q->where('user_id', $user->id))
            ->with(['undangan' => fn($q) => $q->where('user_id', $user->id)])
            ->orderBy('waktu_mulai','desc')
            ->get();

        return view('pegawai.rapat.index', compact('rapatSaya'));
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

    // ✅ Checkout rapat khusus tamu
    public function tamuCheckout(Request $request, Rapat $rapat)
    {
        $user = $request->user();

        // Ambil undangan rapat untuk tamu ini
        $undangan = RapatUndangan::where('rapat_id', $rapat->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Pastikan sudah check-in dulu
        if ($undangan->status_kehadiran !== 'hadir') {
            return back()->with('error','Anda belum melakukan check-in.');
        }

        Log::info('Tamu checkout mandiri', [
            'rapat_id' => $rapat->id,
            'user_id' => $user->id,
            'status_before' => $undangan->status_kehadiran,
            'checked_out_at_before' => $undangan->checked_out_at,
        ]);

        // Update status kehadiran jadi selesai
        $undangan->update([
            'status_kehadiran' => 'selesai',
            'checked_out_at'   => now(),
            'updated_id'       => $user->id,
        ]);

        return redirect()->route('tamu.rapat.saya')
            ->with('success','Checkout berhasil, rapat Anda sudah ditandai selesai.');
    }


    // ✅ Checkout rapat khusus pegawai (tanpa survey)
    public function pegawaiCheckout(Request $request, Rapat $rapat)
    {
        $user = $request->user();

        $undangan = RapatUndangan::where('rapat_id', $rapat->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($undangan->status_kehadiran !== 'hadir') {
            return back()->with('error','Anda belum melakukan check-in.');
        }

        $undangan->update([
            'status_kehadiran' => 'selesai',
            'checked_out_at'   => now(),
            'updated_id'       => $user->id,
        ]);

        return redirect()->route('pegawai.rapat.index')
            ->with('success','Checkout berhasil, rapat Anda sudah ditandai selesai.');
    }


}
