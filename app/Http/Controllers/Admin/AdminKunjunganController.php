<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kunjungan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminKunjunganController extends Controller
{
    public function index()
    {
        // eager load relasi yang benar
        $kunjunganList = Kunjungan::with([
            'tamu.user',        // tamu -> user
            'tamu',             // tamu langsung
            'pegawai.user',     // pegawai -> user
            'bidang'            // bidang tujuan
        ])->latest()->paginate(20);

        return view('admin.kunjungan.index', compact('kunjunganList'));
    }
}
