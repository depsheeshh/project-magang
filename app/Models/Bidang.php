<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bidang extends Model
{
    use HasFactory;

    protected $table = 'bidang';

    protected $fillable = [
        'nama_bidang',
        'deskripsi', // opsional, kalau mau simpan keterangan bidang
    ];

    // Relasi ke Pegawai (satu bidang punya banyak pegawai)
    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'bidang_id');
    }
}
