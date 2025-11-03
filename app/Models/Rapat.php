<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rapat extends Model
{

    protected $table = 'rapat';

    protected $fillable = [
        'judul',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'ruangan_id',
        'latitude',
        'jenis_rapat',
        'longitude',
        'radius',
        'jumlah_tamu',
        'qr_token',
        'qr_token_hash',
        'created_id',
        'updated_id',
        'deleted_id',
        'status',
    ];

    protected $casts = [
        'waktu_mulai'   => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function undangan()
    {
        return $this->hasMany(RapatUndangan::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_id');
    }
}
