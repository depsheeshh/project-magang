<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistoryLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'history_logs';

    protected $fillable = [
        'user_id','action','table_name','record_id',
        'old_values','new_values','reason','created_id',
        'updated_id',
        'deleted_id',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];


    // Relasi ke User (pelaku aksi)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
