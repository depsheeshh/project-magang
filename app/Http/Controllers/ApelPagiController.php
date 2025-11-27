<?php

namespace App\Http\Controllers;

use App\Models\ApelPagi;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApelPagiController extends Controller
{
    private function getTelatInfo(Carbon $now, Carbon $jamBatas): array
    {
        if ($now->lte($jamBatas)) {
            return ['menit' => 0, 'jam' => null];
        }

        return [
            'menit' => abs($now->diffInMinutes($jamBatas, false)),
            'jam'   => $now->diff($jamBatas)->format('%H:%I'),
        ];
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $pegawai = Pegawai::with('user')
            ->when($search, function($q) use ($search) {
                $q->where('nip','like',"%{$search}%")
                ->orWhereHas('user', fn($uq) => $uq->where('name','like',"%{$search}%"));
            })
            ->orderBy('nip')
            ->paginate(10);

        if ($request->ajax()) {
            return view('frontliner.apelpagi.table', compact('pegawai'))->render();
        }

        return view('frontliner.apelpagi.index', compact('pegawai','search'));
    }


    // Halaman detail pegawai setelah scan QR
    public function show($nip)
    {
        $pegawai = Pegawai::where('nip', $nip)->firstOrFail();
        $user = $pegawai->user;

        $absen = ApelPagi::whereDate('tanggal', today())
            ->where('user_id', $user->id)
            ->first();

        return view('apelpagi.scan', compact('pegawai','absen'));
    }

    // Proses klik tombol MASUK
    public function masuk(Request $request, $nip)
    {
        $pegawai = Pegawai::where('nip', $nip)->firstOrFail();
        $user = $pegawai->user;

        $kantorLat = -6.725979820888484;
        $kantorLon = 108.53894692259564;

        if (!$request->latitude || !$request->longitude) {
            return view('apelpagi.error', [
                'message' => "Lokasi tidak terdeteksi. Pastikan izin GPS aktif."
            ]);
        }

        $distance = $this->calculateDistance(
            (float)$request->latitude,
            (float)$request->longitude,
            $kantorLat,
            $kantorLon
        );

        // ✅ Tambahkan log debug
        logger()->info('Apel Pagi Debug', [
            'pegawai'   => $pegawai->nip,
            'user'      => $user->name,
            'lat_user'  => $request->latitude,
            'lon_user'  => $request->longitude,
            'lat_kantor'=> $kantorLat,
            'lon_kantor'=> $kantorLon,
            'distance_m'=> round($distance, 2),
            'status'    => $distance > 50 ? 'di luar radius' : 'dalam radius',
        ]);

        if ($distance > 50) {
            $distanceText = $this->formatDistance($distance);
            return view('apelpagi.error', [
                'message' => "Anda di luar radius kantor (±{$distanceText})."
            ]);
        }

        $now = now();
        $jamBatas = Carbon::today()->setHour(7)->setMinute(30);

        $status = $now->gt($jamBatas) ? 'telat' : 'tepat_waktu';

        // ✅ gunakan helper getTelatInfo
        $telatInfo = $this->getTelatInfo($now, $jamBatas);
        $telatMenit = $telatInfo['menit'];
        $telatJamMenit = $telatInfo['jam'];

        // Cek apakah sudah absen minggu ini
        $startOfWeek = Carbon::now()->startOfWeek(); // default Senin
        $endOfWeek = Carbon::now()->endOfWeek();     // default Minggu

        $alreadyAbsen = ApelPagi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->exists();

        if ($alreadyAbsen) {
            return redirect()->back()->with('swal', [
                'icon' => 'warning',
                'title' => 'Sudah Absen',
                'text' => 'Anda sudah absen apel pagi minggu ini. Tidak bisa absen ulang.',
            ]);
        }


        $absen = ApelPagi::firstOrCreate(
            ['user_id' => $user->id, 'tanggal' => today()],
            [
                'jam_masuk'   => $now,
                'status'      => $status,
                'telat_menit' => $telatMenit,
                'latitude'    => $request->latitude,
                'longitude'   => $request->longitude,
            ]
        );

        return $status === 'telat'
            ? view('apelpagi.telat', compact('pegawai','telatMenit','telatJamMenit'))
            : view('apelpagi.tepat', compact('pegawai','now'));
    }

    // Helper hitung jarak (Haversine formula)
    private function calculateDistance($latUser, $lonUser, $latKantor, $lonKantor): float
    {
        $earth = 6371000; // meter
        $dLat = deg2rad($latUser - $latKantor);
        $dLon = deg2rad($lonUser - $lonKantor);

        $a = sin($dLat/2) ** 2 +
             cos(deg2rad($latKantor)) * cos(deg2rad($latUser)) *
             sin($dLon/2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earth * $c;
    }

    // ✅ Helper format jarak (meter / km)
    private function formatDistance(float $distance): string
    {
        if ($distance < 1000) {
            return number_format($distance, 0) . ' M';
        }
        return number_format($distance / 1000, 2, ',', '.') . ' KM';
    }
}
