<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('auth.change-password');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => [
            'required',
            'confirmed',
            Password::min(8)->mixedCase()->letters()->numbers()->symbols()
        ],
        ], [
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
            'new_password.min' => 'Password baru minimal :min karakter.',
            'current_password.required' => 'Masukkan password lama.',
        ]);

        $user = Auth::user();

        // cek password lama benar atau tidak
    if (! Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Password lama salah.'])->withInput();
    }

    // validasi: password baru tidak boleh sama dengan password lama
    if (Hash::check($request->new_password, $user->password)) {
        return back()->withErrors(['new_password' => 'Password baru tidak boleh sama dengan password lama.'])->withInput();
    }

    // update password
    $user->password = Hash::make($request->new_password);
    $user->save();

    // regenerasi session biar aman
    $request->session()->regenerate();

        return back()->with('status', 'Password berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
