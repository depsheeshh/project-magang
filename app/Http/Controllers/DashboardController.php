<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\Kunjungan;
use App\Models\Tamu;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first(); // ambil role utama

        // Data default
        $totalUsers = $totalPegawai = $totalBidang = $totalJabatan = null;
        $kunjunganMenunggu = $kunjunganTerbaru = $kunjunganSaya = collect();

        // Tambahkan default untuk role tamu
        $total = $diterima = $ditolak = 0;

        if ($role === 'admin') {
            $totalUsers   = User::count();
            $totalPegawai = Pegawai::count();
            $totalBidang  = Bidang::count();
            $totalJabatan = Jabatan::count();
        }

        if ($role === 'frontliner') {
            $kunjunganMenunggu = Kunjungan::with(['tamu.user','pegawai.user','pegawai.bidang'])
                ->where('status','menunggu')
                ->latest()
                ->get();
        }

        if ($role === 'pegawai') {
            $pegawai = $user->pegawai; // relasi user->pegawai
            if ($pegawai) {
                $kunjunganTerbaru = Kunjungan::with('tamu')
                    ->where('pegawai_id',$pegawai->id)
                    ->latest()
                    ->take(5)
                    ->get();
            }
        }

        if ($role === 'tamu') {
            $tamu = $user->tamu;
            if ($tamu) {
                $total    = Kunjungan::where('tamu_id', $tamu->id)->count();
                $diterima = Kunjungan::where('tamu_id', $tamu->id)
                                ->whereIn('status',['sedang_bertamu','selesai'])
                                ->count();
                $ditolak  = Kunjungan::where('tamu_id', $tamu->id)
                                ->where('status','ditolak')
                                ->count();
            } else {
                $total = $diterima = $ditolak = 0;
            }
        }

        return view('dashboard.admin', compact(
            'role',
            'totalUsers','totalPegawai','totalBidang','totalJabatan',
            'kunjunganMenunggu','kunjunganTerbaru','kunjunganSaya','total','diterima','ditolak'
        ));
    }
}
