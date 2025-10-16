<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class VerificationController extends Controller
{
    public function showForm()
    {
        // Pastikan hanya user yang belum verifikasi bisa akses form
        if (Auth::user()?->email_verified_at) {
            return redirect()->route('dashboard.index')
                ->with('info', 'Email Anda sudah terverifikasi.');
        }

        return view('auth.verify-email');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required','string','size:6'],
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Silakan login terlebih dahulu.']);
        }

        $inputCode = trim($request->code);

        // Cek apakah kode ada
        if (!$user->verification_code || !$user->verification_expires_at) {
            return back()->withErrors(['code' => 'Kode tidak ditemukan. Silakan kirim ulang kode.']);
        }

        // Cek expired
        if (now()->greaterThan($user->verification_expires_at)) {
            return back()->withErrors(['code' => 'Kode sudah kadaluarsa. Silakan kirim ulang kode.']);
        }

        // Cek kecocokan
        if ((string)$user->verification_code !== $inputCode) {
            return back()->withErrors(['code' => 'Kode verifikasi tidak cocok.']);
        }

        // ✅ Update email_verified_at
       $user->forceFill([
            'email_verified_at'       => now(),
            'verification_code'       => null,
            'verification_expires_at' => null,
        ])->save();

        Auth::setUser($user);

        // Kalau belum punya role → ke home
        if ($user->roles()->count() === 0) {
            return redirect()->route('home')
                ->with('success', 'Email berhasil diverifikasi! Silakan isi buku tamu untuk melanjutkan.');
        }

        // 🚩 Cek apakah ini verifikasi setelah isi form tamu
        if (session()->pull('after_tamu_form', false) && $user->hasRole('tamu')) {
            return redirect()->route('tamu.thanks')
                ->with('success', 'Email berhasil diverifikasi! Kunjungan Anda sudah tercatat.');
        }

        return redirect()->route('dashboard.index')
            ->with('success', 'Email berhasil diverifikasi!');
    }

    public function resend()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Silakan login terlebih dahulu.']);
        }

        // Generate kode baru 6 digit
        $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->forceFill([
            'verification_code'       => $code,
            'verification_expires_at' => now()->addMinutes(15),
        ])->save();

        // Kirim ulang email
        Mail::to($user->email)->send(new VerificationCodeMail($code));

        return back()->with('status', 'Kode verifikasi baru telah dikirim ke email Anda.');
    }
}
