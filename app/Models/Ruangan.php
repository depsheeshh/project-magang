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

    public function rapat() {
        return $this->hasMany(Rapat::class);
    }

    /**
     * Cek apakah ruangan tersedia untuk periode tertentu
     *
     * @param  \Carbon\Carbon|string  $start
     * @param  \Carbon\Carbon|string  $end
     * @param  int|null $excludeRapatId
     * @return bool
     */
    public function isAvailable($start, $end, $excludeRapatId = null)
    {
        return !$this->rapat()
            ->when($excludeRapatId, fn($q) => $q->where('id','!=',$excludeRapatId))
            ->where('status','!=','selesai')
            ->where(function($q) use ($start,$end) {
                $q->whereBetween('waktu_mulai', [$start,$end])
                  ->orWhereBetween('waktu_selesai', [$start,$end])
                  ->orWhere(function($q2) use ($start,$end) {
                      $q2->where('waktu_mulai','<=',$start)
                         ->where('waktu_selesai','>=',$end);
                  });
            })
            ->exists();
    }

    // public function getDipakaiAttribute()
    // {
    //     return $this->rapat()
    //         ->where('waktu_mulai', '<=', now())
    //         ->where('waktu_selesai', '>=', now())
    //         ->where('status', '!=', 'dibatalkan')
    //         ->exists();
    // }


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
