<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\InstansiCreatedNotification;

class Instansi extends Model
{

    protected $table = 'instansi';

    protected $fillable = [
        'nama_instansi',
        'lokasi',
        'alias',
        'jenis',
        'is_active',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    public function undangan()
    {
        return $this->hasMany(RapatUndangan::class);
    }

    public function undanganRapat()
    {
        return $this->hasMany(RapatUndanganInstansi::class);
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_id');
    }

    protected static function booted()
    {
        static::created(function ($instansi) {
            // Ambil semua user dengan role admin
            User::role('admin')->each(function ($admin) use ($instansi) {
                $admin->notify(new InstansiCreatedNotification($instansi));
            });
        });
    }
}

