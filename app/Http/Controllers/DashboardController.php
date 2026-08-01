<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\Kunjungan;
use App\Models\ApelPagi;
use App\Models\Tamu;
use App\Models\Survey;
use App\Models\Rapat;
use App\Models\Instansi;
use \App\Models\SurveyRapat;
use \App\Models\RapatUndangan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first(); // ambil role utama

        // === Filter Bulan & Tahun ===
        $now        = Carbon::now();
        $bulan      = (int) $request->get('bulan', $now->month);
        $tahun      = (int) $request->get('tahun', $now->year);

        // Validasi rentang
        if ($bulan < 1 || $bulan > 12) $bulan = $now->month;
        if ($tahun < 2000 || $tahun > $now->year + 1) $tahun = $now->year;

        $startOfMonth = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endOfMonth   = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        // Daftar tahun yang tersedia (dari tahun pertama data sampai sekarang)
        $tahunList = range(2023, $now->year);
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        // Data default
        $totalUsers = $totalPegawai = $totalBidang = $totalJabatan = null;
        $totalSurvey = $totalRapat = $totalInstansi = $totalTamu = null;

        $kunjunganMenunggu = $kunjunganTerbaru = $kunjunganSaya = collect();

        // Default untuk role tamu
        $total = $diterima = $ditolak = 0;
        $undanganRapat = 0;

        // khusus pegawai
        $totalKunjungan = $sedangBertamu = $menunggu = $selesai = $ditolakPegawai = 0;
        $riwayatSingkat = collect();
        $totalRapatPegawai = 0;

        // ✅ Inisialisasi aman untuk indikator admin (biar tidak undefined)
        $surveyRapatTotal   = 0;
        $surveyRapatFilled  = 0;
        $surveyRapatPending = 0;
        $totalKunjunganTamu = 0;
        $apelTotal = 0;

        if ($role === 'admin') {
            // Data statis (tidak difilter bulan)
            $totalUsers    = User::count();
            $totalPegawai  = Pegawai::count();
            $totalBidang   = Bidang::count();
            $totalJabatan  = Jabatan::count();
            $totalInstansi = Instansi::count();
            $totalTamu     = User::role('tamu')->count();

            // Data difilter per bulan
            $totalSurvey        = Survey::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $totalRapat         = Rapat::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $totalKunjunganTamu = Kunjungan::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $apelTotal          = ApelPagi::whereBetween('tanggal', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])->count();

            // ✅ indikator survey rapat (per bulan)
            $surveyRapatTotal   = SurveyRapat::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $surveyRapatFilled  = RapatUndangan::where('status_survey', 'sudah_isi')
                                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $surveyRapatPending = RapatUndangan::where('status_survey', 'belum_isi')
                                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        }

        if ($role === 'frontliner') {
            $kunjunganMenunggu = Kunjungan::with(['tamu.user', 'pegawai.user', 'pegawai.bidang'])
                ->where('status', 'menunggu')
                ->latest()
                ->get();

            // Statistik frontliner (per bulan)
            $total         = Kunjungan::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $diterima      = Kunjungan::whereIn('status', ['sedang_bertamu', 'selesai'])
                                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $ditolak       = Kunjungan::where('status', 'ditolak')
                                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $sedangBertamu = Kunjungan::where('status', 'sedang_bertamu')
                                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $selesai       = Kunjungan::where('status', 'selesai')
                                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

            $totalRapat    = Rapat::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        }

        if ($role === 'pegawai') {
            $pegawai = $user->pegawai;
            if ($pegawai) {
                // Kunjungan terbaru (tidak difilter bulan, selalu 5 terakhir)
                $kunjunganTerbaru = Kunjungan::with('tamu')
                    ->where('pegawai_id', $pegawai->id)
                    ->latest()
                    ->take(5)
                    ->get();

                // Ringkasan statistik (per bulan)
                $totalKunjungan = Kunjungan::where('pegawai_id', $pegawai->id)
                                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                $sedangBertamu  = Kunjungan::where('pegawai_id', $pegawai->id)
                                    ->where('status', 'sedang_bertamu')
                                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                $menunggu       = Kunjungan::where('pegawai_id', $pegawai->id)
                                    ->where('status', 'menunggu')
                                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                $selesai        = Kunjungan::where('pegawai_id', $pegawai->id)
                                    ->where('status', 'selesai')
                                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                $ditolakPegawai = Kunjungan::where('pegawai_id', $pegawai->id)
                                    ->where('status', 'ditolak')
                                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

                // Riwayat singkat (tidak difilter bulan, selalu 5 terakhir)
                $riwayatSingkat = Kunjungan::with('tamu')
                    ->where('pegawai_id', $pegawai->id)
                    ->whereIn('status', ['selesai', 'ditolak'])
                    ->latest()
                    ->take(5)
                    ->get();

                // ✅ indikator rapat pegawai (per bulan)
                $totalRapatPegawai = Rapat::whereHas('undangan', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            }
        }

        if ($role === 'tamu') {
            $tamu = $user->tamu;
            if ($tamu) {
                // Statistik per bulan
                $total    = Kunjungan::where('tamu_id', $tamu->id)
                                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                $diterima = Kunjungan::where('tamu_id', $tamu->id)
                                ->whereIn('status', ['sedang_bertamu', 'selesai'])
                                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                $ditolak  = Kunjungan::where('tamu_id', $tamu->id)
                                ->where('status', 'ditolak')
                                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

                // Undangan rapat (per bulan)
                $undanganRapat = Rapat::whereHas('undangan', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

            } else {
                $total = $diterima = $ditolak = 0;
                $undanganRapat = 0;
            }
        }

        return view('dashboard.admin', compact(
            'role',
            'bulan', 'tahun', 'bulanList', 'tahunList',
            'totalUsers', 'totalPegawai', 'totalBidang', 'totalJabatan',
            'totalSurvey', 'totalRapat', 'totalInstansi', 'totalTamu',
            'surveyRapatTotal', 'surveyRapatFilled', 'surveyRapatPending', 'totalKunjunganTamu',
            'kunjunganMenunggu', 'kunjunganTerbaru', 'kunjunganSaya',
            'total', 'diterima', 'ditolak', 'undanganRapat',
            'totalKunjungan', 'sedangBertamu', 'menunggu', 'selesai', 'ditolakPegawai', 'riwayatSingkat',
            'totalRapatPegawai',
            'apelTotal'
        ));
    }
}
