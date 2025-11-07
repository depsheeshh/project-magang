<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RapatUndanganInstansi extends Model
{
    protected $table = 'rapat_undangan_instansi';
    protected $fillable = ['rapat_id','instansi_id','kuota','jumlah_hadir'];

    public function rapat()
    {
        return $this->belongsTo(Rapat::class);
    }

    public function undangan()
    {
        return $this->hasMany(RapatUndangan::class, 'rapat_undangan_instansi_id');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }
}
