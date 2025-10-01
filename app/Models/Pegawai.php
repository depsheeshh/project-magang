<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = [
        'user_id',      // relasi ke tabel users
        'nama',
        'nip',          // opsional: nomor induk pegawai
        'jabatan_id',   // relasi ke tabel jabatan
        'bidang_id',    // relasi ke tabel bidang/unit kerja
        'telepon',
        'email',
    ];

    // Relasi ke User (akun login)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Bidang/Unit Kerja
    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    // Relasi ke Kunjungan (pegawai sebagai tujuan kunjungan)
    public function kunjungan()
    {
        return $this->hasMany(Kunjungan::class, 'pegawai_id');
    }
}
