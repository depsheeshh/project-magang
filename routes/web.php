<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\BidangController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\HistoryLogController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Frontliner\KunjunganController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Pegawai\KunjunganController as PegawaiKunjunganController;
use App\Http\Controllers\Admin\ChangePasswordController as AdminChangePasswordController;
use App\Http\Controllers\Tamu\DashboardController as TamuDashboardController;
use App\Http\Controllers\Tamu\KunjunganController as TamuKunjunganController;

// Landing page publik
Route::get('/', fn () => view('home'))->name('home');

// Flow tamu
Route::prefix('tamu')->name('tamu.')->group(function () {
    Route::get('/scan', fn () => view('tamu.scan'))->name('scan');

    Route::post('/scan-success', function (\Illuminate\Http\Request $request) {
        $request->session()->put('tamu_scanned', true);
        $request->session()->put('url.intended', route('tamu.form'));
        return response()->json(['status' => 'ok']);
    })->name('scan.success');

    Route::get('/form', [TamuController::class, 'create'])
        ->middleware(['auth','tamu.scanned'])
        ->name('form');

    Route::post('/form', [TamuController::class, 'store'])
        ->middleware(['auth','tamu.scanned'])
        ->name('store');

    Route::get('/kunjungan/status', [TamuController::class, 'status'])->name('kunjungan.status');

    Route::get('/thanks', fn () => view('tamu.thanks'))->name('thanks');



    // AJAX filter pegawai by bidang
    Route::get('/get-pegawai/{bidangId}', [TamuController::class, 'getPegawaiByBidang'])
        ->middleware('auth')
        ->name('getPegawai');
});

Route::get('/qrcode/tamu', function () {
    return view('qrcode.tamu');
})->middleware(['auth','role:admin|frontliner'])->name('qrcode.tamu');


// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Forgot & Reset Password
    Route::get('/password/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Dashboard untuk admin, frontliner, pegawai
    // Dashboard tunggal untuk semua role
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin|frontliner|pegawai|tamu')
        ->name('dashboard.index');

    // Admin resource management
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('/users', AdminUserController::class);
            Route::resource('/roles', RoleController::class);
            Route::resource('/permissions', PermissionController::class);
            // Tambahan master data
            Route::resource('/pegawai', PegawaiController::class);
            Route::resource('/bidang', BidangController::class);
            Route::resource('/laporan', LaporanController::class);
            Route::resource('/jabatan', JabatanController::class);
            Route::resource('/history_logs', HistoryLogController::class)
            ->only(['index','show']);
        });
        Route::middleware(['role:frontliner'])
        ->prefix('frontliner')
        ->name('frontliner.')
        ->group(function () {
            // Halaman khusus tamu menunggu
            // Route::get('/tamu-menunggu', [KunjunganController::class, 'menunggu'])
            //     ->name('tamu.menunggu');

            // Halaman daftar semua kunjungan (bisa difilter ?status=menunggu, dll)
            Route::get('/kunjungan', [KunjunganController::class, 'index'])
                ->name('kunjungan.index');

            // Aksi frontliner terhadap kunjungan
            Route::post('/kunjungan/{kunjungan}/approve', [KunjunganController::class, 'approve'])
                ->name('kunjungan.approve');
            Route::post('/kunjungan/{kunjungan}/reject', [KunjunganController::class, 'reject'])
                ->name('kunjungan.reject');
            Route::post('/kunjungan/{kunjungan}/checkout', [KunjunganController::class, 'checkout'])
                ->name('kunjungan.checkout');

        });
        Route::middleware(['auth','role:pegawai'])
            ->prefix('pegawai/kunjungan')
            ->name('pegawai.kunjungan.')
            ->group(function () {
                Route::get('/riwayat', [PegawaiKunjunganController::class, 'riwayat'])->name('riwayat');
                Route::get('/notifikasi', [PegawaiKunjunganController::class, 'notifikasi'])->name('notifikasi');
            });


    Route::prefix('tamu')->name('tamu.')->middleware('role:tamu')->group(function () {
        Route::get('/kunjungan/create', [TamuKunjunganController::class, 'create'])->name('kunjungan.create');
        Route::post('/kunjungan', [TamuKunjunganController::class, 'store'])->name('kunjungan.store');
        Route::get('/kunjungan/status', [TamuKunjunganController::class, 'status'])->name('kunjungan.status');
    });

    // User biasa (role:user)
    // Route::middleware('role:user')->group(function () {
    //     Route::get('/user/dashboard', fn () => view('dashboard.user'))->name('user.dashboard');
    // });

    // // Change Password umum (non-admin)
    // Route::get('/password/change', [ChangePasswordController::class, 'show'])->name('password.change');
    // Route::post('/password/change', [ChangePasswordController::class, 'update'])->name('password.change.update');

    Route::middleware(['role:admin|frontliner|pegawai|tamu'])->group(function () {
        Route::get('/password/change', [AdminChangePasswordController::class, 'edit'])->name('password.change');
        Route::post('/password/change', [AdminChangePasswordController::class, 'update'])->name('password.change.update');
    });

    // Tamu checkout sendiri
    Route::post('/tamu/kunjungan/{kunjungan}/checkout',
        [TamuKunjunganController::class, 'checkout'])
        ->middleware(['auth','role:tamu'])
        ->name('tamu.kunjungan.checkout');

    // Frontliner checkout tamu
    Route::post('/kunjungan/{kunjungan}/checkout',
        [KunjunganController::class, 'checkout'])
        ->middleware(['auth','role:frontliner'])
        ->name('kunjungan.checkout');



    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
