<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungan';

    protected $fillable = [
        'tamu_id',
        'pegawai_id',
        'frontliner_id',
        'keperluan',
        'status',
        'waktu_masuk',
        'waktu_keluar',
        'alasan_penolakan', // tambahkan ini
        'catatan',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    /**
     * Cast kolom datetime ke Carbon otomatis
     */
    protected $casts = [
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
    ];

    const STATUS = ['menunggu','sedang_bertamu','selesai','ditolak'];

    /**
     * Relasi ke Tamu (bukan langsung ke User)
     */
    public function tamu()
    {
        return $this->belongsTo(Tamu::class, 'tamu_id');
    }

    /**
     * Relasi ke Pegawai
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    /**
     * Relasi ke User (frontliner yang melayani)
     */
    public function frontliner()
    {
        return $this->belongsTo(User::class, 'frontliner_id');
    }

    /**
     * Accessor untuk format tanggal masuk
     */
    public function getFormattedWaktuMasukAttribute()
    {
        return $this->waktu_masuk
            ? $this->waktu_masuk->format('d/m/Y H:i')
            : '-';
    }

    /**
     * Accessor untuk format tanggal keluar
     */
    public function getFormattedWaktuKeluarAttribute()
    {
        return $this->waktu_keluar
            ? $this->waktu_keluar->format('d/m/Y H:i')
            : '-';
    }
}
