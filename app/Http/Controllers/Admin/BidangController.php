<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mews\Purifier\Facades\Purifier;

class BidangController extends Controller
{
    public function index()
    {
        $bidang = Bidang::orderBy('nama_bidang')->paginate(10);
        return view('admin.bidang.index', compact('bidang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bidang' => 'required|string|max:255|unique:bidang,nama_bidang',
            'deskripsi'   => 'nullable|string',
        ]);

        // Plain text sanitization (gunakan Purifier jika HTML diizinkan)
        $validated['deskripsi'] = isset($validated['deskripsi'])
            ? strip_tags($validated['deskripsi'])
            : null;

        $validated['created_id'] = Auth::id();

        Bidang::create($validated);

        return redirect()->route('admin.bidang.index')
            ->with('status','Bidang berhasil ditambahkan');
    }

    public function show($id)
    {
        $bidang = Bidang::findOrFail($id);
        return view('admin.bidang.show', compact('bidang'));
    }

    public function update(Request $request, Bidang $bidang)
    {
        $validated = $request->validate([
            'nama_bidang' => 'required|string|max:255|unique:bidang,nama_bidang,' . $bidang->id,
            'deskripsi'   => 'nullable|string',
        ]);

        $validated['deskripsi'] = isset($validated['deskripsi'])
            ? strip_tags($validated['deskripsi'])
            : null;

        $validated['updated_id'] = Auth::id();

        $bidang->update($validated);

        return redirect()->route('admin.bidang.index')
            ->with('status','Bidang berhasil diperbarui');
    }

    public function destroy(Bidang $bidang)
    {
        $bidang->update(['deleted_id' => Auth::id()]);
        $bidang->delete();

        return redirect()->route('admin.bidang.index')
            ->with('status','Bidang berhasil dihapus');
    }
}
