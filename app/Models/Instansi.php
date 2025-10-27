<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instansi extends Model
{

    protected $table = 'instansi';

    protected $fillable = [
        'nama_instansi',
        'lokasi',
        'created_id',
        'updated_id',
        'deleted_id',
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

