<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use App\Models\Kantor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RuanganController extends Controller
{
    private function routePrefix()
    {
        return Auth::user()->hasRole('pegawai') ? 'pegawai' : 'admin';
    }
    public function index()
    {
        $ruangan = Ruangan::with('kantor')->get();
        $kantor  = Kantor::all();
        return view('admin.ruangan.index', compact('ruangan','kantor'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_ruangan'       => 'required|string|max:255',
            'id_kantor'          => 'required|exists:kantor,id',
            'kapasitas_maksimal' => 'nullable|integer|min:0',
            'dipakai'            => 'boolean',
        ]);

        Ruangan::create($data + ['created_id' => Auth::id()]);
        return redirect()->route($this->routePrefix().'.ruangan.index')->with('success','Ruangan berhasil ditambahkan');
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $data = $request->validate([
            'nama_ruangan'       => 'required|string|max:255',
            'id_kantor'          => 'required|exists:kantor,id',
            'kapasitas_maksimal' => 'nullable|integer|min:0',
            'dipakai'            => 'boolean',
        ]);

        $ruangan->update($data + ['updated_id' => Auth::id()]);
        return redirect()->route($this->routePrefix().'.ruangan.index')->with('success','Ruangan berhasil diperbarui');
    }

    public function destroy(Ruangan $ruangan)
    {
        $ruangan->update(['deleted_id' => Auth::id()]);
        $ruangan->delete();
        return redirect()->route($this->routePrefix().'.ruangan.index')->with('success','Ruangan berhasil dihapus');
    }
}
