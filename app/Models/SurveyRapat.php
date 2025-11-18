<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SurveyRapat extends Model
{
    protected $table = 'survey_rapat';

    protected $fillable = [
        'judul', 'tipe', 'deskripsi', 'slug',
        'rapat_id', 'created_id','updated_id','deleted_id'
    ];

    public function getRouteKeyName()
    {
        return 'id'; // ⬅ binding by ID agar CRUD normal
    }

    public function respon()
    {
        return $this->hasMany(SurveyRapatRespon::class, 'survey_id');
    }

    public function rapat()
    {
        return $this->belongsToMany(Rapat::class, 'rapat_survey', 'survey_id', 'rapat_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($survey) {
            if (empty($survey->slug)) {
                $survey->slug = Str::slug($survey->judul) . '-' . Str::random(6);
            }
        });

        static::updating(function ($survey) {
            if (empty($survey->slug)) {
                $survey->slug = Str::slug($survey->judul) . '-' . Str::random(6);
            }
        });
    }
}
