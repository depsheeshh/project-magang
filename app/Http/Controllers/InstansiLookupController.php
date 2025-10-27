<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Instansi;

class InstansiLookupController extends Controller
{
    public function listAdminInstansi()
    {
        // Ambil instansi yang creator-nya punya role 'admin' (Spatie)
        $instansi = Instansi::orderBy('nama_instansi')
        ->get(['id','nama_instansi','lokasi']);


        return response()->json($instansi);
    }
}
