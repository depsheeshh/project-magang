<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $query = Kunjungan::with(['tamu','pegawai.user'])
        ->orderByDesc('waktu_masuk');

    // Filter periode
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('waktu_masuk', [
            $request->start_date . ' 00:00:00',
            $request->end_date . ' 23:59:59'
        ]);
    }

    // Filter status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $kunjungan = $query->get();

    // Rekap data sesuai hasil filter
    $rekap = [
        'total'          => $kunjungan->count(),
        'menunggu'       => $kunjungan->where('status','menunggu')->count(),
        'sedang_bertamu' => $kunjungan->where('status','sedang_bertamu')->count(),
        'selesai'        => $kunjungan->where('status','selesai')->count(),
        'ditolak'        => $kunjungan->where('status','ditolak')->count(),
    ];

    return view('admin.laporan.index', compact('kunjungan','rekap'))
        ->with([
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'status'     => $request->status,
        ]);
    }

    public function cetakPdf(Request $request)
    {
        $query = Kunjungan::with(['tamu','pegawai.user'])
        ->orderByDesc('waktu_masuk');

    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('waktu_masuk', [
            $request->start_date . ' 00:00:00',
            $request->end_date . ' 23:59:59'
        ]);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $kunjungan = $query->get();

    $pdf = PDF::loadView('admin.laporan.pdf', compact('kunjungan'))
              ->setPaper('a4', 'landscape');

    // $pdf->getDomPDF()->getCanvas()->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
    //     $text = "Halaman $pageNumber dari $pageCount";
    //     $font = $fontMetrics->get_font("sans-serif", "normal");
    //     $canvas->text(520, 820, $text, $font, 10); // posisi X,Y di kertas A4
    // });

    // Kalau mau preview di tab baru:
    // return $pdf->stream('laporan_kunjungan.pdf');

    // Kalau mau langsung download (tanpa tab baru):
    return $pdf->download('laporan_kunjungan.pdf');
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
