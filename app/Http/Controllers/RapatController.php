<?php

namespace App\Http\Controllers;

use App\Models\Rapat;
use App\Models\RapatUndangan;
use App\Models\User;
use Illuminate\Http\Request;

class RapatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rapat = Rapat::with(['creator'])
            ->latest('waktu_mulai')
            ->paginate(15);

        // Data user tamu untuk undangan (step selanjutnya)
        $tamu = User::whereHas('roles', fn($q) => $q->where('name','tamu'))->orderBy('name')->get();

        return view('admin.rapat.index', compact('rapat','tamu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'waktu_mulai'   => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'lokasi'        => 'nullable|string|max:255',
            'latitude'      => 'nullable|numeric|between:-90,90',
            'longitude'     => 'nullable|numeric|between:-180,180',
            'radius'        => 'nullable|integer|min:10|max:10000',
        ]);

        $validated['created_by'] = $request->user()->id;
        $validated['radius'] = $validated['radius'] ?? 100;

        Rapat::create($validated);

        return redirect()->route('admin.rapat.index')->with('success','Rapat berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rapat $rapat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rapat $rapat)
    {
        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'waktu_mulai'   => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'lokasi'        => 'nullable|string|max:255',
            'latitude'      => 'nullable|numeric|between:-90,90',
            'longitude'     => 'nullable|numeric|between:-180,180',
            'radius'        => 'nullable|integer|min:10|max:10000',
        ]);

        $rapat->update($validated);

        return redirect()->route('admin.rapat.index')->with('success','Rapat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rapat $rapat)
    {
        $rapat->delete();
        return redirect()->route('admin.rapat.index')->with('success','Rapat berhasil dihapus.');
    }

     public function storeInvitation(Request $request, Rapat $rapat)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        RapatUndangan::firstOrCreate([
            'rapat_id' => $rapat->id,
            'user_id'  => $validated['user_id'],
        ]);

        return redirect()->route('admin.rapat.index')->with('success','Undangan ditambahkan.');
    }

    public function destroyInvitation(Rapat $rapat, RapatUndangan $invitation)
    {
        if ($invitation->rapat_id !== $rapat->id) {
            abort(404);
        }
        $invitation->delete();
        return redirect()->route('admin.rapat.index')->with('success','Undangan dihapus.');
    }
}
