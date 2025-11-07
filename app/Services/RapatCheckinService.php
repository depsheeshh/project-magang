<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Rapat;
use App\Models\RapatUndangan;

class RapatCheckinService
{
    public function validateWaktu(Rapat $rapat): string|bool
    {
        $now     = now();
        $mulai   = Carbon::parse($rapat->waktu_mulai);
        $selesai = Carbon::parse($rapat->waktu_selesai);

        if ($now->lt($mulai->copy()->subMinutes(15))) return 'Check-in belum dibuka.';
        if ($rapat->status === 'selesai' || $now->gt($selesai)) return 'Rapat sudah selesai.';
        if ($now->gt($mulai->copy()->addMinutes(30))) return 'Anda terlambat lebih dari 30 menit.';
        return true;
    }

    public function validateLokasi(Rapat $rapat, float $lat, float $lon, int $buffer = 0): string|bool
    {
        $radius = $rapat->radius + $buffer;
        $distance = $this->haversine($lat, $lon, $rapat->latitude, $rapat->longitude);
        return $distance <= $radius ? true : 'Lokasi Anda di luar radius rapat.';
    }

    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earth = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) ** 2 +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) ** 2;
        return $earth * 2 * atan2(sqrt($a), sqrt(1-$a));
    }

    public function handleCheckin(RapatUndangan $undangan, $user, $lat, $lon): void
    {
        $undangan->update([
            'status_kehadiran' => 'hadir',
            'checked_in_at'    => now(),
            'checkin_latitude' => $lat,
            'checkin_longitude'=> $lon,
            'updated_id'       => $user->id,
            'instansi_id'      => $user->instansi_id,
            'checkin_token_hash'=> null,
        ]);
    }
}
