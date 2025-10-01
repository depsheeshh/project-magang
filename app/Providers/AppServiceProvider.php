<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\Kunjungan;
use App\Observers\BaseObserver;
use App\Observers\UserObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        User::observe(BaseObserver::class);
        Pegawai::observe(BaseObserver::class);
        Bidang::observe(BaseObserver::class);
        Jabatan::observe(BaseObserver::class);
        Kunjungan::observe(BaseObserver::class);
        Validator::replacer('Password', function ($message, $attribute, $rule, $parameters) {
        return "Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan simbol.";
    });
    }
}
