<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rapat extends Model
{
    use SoftDeletes;

    protected $table = 'rapat';

    protected $fillable = [
        'judul',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'latitude',
        'longitude',
        'radius',
        'jumlah_tamu',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    protected $casts = [
        'waktu_mulai'   => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function undangan()
    {
        return $this->hasMany(RapatUndangan::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_id');
    }
}
