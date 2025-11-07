<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Rapat;
use Illuminate\Http\Request;
use App\Models\RapatUndangan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RapatCheckinManualController extends Controller
{
    public function index(Rapat $rapat)
    {
        $rapat->load(['undangan.instansi', 'undanganInstansi.instansi']);

        if ($rapat->jenis_rapat === 'Internal') {
            // Ambil semua pegawai DKIS
            $pegawai = User::role('pegawai')->orderBy('name')->get();
            return view('admin.rapat.checkin-manual-internal', compact('rapat','pegawai'));
        }

        // Eksternal → tetap pakai instansi
        $instansi = $rapat->undanganInstansi()->with('instansi')->get()->pluck('instansi');
        return view('admin.rapat.checkin-manual', compact('rapat','instansi'));
    }

    public function storePeserta(Request $request, Rapat $rapat)
    {
        if ($rapat->jenis_rapat === 'Internal') {
            // ✅ Logika internal: pilih pegawai DKIS
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            $user = User::findOrFail($request->user_id);

            // Cek apakah sudah ada undangan
            $undangan = RapatUndangan::firstOrNew([
                'rapat_id' => $rapat->id,
                'user_id'  => $user->id,
            ]);

            $undangan->fill([
                'status_kehadiran' => 'hadir',
                'checked_in_at'    => now(),
                'checked_in_by'    => Auth::id(),
                'created_id'       => Auth::id(),
            ])->save();

            return back()->with('success','Pegawai berhasil di‑checkin manual.');
        }

        // ✅ Logika eksternal (seperti sebelumnya)
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'instansi_id' => 'required|exists:instansi,id',
            'email' => 'nullable|email|max:255',
        ]);

        $userId = null;
        if ($request->filled('email')) {
            $user = User::updateOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->nama,
                    'password' => bcrypt('Password123!'),
                    'email_verified_at' => now(),
                    'instansi_id' => $request->instansi_id,
                ]
            );
            $userId = $user->id;
        }

        $undanganInstansi = $rapat->undanganInstansi()
            ->where('instansi_id', $request->instansi_id)
            ->first();

        RapatUndangan::create([
            'rapat_id' => $rapat->id,
            'rapat_undangan_instansi_id' => $undanganInstansi ? $undanganInstansi->id : null,
            'user_id' => $userId,
            'instansi_id' => $request->instansi_id,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'email' => $request->email,
            'status_kehadiran' => 'hadir',          // ✅ langsung hadir
            'checked_in_at'    => now(),            // ✅ isi waktu checkin
            'checked_in_by'    => Auth::id(),
            'created_id'       => Auth::id(),
        ]);

        return back()->with('success','Peserta manual berhasil ditambahkan.');
    }

    public function updatePeserta(Request $request, Rapat $rapat, RapatUndangan $undangan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'instansi_id' => 'required|exists:instansi,id',
            'email' => 'nullable|email|max:255|unique:users,email,' . $undangan->user_id,
        ]);

        $userId = $undangan->user_id;
        if ($request->filled('email')) {
            $user = User::updateOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->nama,
                    'password' => bcrypt('Password123!'),
                    'email_verified_at' => now(),
                    'instansi_id' => $request->instansi_id,
                ]
            );
            $userId = $user->id;
        }

        // Cari undangan instansi yang sesuai
        $undanganInstansi = $rapat->undanganInstansi()
            ->where('instansi_id', $request->instansi_id)
            ->first();

        $undangan->update([
            'rapat_undangan_instansi_id' => $undanganInstansi ? $undanganInstansi->id : null,
            'user_id' => $userId,
            'instansi_id' => $request->instansi_id,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'email' => $request->email,
        ]);

        return back()->with('success','Peserta manual berhasil diperbarui.');
    }

    public function checkinPeserta(Rapat $rapat, RapatUndangan $undangan)
    {
        try {
            $now = now();

            // Validasi waktu rapat
            if ($now->lt($rapat->waktu_mulai->subMinutes(15))) {
                return back()->with('error','Rapat belum dimulai, mohon sabar menunggu.');
            }

            if ($rapat->waktu_selesai && $now->gt($rapat->waktu_selesai)) {
                $undangan->update(['status_kehadiran' => 'tidak_hadir']);
                return back()->with('error','Rapat telah selesai, peserta dianggap tidak hadir.');
            }

            if ($now->gt($rapat->waktu_mulai->addMinutes(30))) {
                $undangan->update(['status_kehadiran' => 'tidak_hadir']);
                return back()->with('error','Anda terlambat lebih dari 30 menit, status tidak hadir.');
            }

            // Cek kuota instansi
            $undanganInstansi = $rapat->undanganInstansi()
                ->where('instansi_id', $undangan->instansi_id)
                ->first();

            if (!$undanganInstansi) {
                return back()->with('error','Instansi tidak diundang dalam rapat ini.');
            }

            if ($undanganInstansi->jumlah_hadir >= $undanganInstansi->kuota) {
                return back()->with('error','Kuota instansi sudah penuh.');
            }

            // Cek apakah sudah pernah check-in
            if ($undangan->status_kehadiran === 'hadir') {
                return back()->with('error','Peserta ini sudah melakukan check-in sebelumnya.');
            }

            // Update status hadir
            $undangan->update([
                'status_kehadiran' => 'hadir',
                'checked_in_at'    => $now,
                'checked_in_by'    => Auth::id(),
            ]);

            return back()->with('success','Peserta berhasil di-check-in.');
        } catch (\Exception $e) {
            return back()->with('error','Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function checkoutPeserta(Rapat $rapat, RapatUndangan $undangan)
    {
        try {
            if ($undangan->status_kehadiran !== 'hadir') {
                return back()->with('error','Peserta belum melakukan check-in.');
            }

            $undangan->update([
                'status_kehadiran' => 'selesai',
                'checked_out_at'   => now(),
                'updated_id'       => Auth::id(),
            ]);

            // Kurangi jumlah hadir instansi
            $undanganInstansi = $rapat->undanganInstansi()
                ->where('instansi_id', $undangan->instansi_id)
                ->first();

            if ($undanganInstansi && $undanganInstansi->jumlah_hadir > 0) {
                $undanganInstansi->decrement('jumlah_hadir');
            }

            return back()->with('success','Peserta berhasil di-checkout.');
        } catch (\Exception $e) {
            return back()->with('error','Terjadi kesalahan: '.$e->getMessage());
        }
    }

}
