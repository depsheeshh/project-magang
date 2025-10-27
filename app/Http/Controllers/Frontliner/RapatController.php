<?php

namespace App\Http\Controllers\Frontliner;

use App\Http\Controllers\Controller;
use App\Models\Rapat;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RapatController extends Controller
{
    // Daftar rapat hari ini
    public function index()
    {
        $today = Carbon::today();

        $rapat = Rapat::with(['undangan.user','undangan.instansi'])
            ->whereDate('waktu_mulai', $today)
            ->orderBy('waktu_mulai')
            ->get();

        return view('frontliner.rapat.index', compact('rapat'));
    }

    // Detail rapat + status kehadiran tamu
    public function show(Rapat $rapat)
    {
        $rapat->load(['undangan.user','undangan.instansi']);
        return view('frontliner.rapat.show', compact('rapat'));
    }
}
