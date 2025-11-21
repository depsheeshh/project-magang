<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyRapatRespon extends Model
{
    protected $table = 'survey_rapat_respon';
    protected $fillable = ['survey_id','user_id','nama','instansi','jawaban'];

    protected $casts = [
        'jawaban' => 'array',
    ];

    public function survey() {
        return $this->belongsTo(SurveyRapat::class, 'survey_id');
    }

    public function rapat() {
        return $this->belongsTo(Rapat::class, 'rapat_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
