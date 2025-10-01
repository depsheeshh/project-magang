<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\User;
use App\Models\Bidang;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     * - Kirim paginator pegawai
     * - Kirim users (yang belum punya pegawai)
     * - Kirim master bidang & jabatan untuk dropdown di modal
     */
    public function index()
    {
        $pegawai = Pegawai::with(['user','bidang','jabatan'])->paginate(10);

        // Tampilkan hanya user yang belum punya entri pegawai
        $users   = User::doesntHave('pegawai')->orderBy('name')->get();
        $bidang  = Bidang::orderBy('nama_bidang')->get();
        $jabatan = Jabatan::orderBy('nama_jabatan')->get();

        return view('admin.pegawai.index', compact('pegawai','users','bidang','jabatan'));
    }

    /**
     * Show the form for creating a new resource.
     * - Jika tetap memakai halaman create terpisah, kirim master data yang sama.
     */
    // public function create()
    // {
    //     $users   = User::doesntHave('pegawai')->orderBy('name')->get();
    //     $bidang  = Bidang::orderBy('nama_bidang')->get();
    //     $jabatan = Jabatan::orderBy('nama_jabatan')->get();

    //     return view('admin.pegawai.create', compact('users','bidang','jabatan'));
    // }

    /**
     * Store a newly created resource in storage.
     * - Validasi input
     * - Isi audit created_id
     * - Reason diteruskan ke observer via request()
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id|unique:pegawai,user_id',
            'bidang_id' => 'nullable|exists:bidang,id',
            'jabatan_id'=> 'nullable|exists:jabatan,id',
            'nip'       => 'nullable|string|max:50',
            'telepon'   => 'nullable|string|max:20',
            'reason'    => 'nullable|string|max:1000',
        ]);

        Pegawai::create([
            'user_id'   => $validated['user_id'],
            'bidang_id' => $validated['bidang_id'] ?? null,
            'jabatan_id'=> $validated['jabatan_id'] ?? null,
            'nip'       => $validated['nip'] ?? null,
            'telepon'   => $validated['telepon'] ?? null,
            'created_id'=> Auth::id(),
        ]);

        return redirect()->route('admin.pegawai.index')->with('status','Pegawai berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pegawai $pegawai)
    {
        // opsional: detail pegawai
        return view('admin.pegawai.show', compact('pegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     * - Jika memakai halaman edit terpisah, kirim master data juga.
     */
    // public function edit(Pegawai $pegawai)
    // {
    //     $bidang  = Bidang::orderBy('nama_bidang')->get();
    //     $jabatan = Jabatan::orderBy('nama_jabatan')->get();
    //     return view('admin.pegawai.edit', compact('pegawai','bidang','jabatan'));
    // }

    /**
     * Update the specified resource in storage.
     * - Validasi
     * - Isi audit updated_id
     * - Pastikan reason tersedia sebelum update (agar observer bisa baca request('reason'))
     */
    public function update(Request $request, Pegawai $pegawai)
    {
        $validated = $request->validate([
            // user_id tidak diubah lewat edit (relasi tetap)
            'bidang_id' => 'nullable|exists:bidang,id',
            'jabatan_id'=> 'nullable|exists:jabatan,id',
            'nip'       => 'nullable|string|max:50',
            'telepon'   => 'nullable|string|max:20',
            'reason'    => 'nullable|string|max:1000',
        ]);

        // Pastikan observer bisa membaca reason: reason sudah ada di $request
        $pegawai->update([
            'bidang_id' => $validated['bidang_id'] ?? null,
            'jabatan_id'=> $validated['jabatan_id'] ?? null,
            'nip'       => $validated['nip'] ?? null,
            'telepon'   => $validated['telepon'] ?? null,
            'updated_id'=> Auth::id(),
        ]);

        return redirect()->route('admin.pegawai.index')->with('status','Pegawai berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     * - Set deleted_id sebelum delete
     * - Reason ikut di request untuk observer
     */
    public function destroy(Request $request, Pegawai $pegawai)
    {
        // simpan deleted_id dulu (tetap tercatat meski soft delete)
        $pegawai->update(['deleted_id' => Auth::id()]);
        $pegawai->delete();

        return redirect()->route('admin.pegawai.index')->with('status','Pegawai berhasil dihapus');
    }
}
