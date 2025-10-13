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
        $validated = $request->validate([
        'name' => ['required','string','max:255'],
        'email' => ['required','string','email:rfc,dns','max:255','unique:users,email'],
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

    // Sanitasi input
    $validated['name']  = strip_tags($validated['name']);
    $validated['email'] = strip_tags($validated['email']);

    // Generate kode verifikasi
    $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    // Buat user baru sekaligus dengan kode verifikasi
    $user = User::create([
        'name'                   => $validated['name'],
        'email'                  => $validated['email'],
        'password'               => Hash::make($validated['password']),
        'verification_code'      => $code,
        'verification_expires_at'=> now()->addMinutes(15),
    ]);

    try {
        // Kirim email kode verifikasi
        Mail::to($user->email)->send(new VerificationCodeMail($code));
    } catch (\Exception $e) {
        // Kalau gagal kirim email, hapus user agar tidak ada akun "nyangkut"
        $user->delete();
        return back()->withErrors(['email' => 'Gagal mengirim email verifikasi. Coba lagi.']);
    }

    // Opsional: jangan login otomatis, simpan user_id di session untuk verifikasi
    // session(['pending_user_id' => $user->id]);

    // Kalau tetap mau login otomatis:
    Auth::login($user);

    return redirect()->route('verification.form')
        ->with('status','Registrasi berhasil! Silakan cek email untuk kode verifikasi.');
    }
}
