<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\Kunjungan;
use App\Models\Bidang;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use App\Notifications\TamuBaruNotification;
use App\Notifications\UserBaruNotification;

class TamuController extends Controller
{
    public function create()
    {
        $bidang = Bidang::all();
        return view('tamu.form', compact('bidang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:150',
            'email'      => 'required|email|max:255',
            'instansi'   => 'required|string|max:150',
            'no_hp'      => 'required|string|max:20|regex:/^[0-9\+\-\s]+$/',
            'alamat'     => 'required|string',
            'keperluan'  => 'required|string|max:255',
            'pegawai_id' => 'required|exists:pegawai,id',
        ]);

        $user = User::where('email', $validated['email'])->first();
        $isNew = false;

        if (!$user) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => bcrypt("Password123!"),
            ]);
            $user->assignRole('tamu');
            $isNew = true;

            $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->update([
                'verification_code'       => $code,
                'verification_expires_at' => now()->addMinutes(15),
            ]);

            Mail::to($user->email)->send(new VerificationCodeMail($code));
        } else {
            if ($user->name !== $validated['name']) {
                $user->update(['name' => $validated['name']]);
            }
            if (!$user->hasRole('tamu')) {
                $user->assignRole('tamu');
            }
        }

        $tamu = Tamu::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama'     => $validated['name'],
                'email'    => $validated['email'],
                'instansi' => $validated['instansi'],
                'no_hp'    => $validated['no_hp'],
                'alamat'   => $validated['alamat'],
            ]
        );

        $kunjungan = Kunjungan::create([
            'tamu_id'     => $tamu->id,
            'pegawai_id'  => $validated['pegawai_id'],
            'keperluan'   => trim(strip_tags($validated['keperluan'])),
            'waktu_masuk' => now(),
            'status'      => 'menunggu',
        ]);


        $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new UserBaruNotification($user, 'form_tamu'));
            }

        // 🚩 Kirim notifikasi ke pegawai yang dituju
        $pegawai = Pegawai::with('user')->find($validated['pegawai_id']);
        if ($pegawai && $pegawai->user) {
            $pegawai->user->notify(new TamuBaruNotification($tamu, $kunjungan));
        }

        // Kirim juga ke semua frontliner
        $frontliners = User::role('frontliner')->get();
        foreach ($frontliners as $f) {
            $f->notify(new TamuBaruNotification($tamu, $kunjungan));
        }

        if ($isNew) {
            Auth::login($user);
            session(['after_tamu_form' => true]);
            $request->session()->forget('url.intended');

            return redirect()->route('verification.form')
                ->with('info','Akun berhasil dibuat. Silakan cek email untuk verifikasi.');
        }

        Auth::login($user);
        $request->session()->forget('url.intended');
        return redirect()->route('tamu.thanks')->with('success','Kunjungan berhasil dicatat!');
    }

    public function status()
    {
        $user = Auth::user();
        $tamu = $user ? Tamu::where('user_id', $user->id)->first() : null;

        $kunjungan = $tamu
            ? Kunjungan::with(['pegawai.user'])
                ->where('tamu_id', $tamu->id)
                ->orderBy('waktu_masuk','desc')
                ->get()
            : collect();

        return view('tamu.status', compact('kunjungan'));
    }

    public function getPegawaiByBidang($bidangId)
    {
        $pegawai = Pegawai::with('user')
            ->where('bidang_id', $bidangId)
            ->get();

        return response()->json($pegawai);
    }
}
