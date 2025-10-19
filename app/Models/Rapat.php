<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rapat extends Model
{
    protected $table = 'rapat';

    protected $fillable = [
        'judul','waktu_mulai','waktu_selesai',
        'lokasi','latitude','longitude','radius','created_by'
    ];

    public function undangan()
    {
        return $this->hasMany(RapatUndangan::class, 'rapat_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
