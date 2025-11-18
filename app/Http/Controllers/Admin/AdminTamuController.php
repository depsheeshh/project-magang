<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Instansi;

class AdminTamuController extends Controller
{
    public function index()
    {
        $tamuList = User::role('tamu')->with('tamu')->orderBy('name')->paginate(20);
        $instansi = Instansi::orderBy('nama_instansi')->get();
        return view('admin.tamu.index', compact('tamuList','instansi'));
    }

    public function update(Request $request, User $user)
    {
        if (!$user->hasRole('tamu')) {
            abort(403, 'Hanya tamu yang bisa diedit');
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'no_hp'    => 'nullable|string|max:20',
            'alamat'   => 'nullable|string|max:255',
        ]);

        // update user (hanya name)
        $user->update([
            'name' => $request->name,
        ]);

        // sinkronisasi ke tabel tamu
        if (!$user->tamu) {
            $user->tamu()->create([
                'nama'     => $request->name,   // sinkron dengan users.name
                'instansi' => $request->instansi,
                'no_hp'    => $request->no_hp,
                'alamat'   => $request->alamat,
            ]);
        } else {
            $user->tamu->update([
                'nama'     => $request->name,   // sinkron dengan users.name
                'instansi' => $request->instansi,
                'no_hp'    => $request->no_hp,
                'alamat'   => $request->alamat,
            ]);
        }

        return redirect()->route('admin.tamu.index')->with('success','Data tamu berhasil diperbarui.');
    }
}
