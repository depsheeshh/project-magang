<?php

namespace App\Http\Controllers\Tamu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kunjungan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $tamu = Auth::user()->tamu;

        $total    = Kunjungan::where('tamu_id', $tamu->id)->count();
        $diterima = Kunjungan::where('tamu_id', $tamu->id)->where('status','diterima')->count();
        $ditolak  = Kunjungan::where('tamu_id', $tamu->id)->where('status','ditolak')->count();

        return view('tamu.dashboard', compact('total','diterima','ditolak'));
    }
}
