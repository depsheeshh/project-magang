<?php

namespace App\Observers;

use App\Models\RapatUndangan;
use App\Models\RapatUndanganInstansi;

class RapatUndanganObserver
{
    public function created(RapatUndangan $undangan)
    {
        if ($undangan->status_kehadiran === 'hadir') {
            RapatUndanganInstansi::where('id', $undangan->rapat_undangan_instansi_id)
                ->increment('jumlah_hadir');
        }
    }

    public function deleted(RapatUndangan $undangan)
    {
        if ($undangan->status_kehadiran === 'hadir') {
            RapatUndanganInstansi::where('id', $undangan->rapat_undangan_instansi_id)
                ->decrement('jumlah_hadir');
        }
    }

    public function updated(RapatUndangan $undangan)
    {
        if ($undangan->isDirty('status_kehadiran')) {
            $old = $undangan->getOriginal('status_kehadiran');
            $new = $undangan->status_kehadiran;

            if ($old === 'hadir' && $new !== 'hadir') {
                RapatUndanganInstansi::where('id', $undangan->rapat_undangan_instansi_id)
                    ->decrement('jumlah_hadir');
            } elseif ($old !== 'hadir' && $new === 'hadir') {
                RapatUndanganInstansi::where('id', $undangan->rapat_undangan_instansi_id)
                    ->increment('jumlah_hadir');
            }
        }
    }
}
