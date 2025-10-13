<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatan = Jabatan::orderBy('nama_jabatan')->paginate(10);
        return view('admin.jabatan.index', compact('jabatan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jabatan' => 'required|string|max:100|unique:jabatan,nama_jabatan',
            'reason'       => 'nullable|string|max:1000',
        ]);

        // Sanitize reason (untuk observer/log)
        $reason = isset($validated['reason']) ? strip_tags($validated['reason']) : null;
        $request->merge(['reason' => $reason]);

        $data = [
            'nama_jabatan' => $validated['nama_jabatan'],
            'created_id'   => Auth::id(),
        ];

        Jabatan::create($data);

        return redirect()->route('admin.jabatan.index')
            ->with('status', 'Jabatan berhasil ditambahkan');
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $validated = $request->validate([
            'nama_jabatan' => 'required|string|max:100|unique:jabatan,nama_jabatan,' . $jabatan->id,
            'reason'       => 'nullable|string|max:1000',
        ]);

        $reason = isset($validated['reason']) ? strip_tags($validated['reason']) : null;
        $request->merge(['reason' => $reason]);

        $jabatan->update([
            'nama_jabatan' => $validated['nama_jabatan'],
            'updated_id'   => Auth::id(),
        ]);

        return redirect()->route('admin.jabatan.index')
            ->with('status', 'Jabatan berhasil diperbarui');
    }

    public function destroy(Request $request, Jabatan $jabatan)
    {
        $jabatan->update(['deleted_id' => Auth::id()]);
        $jabatan->delete();

        return redirect()->route('admin.jabatan.index')
            ->with('status', 'Jabatan berhasil dihapus');
    }
}
