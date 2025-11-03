<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Pegawai;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'verification_code','verification_expires_at',
        'email_verified_at', // pastikan ada
        'instansi_id',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verification_expires_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
        ];
    }

    public function sendPasswordResetNotification($token)
{
    $url = url(route('password.reset', [
        'token' => $token,
        'email' => $this->email,
    ], false));

    $this->notify(new \App\Notifications\ResetPasswordNotification($token));
}

    // protected static function booted()
    // {
    //     static::created(function ($user) {
    //         $user->assignRole('tamu');
    //     });
    // }

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'user_id');
    }

    public function tamu()
    {
        return $this->hasOne(Tamu::class, 'user_id');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

}
