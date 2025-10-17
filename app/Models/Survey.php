<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'surveys';

    protected $fillable = ['kunjungan_id','user_id','rating','feedback'];

    // Relasi ke Kunjungan
    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }

    // Relasi ke User (tamu yang isi survey)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
