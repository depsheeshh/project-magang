<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryLog extends Model
{
    use HasFactory;

    protected $table = 'history_logs';

    protected $fillable = [
        'user_id','action','table_name','record_id',
        'old_values','new_values','reason'
    ];

    // Relasi ke User (pelaku aksi)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
