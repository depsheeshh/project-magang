<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Instansi;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $instansi = Instansi::all();
        return view('profile.edit', compact('user','instansi'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:3048',
        ];

        if ($user->hasRole('tamu')) {
            $rules['instansi'] = 'required|string|max:255';
            $rules['no_hp']    = 'nullable|string|max:20|regex:/^[0-9\+\-\s]+$/';
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

        // ✅ Upload foto profil
        if ($request->hasFile('profile_photo')) {
            // hapus foto lama jika ada
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $path = $request->file('profile_photo')->store('profile_photos','public');
            $data['profile_photo'] = $path;
        }

        $user->update($data);

        // Update relasi tamu
        if ($user->hasRole('tamu') && $user->tamu) {
            $user->tamu->update([
                'instansi'   => strip_tags($request->instansi),
                'no_hp'      => strip_tags($request->no_hp),
                'alamat'     => strip_tags($request->alamat),
                'updated_id' => Auth::id(),
            ]);
        }

        // Update relasi pegawai
        if ($user->hasRole('pegawai') && $user->pegawai) {
            $user->pegawai->update([
                'telepon'    => strip_tags($request->telepon),
                'updated_id' => Auth::id(),
            ]);
        }

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui');
    }

    public function resetPhoto()
    {
        $user = Auth::user();

        // hapus file lama jika ada
        if ($user->profile_photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo);
        }

        // kosongkan kolom profile_photo
        $user->update([
            'profile_photo' => null,
            'updated_id'    => Auth::id(),
        ]);

        return redirect()->route('profile')->with('success','Foto profil berhasil direset ke default.');
    }

}
