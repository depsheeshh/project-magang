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
        if (in_array($undangan->status_kehadiran, ['hadir','selesai'])) {
            RapatUndanganInstansi::where('id', $undangan->rapat_undangan_instansi_id)
                ->decrement('jumlah_hadir');
        }
    }

    public function updated(RapatUndangan $undangan)
    {
        if ($undangan->isDirty('status_kehadiran')) {
            $old = $undangan->getOriginal('status_kehadiran');
            $new = $undangan->status_kehadiran;

            // masuk hadir → increment
            if ($old !== 'hadir' && $new === 'hadir') {
                RapatUndanganInstansi::where('id', $undangan->rapat_undangan_instansi_id)
                    ->increment('jumlah_hadir');
            }

            // keluar dari hadir → decrement HANYA jika bukan selesai
            if ($old === 'hadir' && $new !== 'hadir' && $new !== 'selesai') {
                RapatUndanganInstansi::where('id', $undangan->rapat_undangan_instansi_id)
                    ->decrement('jumlah_hadir');
            }
        }
    }
}
