<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    protected $fillable = [
        'nama_ruangan',
        'id_kantor',
        'kapasitas_maksimal',
        'dipakai',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    protected $casts = [
        'dipakai' => 'boolean',
    ];

    /**
     * Relasi ke kantor
     */
    public function kantor()
    {
        return $this->belongsTo(Kantor::class, 'id_kantor');
    }

    /**
     * Relasi ke user yang membuat
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_id');
    }

    /**
     * Relasi ke user yang mengupdate
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_id');
    }

    /**
     * Relasi ke user yang menghapus (soft delete)
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_id');
    }
}
