<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\Kunjungan;
use App\Models\Tamu;
use App\Models\Survey;
use App\Models\Rapat;
use App\Models\Instansi;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first(); // ambil role utama

        // Data default
        $totalUsers = $totalPegawai = $totalBidang = $totalJabatan = null;
        $totalSurvey = $totalRapat = $totalInstansi = null;

        $kunjunganMenunggu = $kunjunganTerbaru = $kunjunganSaya = collect();

        // Tambahkan default untuk role tamu
        $total = $diterima = $ditolak = 0;
        $rapatTersedia = $undanganRapat = 0;

        // khusus pegawai
        $totalKunjungan = $sedangBertamu = $menunggu = $selesai = $ditolakPegawai = 0;
        $riwayatSingkat = collect();

        if ($role === 'admin') {
            $totalUsers    = User::count();
            $totalPegawai  = Pegawai::count();
            $totalBidang   = Bidang::count();
            $totalJabatan  = Jabatan::count();
            $totalSurvey   = Survey::count();
            $totalRapat    = Rapat::count();
            $totalInstansi = Instansi::count();
        }

        if ($role === 'frontliner') {
            $kunjunganMenunggu = Kunjungan::with(['tamu.user','pegawai.user','pegawai.bidang'])
                ->where('status','menunggu')
                ->latest()
                ->get();

            // Statistik frontliner
            $total         = Kunjungan::count();
            $diterima      = Kunjungan::whereIn('status',['sedang_bertamu','selesai'])->count();
            $ditolak       = Kunjungan::where('status','ditolak')->count();
            $sedangBertamu = Kunjungan::where('status','sedang_bertamu')->count();
            $selesai       = Kunjungan::where('status','selesai')->count();
        }

        if ($role === 'pegawai') {
            $pegawai = $user->pegawai;
            if ($pegawai) {
                // Kunjungan terbaru
                $kunjunganTerbaru = Kunjungan::with('tamu')
                    ->where('pegawai_id',$pegawai->id)
                    ->latest()
                    ->take(5)
                    ->get();

                // Ringkasan statistik
                $totalKunjungan   = Kunjungan::where('pegawai_id',$pegawai->id)->count();
                $sedangBertamu    = Kunjungan::where('pegawai_id',$pegawai->id)
                                        ->where('status','sedang_bertamu')->count();
                $menunggu         = Kunjungan::where('pegawai_id',$pegawai->id)
                                        ->where('status','menunggu')->count();
                $selesai          = Kunjungan::where('pegawai_id',$pegawai->id)
                                        ->where('status','selesai')->count();
                $ditolakPegawai   = Kunjungan::where('pegawai_id',$pegawai->id)
                                        ->where('status','ditolak')->count();

                // Riwayat singkat
                $riwayatSingkat = Kunjungan::with('tamu')
                    ->where('pegawai_id',$pegawai->id)
                    ->whereIn('status',['selesai','ditolak'])
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

                // Tambahan indikator rapat
                $undanganRapat = Rapat::whereHas('undangan', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->count();

            } else {
                $total = $diterima = $ditolak = 0;
                $undanganRapat = 0;
            }
        }

        return view('dashboard.admin', compact(
            'role',
            'totalUsers','totalPegawai','totalBidang','totalJabatan',
            'totalSurvey','totalRapat','totalInstansi',
            'kunjunganMenunggu','kunjunganTerbaru','kunjunganSaya',
            'total','diterima','ditolak','undanganRapat',
            'totalKunjungan','sedangBertamu','menunggu','selesai','ditolakPegawai','riwayatSingkat'
        ));
    }
}
