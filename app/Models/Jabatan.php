<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jabatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jabatan';

    protected $fillable = [
        'nama_jabatan',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    // Relasi ke Pegawai
    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'jabatan_id');
    }

    // Relasi ke User yang membuat/mengupdate/menghapus
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_id');
    }
}
