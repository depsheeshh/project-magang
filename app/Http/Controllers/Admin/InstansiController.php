<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstansiController extends Controller
{
    public function index()
    {
        $instansi = Instansi::latest()->paginate(10);
        return view('admin.instansi.index', compact('instansi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:255|unique:instansi,nama_instansi',
            'lokasi'        => 'nullable|string|max:255',
        ]);

        Instansi::create([
            'nama_instansi' => $request->nama_instansi,
            'lokasi'        => $request->lokasi,
            'created_id'    => Auth::id(),
        ]);

        return redirect()->route('admin.instansi.index')
            ->with('success', 'Instansi berhasil ditambahkan');
    }

    public function update(Request $request, Instansi $instansi)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:255|unique:instansi,nama_instansi,'.$instansi->id,
            'lokasi'        => 'nullable|string|max:255',
        ]);

        $instansi->update([
            'nama_instansi' => $request->nama_instansi,
            'lokasi'        => $request->lokasi,
            'updated_id'    => Auth::id(),
        ]);

        return redirect()->route('admin.instansi.index')
            ->with('success', 'Instansi berhasil diperbarui');
    }

    public function destroy(Instansi $instansi)
    {
        $instansi->update(['deleted_id' => Auth::id()]);
        $instansi->delete();

        return redirect()->route('admin.instansi.index')
            ->with('success', 'Instansi berhasil dihapus');
    }
}
