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

    // Halaman detail pegawai setelah scan QR (pakai token)
    public function show($token)
    {
        $pegawai = Pegawai::where('apel_token', $token)->firstOrFail();
        $user = $pegawai->user;

        // ✅ Null check: pastikan pegawai punya akun user
        if (!$user) {
            return view('apelpagi.error', [
                'message' => 'Akun pegawai ini tidak ditemukan. Hubungi admin.',
            ]);
        }

        // ✅ Validasi hari — ubah ke isMonday() setelah selesai testing
        if (!Carbon::today()->isFriday()) {
            return view('apelpagi.error', [
                'message' => 'QR Code Apel Pagi hanya berlaku hari Senin.',
            ]);
        }

        $absen = ApelPagi::whereDate('tanggal', today())
            ->where('user_id', $user->id)
            ->first();

        return view('apelpagi.scan', compact('pegawai', 'absen'));
    }

    // Proses klik tombol MASUK (pakai token)
    public function masuk(Request $request, $token)
    {
        $pegawai = Pegawai::where('apel_token', $token)->firstOrFail();
        $user = $pegawai->user;

        // ✅ Null check: pastikan pegawai punya akun user
        if (!$user) {
            return redirect()->route('apelpagi.show', $token)->with('swal', [
                'icon'  => 'error',
                'title' => 'Kesalahan',
                'text'  => 'Akun pegawai ini tidak ditemukan. Hubungi admin.',
            ]);
        }

        // ✅ Validasi hari — ubah ke isMonday() setelah selesai testing
        if (!Carbon::today()->isFriday()) {
            return redirect()->route('apelpagi.show', $token)->with('swal', [
                'icon'  => 'warning',
                'title' => 'Tidak Berlaku',
                'text'  => 'QR Code Apel Pagi hanya berlaku hari Senin.',
            ]);
        }

        $kantorLat = -6.725979820888484;
        $kantorLon = 108.53894692259564;

        // Jika koordinat tersedia, cek radius kantor
        // Jika tidak (misal HTTP di dev), lewati saja pengecekan jarak
        if ($request->latitude && $request->longitude) {
            $distance = $this->calculateDistance(
                (float)$request->latitude,
                (float)$request->longitude,
                $kantorLat,
                $kantorLon
            );

            logger()->info('Apel Pagi Debug', [
                'pegawai'    => $pegawai->nip,
                'user'       => $user->name,
                'lat_user'   => $request->latitude,
                'lon_user'   => $request->longitude,
                'lat_kantor' => $kantorLat,
                'lon_kantor' => $kantorLon,
                'distance_m' => round($distance, 2),
                'status'     => $distance > 50 ? 'di luar radius' : 'dalam radius',
            ]);

            if ($distance > 50) {
                $distanceText = $this->formatDistance($distance);
                return redirect()->route('apelpagi.show', $token)->with('swal', [
                    'icon'  => 'error',
                    'title' => 'Di Luar Radius',
                    'text'  => "Anda berada di luar radius kantor (±{$distanceText}). Pastikan Anda berada di lokasi kantor.",
                ]);
            }
        } else {
            logger()->info('Apel Pagi: koordinat tidak tersedia, cek radius dilewati', [
                'pegawai' => $pegawai->nip,
            ]);
        }

        $now = now();
        $jamBatas = Carbon::today()->setHour(7)->setMinute(30);

        $status = $now->gt($jamBatas) ? 'telat' : 'tepat_waktu';

        $telatInfo = $this->getTelatInfo($now, $jamBatas);
        $telatMenit = $telatInfo['menit'];
        $telatJamMenit = $telatInfo['jam'];

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $alreadyAbsen = ApelPagi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->exists();

        if ($alreadyAbsen) {
            return redirect()->route('apelpagi.show', $token)->with('swal', [
                'icon' => 'warning',
                'title' => 'Sudah Absen',
                'text' => 'Anda sudah absen apel pagi minggu ini. Tidak bisa absen ulang.',
            ]);
        }

        ApelPagi::firstOrCreate(
            ['user_id' => $user->id, 'tanggal' => today()],
            [
                'jam_masuk'   => $now,
                'status'      => $status,
                'telat_menit' => $telatMenit,
                'latitude'    => $request->latitude,
                'longitude'   => $request->longitude,
            ]
        );

        // ✅ PRG pattern: redirect ke halaman hasil agar mobile browser tidak stuck
        return redirect()->route('apelpagi.show', $token)->with('hasil_absen', [
            'status'         => $status,
            'telatMenit'     => $telatMenit,
            'telatJamMenit'  => $telatJamMenit,
            'jam'            => $now->format('H:i'),
        ]);
    }

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

    private function formatDistance(float $distance): string
    {
        if ($distance < 1000) {
            return number_format($distance, 0) . ' M';
        }
        return number_format($distance / 1000, 2, ',', '.') . ' KM';
    }
}
