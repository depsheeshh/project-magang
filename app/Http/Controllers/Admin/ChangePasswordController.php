<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{
    public function edit()
    {
        return view('admin.password.change-password'); // sesuai view yang sudah kamu buat
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => [
                'required',
                'confirmed', // otomatis cek dengan field new_password_confirmation
                Password::min(8)->mixedCase()->letters()->numbers()->symbols(),
            ],
        ]);

        $user = $request->user();

        // cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        // validasi: password baru tidak boleh sama dengan password lama
        if (Hash::check($request->new_password, $user->password)) {
            return back()->withErrors(['new_password' => 'Password baru tidak boleh sama dengan password lama.']);
        }

        // update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('status', 'Password berhasil diperbarui.');
    }
}
