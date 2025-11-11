<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\RapatController;
use App\Http\Controllers\Admin\BidangController;
use App\Http\Controllers\Admin\KantorController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RapatCheckinController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\InstansiController;
use App\Http\Controllers\InstansiLookupController;
use App\Http\Controllers\Admin\HistoryLogController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\SurveyLinkController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\RapatCheckinEksternalController;
use App\Http\Controllers\Admin\RapatCheckinManualController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RuanganController as AdminRuanganController;
use App\Http\Controllers\Tamu\KunjunganController as TamuKunjunganController;
use App\Http\Controllers\Frontliner\RapatController as FrontlinerRapatController;
use App\Http\Controllers\Pegawai\KunjunganController as PegawaiKunjunganController;
use App\Http\Controllers\Admin\ChangePasswordController as AdminChangePasswordController;
use App\Http\Controllers\Frontliner\KunjunganController as FrontlinerKunjunganController;

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

    // Scan QR rapat eksternal
    Route::get('rapat/scan', fn () => view('tamu.rapat.scan'))->name('rapat.scan');

    // ✅ Rapat eksternal (PUBLIC, tanpa login)

    // Route verifikasi email check-in (HARUS di atas {token})
    Route::get('rapat/{rapat}/checkin/verify', [RapatCheckinEksternalController::class, 'verifyCheckin'])
        ->name('rapat.checkin.verify');

    // Form check-in via QR
    Route::get('rapat/{rapat}/checkin/{token}', [RapatCheckinEksternalController::class, 'showForm'])
        ->name('rapat.checkin.form');
    Route::post('rapat/{rapat}/checkin/{token}', [RapatCheckinEksternalController::class, 'checkin'])
        ->name('rapat.checkin');

    // Halaman pending (setelah submit form, sebelum verifikasi email)
    Route::get('rapat/checkin-pending', fn () => view('tamu.rapat.checkin_pending'))
        ->name('rapat.checkin.pending');

    // Halaman sukses (setelah verifikasi email berhasil)
    Route::get('rapat/checkin-success', fn () => view('tamu.rapat.checkin_success'))
        ->name('rapat.checkin.success');

    // Halaman gagal (link verifikasi invalid/expired)
    Route::get('rapat/checkin-failed', fn () => view('tamu.rapat.checkin_failed'))
        ->name('rapat.checkin.failed');
});

Route::middleware(['auth','role:admin|frontliner'])->group(function () {
    Route::get('/qrcode/tamu', [QrCodeController::class, 'index'])->name('qrcode.tamu');
    Route::get('/qrcode/tamu/pdf', [QrCodeController::class, 'pdf'])->name('qrcode.tamu.pdf');
});

Route::get('/survey/{kunjungan}/{token}', [SurveyController::class, 'form'])
    ->name('survey.form');
Route::post('/survey/{kunjungan}/{token}', [SurveyController::class, 'submit'])
    ->name('survey.submit');
