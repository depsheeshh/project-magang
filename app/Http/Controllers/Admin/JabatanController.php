<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatan = Jabatan::orderBy('nama_jabatan')->paginate(10);
        return view('admin.jabatan.index', compact('jabatan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jabatan' => 'required|string|max:100',
            'reason'       => 'nullable|string|max:1000',
        ]);

        // inject audit ids
        $data = [
            'nama_jabatan' => $validated['nama_jabatan'],
            'created_id'   => Auth::id(),
        ];

        // simpan (observer akan menangkap request('reason'))
        Jabatan::create($data);

        return redirect()->route('admin.jabatan.index')->with('status', 'Jabatan berhasil ditambahkan');
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $validated = $request->validate([
            'nama_jabatan' => 'required|string|max:100',
            'reason'       => 'nullable|string|max:1000',
        ]);

        $jabatan->update([
            'nama_jabatan' => $validated['nama_jabatan'],
            'updated_id'   => Auth::id(),
        ]);

        return redirect()->route('admin.jabatan.index')->with('status', 'Jabatan berhasil diperbarui');
    }

    public function destroy(Request $request, Jabatan $jabatan)
    {
        // reason optional, tetap tersalurkan ke observer via request('reason')
        $jabatan->update(['deleted_id' => Auth::id()]);
        $jabatan->delete();

        return redirect()->route('admin.jabatan.index')->with('status', 'Jabatan berhasil dihapus');
    }
}
