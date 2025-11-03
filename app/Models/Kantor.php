<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    protected $table = 'kantor';

    protected $fillable = [
        'nama_kantor',
        'alamat',
        'latitude',
        'longitude',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // 🔗 Relasi: satu kantor bisa punya banyak ruangan
    public function ruangan()
    {
        return $this->hasMany(Ruangan::class, 'id_kantor');
    }

    // 🔗 Relasi: satu kantor bisa dipakai di banyak rapat
    public function rapat()
    {
        return $this->hasMany(Rapat::class, 'lokasi', 'nama_kantor');
        // Catatan: lebih baik nanti rapat pakai id_kantor agar relasi lebih rapi
    }
}
