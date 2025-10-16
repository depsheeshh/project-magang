<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function index()
    {
        return view('qrcode.tamu');
    }

    public function pdf()
    {
        $qrCode = base64_encode(
        QrCode::format('png')
            ->size(250)
            ->margin(2)
            ->generate(route('tamu.form'))
    );

    $companyName = "Dinas Komunikasi, Informatika dan Statistik Kota Cirebon";

    $pdf = Pdf::loadView('qrcode.tamu-pdf', compact('qrCode','companyName'));
    return $pdf->download('qrcode-buku-tamu.pdf');
    }
}
