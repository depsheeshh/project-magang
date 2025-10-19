<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RapatUndangan extends Model
{
    protected $table = 'rapat_undangan';

    protected $fillable = [
        'rapat_id','user_id','status_kehadiran',
        'checkin_time','checkin_lat','checkin_lng'
    ];

    public function rapat()
    {
        return $this->belongsTo(Rapat::class, 'rapat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
