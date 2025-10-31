<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Notifications\SurveyCreatedNotification;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'surveys';

    protected $fillable = ['kunjungan_id','user_id','rating','feedback','link',
    'kemudahan_registrasi',
    'keramahan_pelayanan',
    'waktu_tunggu',
    'saran','created_id','updated_id','deleted_id'];

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

    protected static function booted()
    {
        static::created(function ($survey) {
            User::role('admin')->each(function ($admin) use ($survey) {
                $admin->notify(new SurveyCreatedNotification($survey));
            });
        });
    }
}
