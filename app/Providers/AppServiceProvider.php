<?php

namespace App\Providers;

use App\Models\Tamu;
use App\Models\User;
use App\Models\Rapat;
use App\Models\Bidang;
use App\Models\Kantor;
use App\Models\Survey;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Ruangan;
use App\Models\Instansi;
use App\Models\Kunjungan;
use App\Models\DaftarSurvey;
use App\Models\RapatUndangan;
use App\Observers\BaseObserver;
use App\Observers\UserObserver;
use App\Models\RapatUndanganInstansi;
use Illuminate\Support\ServiceProvider;
use App\Observers\RapatUndanganObserver;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;


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
        Tamu::observe(BaseObserver::class);
        Survey::observe(BaseObserver::class);
        Kunjungan::observe(BaseObserver::class);
        Rapat::observe(BaseObserver::class);
        RapatUndangan::observe(BaseObserver::class);
        DaftarSurvey::observe(BaseObserver::class);
        Instansi::observe(BaseObserver::class);
        RapatUndangan::observe(RapatUndanganObserver::class);
        Kantor::observe(BaseObserver::class);
        RapatUndanganInstansi::observe(BaseObserver::class);
        Ruangan::observe(BaseObserver::class);
        Validator::replacer('Password', function ($message, $attribute, $rule, $parameters) {
        return "Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan simbol.";
    });
    }
}
