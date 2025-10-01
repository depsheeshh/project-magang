<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use Illuminate\Http\Request;

class BidangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bidang = Bidang::orderBy('nama_bidang')->paginate(10);
        return view('admin.bidang.index', compact('bidang'));
    }

    // public function create()
    // {
    //     return view('admin.bidang.create');
    // }

    public function store(Request $request)
    {
        $validated = $request->validate([
        'nama_bidang' => 'required|string|max:255',
        'deskripsi'   => 'nullable|string',
    ]);

    Bidang::create($validated);
        return redirect()->route('admin.bidang.index')->with('status','Bidang berhasil ditambahkan');
    }

    // public function edit(Bidang $bidang)
    // {
    //     return view('admin.bidang.edit', compact('bidang'));
    // }

    public function update(Request $request, Bidang $bidang)
    {
        $validated = $request->validate([
        'nama_bidang' => 'required|string|max:255',
        'deskripsi'   => 'nullable|string',
    ]);

    $bidang->update($validated);
        return redirect()->route('admin.bidang.index')->with('status','Bidang berhasil diperbarui');
    }

    public function destroy(Bidang $bidang)
    {
        $bidang->delete();
        return redirect()->route('admin.bidang.index')->with('status','Bidang berhasil dihapus');
    }
}
