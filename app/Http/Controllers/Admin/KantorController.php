<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kantor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KantorController extends Controller
{
    private function routePrefix()
    {
        return Auth::user()->hasRole('pegawai') ? 'pegawai' : 'admin';
    }

    public function index()
    {
        $kantor = Kantor::all();
        return view('admin.kantor.index', compact('kantor'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kantor' => 'required|string|max:150',
            'alamat'      => 'required|string',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
        ]);

        Kantor::create($data + ['created_id' => Auth::id()]);
        return redirect()->route($this->routePrefix().'.kantor.index')->with('success','Kantor berhasil ditambahkan');
    }

    public function update(Request $request, Kantor $kantor)
    {
        $data = $request->validate([
            'nama_kantor' => 'required|string|max:150',
            'alamat'      => 'required|string',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
        ]);

        $kantor->update($data + ['updated_id' => Auth::id()]);
        return redirect()->route($this->routePrefix().'.kantor.index')->with('success','Kantor berhasil diperbarui');
    }

    public function destroy(Kantor $kantor)
    {
        $kantor->update(['deleted_id' => Auth::id()]);
        $kantor->delete();
        return redirect()->route($this->routePrefix().'.kantor.index')->with('success','Kantor berhasil dihapus');
    }
}