Route::view('/survey/thanks', 'survey.thanks')->name('survey.thanks');

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
                Route::resource('/roles', RoleController::class);
                Route::resource('/permissions', PermissionController::class);

                // Tambahan master data
                Route::resource('/pegawai', PegawaiController::class);
                Route::resource('/bidang', BidangController::class);
                Route::resource('/jabatan', JabatanController::class);
                // CRUD rapat
                // Resource instansi
                Route::resource('instansi', InstansiController::class)->except(['create','edit']);
                Route::get('instansi/search', [InstansiController::class, 'search'])->name('instansi.search');

                Route::get('rapat/rekap', [RapatController::class, 'rekapRapat'])
                    ->name('rapat.rekap');
                Route::get('rapat/rekap/pdf', [RapatController::class, 'exportRekapRapatPdf'])
                    ->name('rapat.rekap.pdf');

                Route::resource('rapat', RapatController::class)->except(['create','edit']);
                Route::resource('kantor', KantorController::class)->except(['create','edit','show']);
                Route::resource('ruangan', AdminRuanganController::class)->except(['create','edit','show']);



                Route::get('rapat/{rapat}/export-pdf', [RapatController::class, 'exportKehadiranPdf'])
                ->name('rapat.export.pdf');
                Route::get('rapat/{rapat}/export/qr-pdf', [RapatController::class, 'exportQrPdf'])
                ->name('rapat.export.qrpdf');


                // Undangan rapat
                Route::post('rapat/{rapat}/invitation', [RapatController::class, 'storeInvitation'])
                    ->name('rapat.storeInvitation');
                Route::delete('rapat/{rapat}/invitation/{invitation}', [RapatController::class, 'destroyInvitation'])
                    ->name('rapat.destroyInvitation');

                Route::post('rapat/{rapat}/invitation-instansi', [RapatController::class, 'storeInvitationInstansi'])
                    ->name('rapat.storeInvitationInstansi');
                Route::post('rapat/{rapat}/invite-all-instansi', [RapatController::class, 'inviteAllInstansi'])
                    ->name('rapat.inviteAllInstansi');
                Route::patch('rapat/{rapat}/invitation-instansi/{undanganInstansi}/kuota',
                    [RapatController::class, 'updateKuotaInstansi'])
                    ->name('rapat.updateKuotaInstansi');
                Route::delete('rapat/{rapat}/invitation-instansi/{undanganInstansi}',
                [RapatController::class, 'destroyInvitationInstansi'])
                ->name('rapat.destroyInvitationInstansi');

                // Manajemen undangan (tetap manual karena bukan resource standar)
                Route::post('rapat/{rapat}/undangan', [RapatController::class, 'storeInvitation'])
                    ->name('rapat.undangan.store');
                Route::delete('rapat/{rapat}/undangan/{invitation}', [RapatController::class, 'destroyInvitation'])
                    ->name('rapat.undangan.destroy');
                Route::post('rapat/{rapat}/invite-all', [RapatController::class, 'inviteAll'])
                    ->name('rapat.inviteAll');
                Route::get('rapat/{rapat}/instansi/{undanganInstansi}/tamu',
                    [RapatController::class, 'detailTamuInstansi'])
                    ->name('rapat.detailTamuInstansi');
                Route::delete('rapat/{rapat}/instansi/{undanganInstansi}/tamu/{undangan}',
                [RapatController::class, 'destroyTamuInstansi'])
                ->name('rapat.destroyTamuInstansi');
                Route::patch('rapat/{rapat}/end', [RapatController::class, 'endRapat'])->name('rapat.end');

                // Checkin manual lewat admin
                Route::get('rapat/{rapat}/checkin-manual', [RapatCheckinManualController::class, 'index'])
                    ->name('rapat.peserta.index');
                Route::post('rapat/{rapat}/peserta', [RapatCheckinManualController::class, 'storePeserta'])
                    ->name('rapat.peserta.store');
                Route::put('rapat/{rapat}/peserta/{undangan}', [RapatCheckinManualController::class, 'updatePeserta'])
                    ->name('rapat.peserta.update');
                Route::patch('rapat/{rapat}/peserta/{undangan}/checkin', [RapatCheckinManualController::class, 'checkinPeserta'])
                    ->name('rapat.peserta.checkin');
                Route::patch('rapat/{rapat}/peserta/{undangan}/checkout', [RapatCheckinManualController::class, 'checkoutPeserta'])
                    ->name('rapat.peserta.checkout');

                // Export kehadiran
                Route::get('rapat/{rapat}/export-kehadiran', [RapatController::class, 'exportKehadiran'])
                    ->name('rapat.export.csv');

                // Export PDF
                Route::get('/laporan/cetak', [LaporanController::class, 'cetakPdf'])
                    ->name('laporan.cetak');
                Route::resource('/laporan', LaporanController::class);

                Route::resource('/history_logs', HistoryLogController::class)
                    ->only(['index','show']);

                // ✅ Tambahan menu Survey Tamu
                Route::get('/surveys', [SurveyController::class, 'index'])
                    ->name('surveys.index')
                    ->middleware('permission:surveys.view');

                // Tambahan survey link
                Route::get('/survey-links', [SurveyLinkController::class, 'index'])->name('survey_links.index');
                Route::post('/survey-links', [SurveyLinkController::class, 'store'])->name('survey_links.store');
                Route::patch('/survey-links/{id}/activate', [SurveyLinkController::class, 'activate'])->name('survey_links.activate');
                Route::patch('/survey-links/{id}/deactivate', [SurveyLinkController::class, 'deactivate'])->name('survey_links.deactivate');
                Route::delete('/survey-links/{id}', [SurveyLinkController::class, 'destroy'])->name('survey_links.destroy');


                Route::get('surveys/rekap', [SurveyController::class, 'rekap'])->name('surveys.rekap');
                Route::get('/surveys/{survey}', [SurveyController::class, 'show'])
                    ->name('surveys.show')
                    ->middleware('permission:surveys.view');

                Route::delete('/surveys/{survey}', [SurveyController::class, 'destroy'])
                    ->name('surveys.destroy')
                    ->middleware('permission:surveys.delete');
                Route::get('surveys/{survey}/fill', [SurveyController::class, 'fill'])->name('surveys.fill');
                Route::post('surveys/{survey}/fill', [SurveyController::class, 'fillSubmit'])->name('surveys.fill.submit');

                Route::get('surveys/rekap/export/{periode}', [SurveyController::class, 'exportPdf'])->name('surveys.export.pdf');


            });
            Route::middleware(['role:frontliner'])
            ->prefix('frontliner')
            ->name('frontliner.')
            ->group(function () {
                Route::get('/kunjungan', [FrontlinerKunjunganController::class, 'index'])
                    ->name('kunjungan.index');

                // Aksi frontliner terhadap kunjungan
                Route::post('/kunjungan/{kunjungan}/approve', [FrontlinerKunjunganController::class, 'approve'])
                    ->name('kunjungan.approve');
                Route::post('/kunjungan/{kunjungan}/reject', [FrontlinerKunjunganController::class, 'reject'])
                    ->name('kunjungan.reject');
                Route::post('/kunjungan/{kunjungan}/checkout', [FrontlinerKunjunganController::class, 'checkout'])
                    ->name('kunjungan.checkout');

                Route::get('/rapat', [FrontlinerRapatController::class, 'index'])
                    ->name('rapat.index');
                Route::get('/rapat/hari-ini', [FrontlinerRapatController::class, 'today'])
                    ->name('rapat.today');
                Route::get('/rapat/{rapat}', [FrontlinerRapatController::class, 'show'])
                    ->name('rapat.show');

            });
            Route::middleware(['role:pegawai'])
                ->prefix('pegawai')
                ->name('pegawai.')
                ->group(function () {

                    // ✅ Kunjungan (tidak diubah, tetap jalan)
                    Route::prefix('kunjungan')->name('kunjungan.')->group(function () {
                        Route::get('/riwayat', [PegawaiKunjunganController::class, 'riwayat'])->name('riwayat');
                        Route::get('/notifikasi', [PegawaiKunjunganController::class, 'notifikasi'])->name('notifikasi');
                        Route::post('/{kunjungan}/konfirmasi', [PegawaiKunjunganController::class, 'konfirmasi'])->name('konfirmasi');
                    });

                    // ✅ Agenda Rapat Saya (tetap sederhana, tidak bentrok)
                    Route::get('/rapat-saya', [RapatCheckinController::class, 'agendaPegawai'])->name('agenda.rapat');
                    Route::get('/rapat/scan', fn() => view('pegawai.rapat.scan'))->name('rapat.scan');

                    // Rekap Rapat (role Pegawai)
                    Route::get('rapat/rekap', [RapatController::class, 'rekapRapat'])->name('rapat.rekap');
                    Route::get('rapat/rekap/pdf', [RapatController::class, 'exportRekapRapatPdf'])->name('rapat.rekap.pdf');
                    // ✅ Show detail rapat (khusus pegawai)
                    Route::get('/rapat/{rapat}/detail', [RapatCheckinController::class, 'showPegawai'])
                        ->name('rapat.detail');

                    // ✅ Check-in / Checkout
                    Route::post('/rapat/{rapat}/checkin/{token}', [RapatCheckinController::class, 'checkinByRapatToken'])
                        ->name('rapat.checkin.token');
                    Route::post('/rapat/{rapat}/checkout', [RapatCheckinController::class, 'pegawaiCheckout'])
                        ->name('rapat.checkout');

                    // ✅ Rapat (CRUD + checkin manual + end rapat)

                    Route::resource('rapat', RapatController::class)->except(['create','edit']);
                    Route::get('rapat/{rapat}/export-pdf', [RapatController::class, 'exportKehadiranPdf'])->name('rapat.export.pdf');
                    Route::get('rapat/{rapat}/export/qr-pdf', [RapatController::class, 'exportQrPdf'])->name('rapat.export.qrpdf');
                    Route::get('rapat/{rapat}/checkin-manual', [RapatCheckinManualController::class, 'index'])->name('rapat.peserta.index');
                    Route::post('rapat/{rapat}/peserta', [RapatCheckinManualController::class, 'storePeserta'])->name('rapat.peserta.store');
                    Route::put('rapat/{rapat}/peserta/{undangan}', [RapatCheckinManualController::class, 'updatePeserta'])->name('rapat.peserta.update');
                    Route::patch('rapat/{rapat}/peserta/{undangan}/checkin', [RapatCheckinManualController::class, 'checkinPeserta'])->name('rapat.peserta.checkin');
                    Route::patch('rapat/{rapat}/peserta/{undangan}/checkout', [RapatCheckinManualController::class, 'checkoutPeserta'])->name('rapat.peserta.checkout');

                    // ✅ Undangan rapat internal/eksternal
                    Route::post('rapat/{rapat}/invitation', [RapatController::class, 'storeInvitation'])->name('rapat.storeInvitation');
                    Route::delete('rapat/{rapat}/invitation/{invitation}', [RapatController::class, 'destroyInvitation'])->name('rapat.destroyInvitation');
                    Route::post('rapat/{rapat}/invite-all', [RapatController::class, 'inviteAll'])->name('rapat.inviteAll');

                    Route::post('rapat/{rapat}/invitation-instansi', [RapatController::class, 'storeInvitationInstansi'])->name('rapat.storeInvitationInstansi');
                    Route::post('rapat/{rapat}/invite-all-instansi', [RapatController::class, 'inviteAllInstansi'])->name('rapat.inviteAllInstansi');
                    Route::patch('rapat/{rapat}/invitation-instansi/{undanganInstansi}/kuota', [RapatController::class, 'updateKuotaInstansi'])->name('rapat.updateKuotaInstansi');
                    Route::delete('rapat/{rapat}/invitation-instansi/{undanganInstansi}', [RapatController::class, 'destroyInvitationInstansi'])->name('rapat.destroyInvitationInstansi');

                    Route::get('rapat/{rapat}/instansi/{undanganInstansi}/tamu', [RapatController::class, 'detailTamuInstansi'])->name('rapat.detailTamuInstansi');
                    Route::delete('rapat/{rapat}/instansi/{undanganInstansi}/tamu/{undangan}', [RapatController::class, 'destroyTamuInstansi'])->name('rapat.destroyTamuInstansi');
                    Route::patch('rapat/{rapat}/end', [RapatController::class, 'endRapat'])->name('rapat.end');
                    Route::get('rapat/{rapat}/export-kehadiran', [RapatController::class, 'exportKehadiran'])->name('rapat.export.csv');

                    // ✅ Instansi (CRUD + search)
                    Route::resource('instansi', InstansiController::class)->except(['create','edit']);
                    Route::get('instansi/search', [InstansiController::class, 'search'])->name('instansi.search');

                    // ✅ Kantor (CRUD)
                    Route::resource('kantor', KantorController::class)->except(['create','edit','show']);

                    // ✅ Ruangan (CRUD)
                    Route::resource('ruangan', AdminRuanganController::class)->except(['create','edit','show']);

                });



        Route::prefix('tamu')->name('tamu.')->middleware('role:tamu')->group(function () {
            // Kunjungan
            Route::get('/kunjungan/create', [TamuKunjunganController::class, 'create'])->name('kunjungan.create');
            Route::post('/kunjungan', [TamuKunjunganController::class, 'store'])->name('kunjungan.store');
            Route::get('/kunjungan/status', [TamuKunjunganController::class, 'status'])->name('kunjungan.status');

            // Checkout tamu
            Route::post('/kunjungan/{id}/checkout', [TamuKunjunganController::class, 'checkout'])
                ->name('kunjungan.checkout');

            // Survey setelah checkout
            Route::post('/kunjungan/{id}/survey', [SurveyController::class, 'store'])
                ->name('kunjungan.survey.store');

            // Agenda rapat tamu (rapat eksternal yang sudah diikuti)
            Route::get('/rapat-saya', [RapatCheckinEksternalController::class, 'index'])->name('rapat.saya');
            Route::get('/rapat/{rapat}', [RapatCheckinEksternalController::class, 'show'])->name('rapat.show');
                // Checkout eksternal (setelah auto login)
            Route::post('rapat/{rapat}/checkout', [RapatCheckinEksternalController::class, 'checkout'])
                ->name('rapat.checkout');

            // API untuk autocomplete: hanya instansi yang dibuat oleh admin
            Route::get('api/instansi/admin', [InstansiLookupController::class, 'listAdminInstansi'])
                ->name('api.instansi.admin');
        });
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

            // Route::patch('/admin/rapat/{rapat}/end', [RapatController::class, 'endRapat'])
            // ->name('rapat.end')
            // ->middleware(['role:admin']);
    });

    Route::get('/notifikasi', [NotificationController::class, 'index']);
    Route::patch('/notifikasi/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifikasi/{id}', [NotificationController::class, 'destroy']);
    // Route::delete('/notifikasi/clear', [NotificationController::class, 'clearAll']);

    Route::get('/ruangan/update-dipakai', [RuanganController::class, 'updateDipakai'])
    ->name('ruangan.updateDipakai');


    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
