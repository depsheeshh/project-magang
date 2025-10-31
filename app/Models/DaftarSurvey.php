<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarSurvey extends Model
{
    protected $table = 'daftar_survey';
    protected $fillable = ['link_survey','is_active'];
}
