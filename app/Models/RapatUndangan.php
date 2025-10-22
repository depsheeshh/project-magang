<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RapatUndangan extends Model
{
    use SoftDeletes;

    protected $table = 'rapat_undangan';

    protected $fillable = [
        'rapat_id',
        'user_id',
        'instansi_id',
        'jumlah_peserta',
        'status_kehadiran',
        'checked_in_at',
        'checkin_latitude',
        'checkin_longitude',
        'checkin_distance',
        'checkin_token',
        'checkin_token_hash',
        'qr_scanned_at',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'qr_scanned_at' => 'datetime',
    ];

    public function rapat()
    {
        return $this->belongsTo(Rapat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }
}
