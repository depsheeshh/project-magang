<?php

namespace App\Http\Controllers\Admin;

use App\Models\ApelPagi;
use App\Models\Pegawai;
use App\Models\Bidang;
use App\Models\Jabatan;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ApelPagiController extends Controller
{
    // Tampilkan history apel pagi
    public function index(Request $request)
{
    $query = ApelPagi::with(['user.pegawai.bidang','user.pegawai.jabatan']);

    // 🔍 Search by NIP / Nama
    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('user.pegawai', function($q) use ($search) {
            $q->where('nip','like',"%{$search}%")
              ->orWhereHas('user', fn($uq) => $uq->where('name','like',"%{$search}%"));
        });
    }

    // 📅 Filter periode (fleksibel, bisa range bebas)
    if ($request->filled('start_date')) {
        $query->whereDate('tanggal','>=',$request->start_date);
    }
    if ($request->filled('end_date')) {
        $query->whereDate('tanggal','<=',$request->end_date);
    }

    $history = $query->orderBy('tanggal','desc')->paginate(20);

    // ✅ AJAX partial render
    if ($request->ajax()) {
        return view('admin.apelpagi.table', compact('history'))->render();
    }

    return view('admin.apelpagi.index', compact('history'))
        ->with('search',$request->search)
        ->with('start_date',$request->start_date)
        ->with('end_date',$request->end_date);
}


    // Export ke PDF
    public function exportPdf(Request $request)
    {
        $query = ApelPagi::with(['user.pegawai.bidang','user.pegawai.jabatan'])
            ->orderBy('tanggal','desc');

        // 🔍 Filter by search (NIP / Nama)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user.pegawai', function($q) use ($search) {
                $q->where('nip','like',"%{$search}%")
                ->orWhereHas('user', fn($uq) => $uq->where('name','like',"%{$search}%"));
            });
        }

        // 📅 Filter periode
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal','>=',$request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal','<=',$request->end_date);
        }

        $history = $query->get();

        $pdf = Pdf::loadView('admin.apelpagi.pdf', [
            'history'    => $history,
            'printed_at' => Carbon::now()->translatedFormat('d F Y H:i'),
            'filters'    => [
                'search'      => $request->search,
                'start_date'  => $request->start_date,
                'end_date'    => $request->end_date,
            ]
        ])->setOption('isPhpEnabled', true);

        return $pdf->download('laporan-apelpagi-'.now()->format('Ymd').'.pdf');
    }
}


