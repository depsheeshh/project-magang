<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instansi extends Model
{
    use SoftDeletes;

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
}

