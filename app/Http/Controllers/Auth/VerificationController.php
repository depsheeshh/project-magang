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
        return view('auth.verify-email');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required','string','size:6'],
        ]);

        $user = Auth::user();
        $inputCode = trim($request->code);

        if (!$user->verification_code || !$user->verification_expires_at) {
            return back()->withErrors(['code' => 'Kode tidak ditemukan. Silakan kirim ulang kode.']);
        }

        if (now()->greaterThan($user->verification_expires_at)) {
            return back()->withErrors(['code' => 'Kode sudah kadaluarsa. Silakan kirim ulang kode.']);
        }

        if ((string)$user->verification_code !== $inputCode) {
            return back()->withErrors(['code' => 'Kode verifikasi tidak cocok.']);
        }

        // ✅ Update email_verified_at
        $user->update([
            'email_verified_at'       => now(),
            'verification_code'       => null,
            'verification_expires_at' => null,
        ]);

        // Refresh session agar data user terbaru
        Auth::setUser($user);

        // Kalau belum punya role → redirect ke home
        if ($user->roles()->count() === 0) {
            return redirect()->route('home')->with('success', 'Email berhasil diverifikasi! Silakan isi buku tamu untuk melanjutkan.');
        }

        return redirect()->route('dashboard.index')->with('success', 'Email berhasil diverifikasi!');
    }

    public function resend()
    {
        $user = Auth::user();

        // Generate kode baru
        $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'verification_code'       => $code,
            'verification_expires_at' => now()->addMinutes(15),
        ]);

        // Kirim ulang email
        Mail::to($user->email)->send(new VerificationCodeMail($code));

        return back()->with('status', 'Kode verifikasi baru telah dikirim ke email Anda.');
    }
}
