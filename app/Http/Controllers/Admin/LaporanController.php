<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil kunjungan dengan relasi tamu, pegawai, dan user pegawai
        $kunjungan = Kunjungan::with([
            'tamu',                // relasi ke tabel tamu
            'pegawai.user',        // relasi pegawai + user (untuk nama pegawai)
        ])
        ->orderByDesc('waktu_masuk')
        ->get();

        return view('admin.laporan.index', compact('kunjungan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Kunjungan $kunjungan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kunjungan $kunjungan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kunjungan $kunjungan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kunjungan $kunjungan)
    {
        //
    }
}
