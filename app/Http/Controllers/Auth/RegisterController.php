<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register'); // resources/views/auth/register.blade.php
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255','unique:users'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->letters()->numbers()->symbols()
            ],
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        // Buat user baru
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate kode verifikasi
        $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'verification_code'       => $code, // string
            'verification_expires_at' => now()->addMinutes(15), // datetime
        ]);

        // Kirim email kode verifikasi
        Mail::to($user->email)->send(new VerificationCodeMail($code));

        // Login user sementara
        Auth::login($user);

        // Redirect ke halaman verifikasi
        return redirect()->route('verification.form')
            ->with('status','Registrasi berhasil! Silakan cek email untuk kode verifikasi.');
    }
}
