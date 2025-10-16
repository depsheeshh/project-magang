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
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Frontliner\KunjunganController as FrontlinerKunjunganController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Pegawai\KunjunganController as PegawaiKunjunganController;
use App\Http\Controllers\Admin\ChangePasswordController as AdminChangePasswordController;
use App\Http\Controllers\Tamu\KunjunganController as TamuKunjunganController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\NotificationController;

// Landing page publik
Route::get('/', fn () => view('home'))->name('home');

// Flow tamu
Route::prefix('tamu')->name('tamu.')->group(function () {
    // Halaman scan QR (opsional, kalau masih dipakai)
    Route::get('/scan', fn () => view('tamu.scan'))->name('scan');

    // Callback setelah scan (opsional, kalau masih dipakai)
    Route::post('/scan-success', function (\Illuminate\Http\Request $request) {
        $request->session()->put('tamu_scanned', true);
        $request->session()->put('url.intended', route('tamu.form'));
        return response()->json(['status' => 'ok']);
    })->name('scan.success');

    // Form tamu langsung tanpa auth
    Route::get('/form', [TamuController::class, 'create'])->name('form');
    Route::post('/form', [TamuController::class, 'store'])->name('store');

    // Halaman terima kasih
    Route::get('/thanks', fn () => view('tamu.thanks'))->name('thanks');

    // AJAX filter pegawai by bidang (tidak perlu auth, karena dipakai di form publik)
    Route::get('/get-pegawai/{bidangId}', [TamuController::class, 'getPegawaiByBidang'])
        ->name('getPegawai');
});

Route::middleware(['auth','role:admin|frontliner'])->group(function () {
    Route::get('/qrcode/tamu', [QrCodeController::class, 'index'])->name('qrcode.tamu');
    Route::get('/qrcode/tamu/pdf', [QrCodeController::class, 'pdf'])->name('qrcode.tamu.pdf');
});


// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    // OAuth Google
    Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

    // Forgot & Reset Password
    Route::get('/password/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Authenticated routes
Route::middleware('auth',)->group(function () {

    // Verifikasi Email
    Route::get('/verify-email', [VerificationController::class, 'showForm'])->name('verification.form');
    Route::post('/verify-email', [VerificationController::class, 'verify'])->name('verification.verify');
     Route::post('/verify-email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

     // Semua route lain WAJIB verified
    Route::middleware('ensure.verified')->group(function() {
        // Dashboard untuk admin, frontliner, pegawai
        // Dashboard tunggal untuk semua role
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware('verified','role:admin|frontliner|pegawai|tamu')
            ->name('dashboard.index');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');


        // Admin resource management
        Route::middleware('role:admin')
            ->prefix('admin')
            ->name('admin.')
            ->group(function () {
                Route::resource('/users', AdminUserController::class);
                // // Tambahan fitur password
                // Route::post('/users/{user}/change-password', [AdminUserController::class, 'changePassword'])
                //     ->name('users.change-password');
                // Route::post('/users/{user}/send-reset-link', [AdminUserController::class, 'sendResetLink'])
                //     ->name('users.send-reset-link');
                Route::resource('/roles', RoleController::class);
                Route::resource('/permissions', PermissionController::class);
                // Tambahan master data
                Route::resource('/pegawai', PegawaiController::class);
                Route::resource('/bidang', BidangController::class);
                // Export PDF
                Route::get('/laporan/cetak', [LaporanController::class, 'cetakPdf'])
                    ->name('laporan.cetak');
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
                // Route::get('/tamu-menunggu', [FrontlinerKunjunganController::class, 'menunggu'])
                //     ->name('tamu.menunggu');

                // Halaman daftar semua kunjungan (bisa difilter ?status=menunggu, dll)
                Route::get('/kunjungan', [FrontlinerKunjunganController::class, 'index'])
                    ->name('kunjungan.index');

                // Aksi frontliner terhadap kunjungan
                Route::post('/kunjungan/{kunjungan}/approve', [FrontlinerKunjunganController::class, 'approve'])
                    ->name('kunjungan.approve');
                Route::post('/kunjungan/{kunjungan}/reject', [FrontlinerKunjunganController::class, 'reject'])
                    ->name('kunjungan.reject');
                Route::post('/kunjungan/{kunjungan}/checkout', [FrontlinerKunjunganController::class, 'checkout'])
                    ->name('kunjungan.checkout');

            });
            Route::middleware(['role:pegawai'])
                ->prefix('pegawai')
                ->name('pegawai.')
                ->group(function () {
                    Route::prefix('kunjungan')->name('kunjungan.')->group(function () {
                        Route::get('/riwayat', [PegawaiKunjunganController::class, 'riwayat'])->name('riwayat');
                        Route::get('/notifikasi', [PegawaiKunjunganController::class, 'notifikasi'])->name('notifikasi');
                        Route::post('/{kunjungan}/konfirmasi', [PegawaiKunjunganController::class, 'konfirmasi'])->name('konfirmasi');
                });
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

        // Untuk tamu: cek status kunjungan terakhir
        Route::middleware(['auth','role:tamu'])->get('/api/tamu/notifikasi', [TamuController::class, 'checkNotification']);

        // Untuk frontliner: cek jumlah tamu menunggu
        Route::middleware(['auth','role:frontliner'])->get('/api/frontliner/notifikasi', [FrontlinerKunjunganController::class, 'checkNotification']);

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
            [FrontlinerKunjunganController::class, 'checkout'])
            ->middleware(['auth','role:frontliner'])
            ->name('kunjungan.checkout');
    });

    Route::get('/notifikasi', [NotificationController::class, 'index']);
    Route::patch('/notifikasi/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifikasi/{id}', [NotificationController::class, 'destroy']);
    Route::delete('/notifikasi/clear', [NotificationController::class, 'clearAll']);


    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
