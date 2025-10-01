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

        $data = compact('user','role');

        if ($role === 'admin') {
            $data = array_merge($data, [
                'totalUsers'   => User::count(),
                'totalPegawai' => Pegawai::count(),
                'totalBidang'  => Bidang::count(),
                'totalJabatan' => Jabatan::count(),
            ]);
        }

        if ($role === 'frontliner') {
            $data['kunjunganMenunggu'] = Kunjungan::with([
                    'tamu',
                    'pegawai.user',
                    'pegawai.bidang' // bidang lewat pegawai
                ])
                ->where('status', 'menunggu')
                ->orderBy('waktu_masuk','desc')
                ->take(10)
                ->get();
        }

        if ($role === 'pegawai') {
            $pegawaiId = optional($user->pegawai)->id;
            $data['kunjunganTerbaru'] = Kunjungan::with([
                    'tamu',
                    'pegawai.user',
                    'pegawai.bidang'
                ])
                ->when($pegawaiId, fn($q) => $q->where('pegawai_id', $pegawaiId))
                ->orderBy('waktu_masuk','desc')
                ->take(10)
                ->get();
        }

        if ($role === 'tamu') {
            $tamu = Tamu::where('user_id', $user->id)->first();
            $data['kunjunganSaya'] = $tamu
                ? Kunjungan::with([
                        'pegawai.user',
                        'pegawai.bidang'
                    ])
                    ->where('tamu_id', $tamu->id)
                    ->orderBy('waktu_masuk','desc')
                    ->take(5)
                    ->get()
                : collect();
        }

        return view('dashboard.admin', $data);
    }
}
