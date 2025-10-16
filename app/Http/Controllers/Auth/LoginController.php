<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // ✅ Cek apakah email sudah diverifikasi
            if (is_null($user->email_verified_at)) {
                Auth::logout();
                return redirect()
                    ->route('verification.form')
                    ->with('status', 'Silakan verifikasi email terlebih dahulu.');
            }

            // 🚩 Redirect berdasarkan role
            if ($user->hasRole('admin') || $user->hasRole('frontliner') || $user->hasRole('pegawai')) {
                return redirect()->route('dashboard.index');
            }

            // 🧭 Kalau user biasa (termasuk tamu), arahkan ke home
            return redirect()->route('home');
        }

        // Gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ======== LOGIN GOOGLE ========
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'password' => bcrypt(Str::random(16)),
                'email_verified_at' => now(),
            ]
        );

        Auth::login($user);

        // Redirect setelah login via Google
        if ($user->hasRole('admin') || $user->hasRole('frontliner') || $user->hasRole('pegawai')) {
            return redirect()->route('dashboard.index');
        }

        return redirect()->route('home');
    }
}
