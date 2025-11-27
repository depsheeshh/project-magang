<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApelPagi extends Model
{
    protected $table = 'apel_pagi';

    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_masuk',
        'status',
        'telat_menit',
        'latitude',
        'longitude',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
