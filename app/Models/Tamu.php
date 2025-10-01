<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tamu extends Model
{
    use HasFactory;

    // Karena nama tabel bukan plural default (tamus), kita definisikan manual
    protected $table = 'tamu';

    protected $fillable = [
        'user_id',
        'nama',
        'instansi',
        'no_hp',
        'email',
        'alamat',
    ];

    /**
     * Relasi ke User (jika tamu punya akun login)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Kunjungan (satu tamu bisa punya banyak kunjungan)
     */
    public function kunjungan()
    {
        return $this->hasMany(Kunjungan::class);
    }
}
