<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        // Tambahan validasi sesuai role
        if ($user->hasRole('tamu')) {
            $rules['instansi'] = 'nullable|string|max:255';
            $rules['telepon']  = 'nullable|string|max:20';
            $rules['alamat']   = 'nullable|string|max:255';
        }

        if ($user->hasRole('pegawai')) {
            $rules['telepon']  = 'nullable|string|max:20';
        }

        $data = $request->validate($rules);

        // Sanitasi input
        $data['name']  = strip_tags($data['name']);
        $data['email'] = strip_tags($data['email']);

        $data['updated_id'] = Auth::id();

        $user->update($data);

        // Update relasi pegawai/tamu jika ada
        if ($user->hasRole('tamu') && $user->tamu) {
            $user->tamu->update([
                'instansi' => strip_tags($request->instansi),
                'telepon'  => strip_tags($request->telepon),
                'alamat'   => strip_tags($request->alamat),
                'updated_id' => Auth::id(),
            ]);
        }

        if ($user->hasRole('pegawai') && $user->pegawai) {
            $user->pegawai->update([
                'telepon'    => strip_tags($request->telepon),
                'updated_id' => Auth::id(),
            ]);
        }

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
