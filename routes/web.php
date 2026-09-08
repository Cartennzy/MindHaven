<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTH CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| FRONTEND HOME CONTROLLER
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\AiChatbotController;

/*
|--------------------------------------------------------------------------
| FRONTEND PASIEN CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Frontend\Pasien\DashboardController as PasienDashboardController;
use App\Http\Controllers\Frontend\Pasien\KonsultasiController as PasienKonsultasiController;
use App\Http\Controllers\Frontend\Pasien\PembayaranController as PasienPembayaranController;
use App\Http\Controllers\Frontend\Pasien\ProfileController as PasienProfileController;
use App\Http\Controllers\Frontend\Pasien\HasilKonsultasiController as PasienHasilKonsultasiController;
use App\Http\Controllers\Frontend\Pasien\RujukanPsikiaterController as PasienRujukanPsikiaterController;
use App\Http\Controllers\Frontend\Pasien\MeditasiController as PasienMeditasiController;
use App\Http\Controllers\Frontend\Pasien\SelfAssessmentController;
use App\Http\Controllers\Frontend\Pasien\TestimonialsController;
use App\Http\Controllers\Frontend\ArtikelController;
use App\Http\Controllers\Frontend\PsikologRegistrationController;
use App\Http\Controllers\Frontend\KonsultasiMessageController;

/*
|--------------------------------------------------------------------------
| FRONTEND PSIKOLOG CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Frontend\Psikolog\DashboardController as PsikologDashboardController;
use App\Http\Controllers\Frontend\Psikolog\KonsultasiController as PsikologKonsultasiController;
use App\Http\Controllers\Frontend\Psikolog\PasienController as PsikologPasienController;
use App\Http\Controllers\Frontend\Psikolog\PsikiaterController as PsikologPsikiaterController;
use App\Http\Controllers\Frontend\Psikolog\RumahSakitController as PsikologRumahSakitController;
use App\Http\Controllers\Frontend\Psikolog\RujukanPsikiaterController as PsikologRujukanPsikiaterController;
use App\Http\Controllers\Frontend\Psikolog\ProfileController;

/*
|--------------------------------------------------------------------------
| BACKEND ADMIN CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\DashboardController as AdminDashboardController;
use App\Http\Controllers\Backend\KonsultasiController as AdminKonsultasiController;
use App\Http\Controllers\Backend\PasienController as AdminPasienController;
use App\Http\Controllers\Backend\PsikologController as AdminPsikologController;
use App\Http\Controllers\Backend\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Backend\ArtikelController as AdminArtikelController;
use App\Http\Controllers\Backend\MeditasiController as AdminMeditasiController;
use App\Http\Controllers\Backend\RumahSakitController as AdminRumahSakitController;
use App\Http\Controllers\Backend\PsikiaterController as AdminPsikiaterController;
use App\Http\Controllers\Backend\RujukanPsikiaterController as AdminRujukanPsikiaterController;
use App\Http\Controllers\Backend\LaporanController as AdminLaporanController;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/home', [HomeController::class, 'index'])
    ->name('home.index');

Route::get('/ai-chatbot', [AiChatbotController::class, 'index'])
    ->name('chatbot.index');

Route::post('/ai-chatbot/new', [AiChatbotController::class, 'newChat'])
    ->name('chatbot.new');

Route::post('/ai-chatbot/send', [AiChatbotController::class, 'send'])
    ->name('chatbot.send');

Route::delete('/ai-chatbot/session/{session}', [AiChatbotController::class, 'clearSession'])
    ->name('chatbot.session.delete');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'index'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');

Route::get('/register', [RegisterController::class, 'index'])
    ->name('register');

Route::post('/register', [RegisterController::class, 'register'])
    ->name('register.process');

Route::get('/backend/login', [LoginController::class, 'backendLogin'])
    ->name('backend.login');

Route::post('/backend/login', [LoginController::class, 'backendLoginProcess'])
    ->name('backend.login.process');

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/daftar-psikolog', [PsikologRegistrationController::class, 'index'])
    ->name('psikolog.register');

Route::post('/daftar-psikolog', [PsikologRegistrationController::class, 'store'])
    ->name('psikolog.register.store');

Route::get('/daftar-psikolog/berhasil', [PsikologRegistrationController::class, 'success'])
    ->name('psikolog.register.success');

/*
|--------------------------------------------------------------------------
| PASIEN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:pasien'])
    ->prefix('pasien')
    ->name('pasien.')
    ->group(function () {

        Route::get('/dashboard', [PasienDashboardController::class, 'index'])
            ->name('dashboard');

        // ROUTE TESTIMONIALS PASIEN SUDAH SINKRON DI SINI
        Route::get('/testimonials', [TestimonialsController::class, 'index'])
            ->name('testimonials.index');

        Route::post('/testimonials', [TestimonialsController::class, 'store'])
            ->name('testimonials.store');

        Route::get('/profile', [PasienProfileController::class, 'index'])
            ->name('profile.index');

        Route::put('/profile', [PasienProfileController::class, 'update'])
            ->name('profile.update');

        Route::get('/hasil-konsultasi', [PasienHasilKonsultasiController::class, 'index'])
            ->name('hasil-konsultasi.index');

        Route::get('/hasil-konsultasi/{konsultasi}', [PasienHasilKonsultasiController::class, 'show'])
            ->name('hasil-konsultasi.show');

        Route::get('/konsultasi/{konsultasi}/hasil', [PasienKonsultasiController::class, 'hasil'])
            ->name('konsultasi.hasil');

        Route::get('/konsultasi/psikolog/{psikolog}/detail', [PasienKonsultasiController::class, 'detailPsikolog'])
            ->name('konsultasi.detail-psikolog');

        Route::get('/konsultasi/{konsultasi}/metode', [PasienKonsultasiController::class, 'metode'])
            ->name('konsultasi.metode');

        Route::put('/konsultasi/{konsultasi}/metode', [PasienKonsultasiController::class, 'storeMetode'])
            ->name('konsultasi.store-metode');

        Route::get('/konsultasi/{konsultasi}/sesi', [PasienKonsultasiController::class, 'sesi'])
            ->name('konsultasi.sesi');

        Route::post('/hasil-konsultasi/{id}/rate', [PasienHasilKonsultasiController::class, 'rate'])
            ->name('hasil-konsultasi.rate');

        Route::post('/konsultasi/{konsultasi}/messages', [KonsultasiMessageController::class, 'store'])
            ->name('konsultasi.messages.store');

        Route::get('/konsultasi/{konsultasi}/messages', [KonsultasiMessageController::class, 'fetch'])
            ->name('konsultasi.messages.fetch');

        Route::resource('konsultasi', PasienKonsultasiController::class)
            ->only(['index', 'create', 'store', 'show']);

        Route::resource('rujukan-psikiater', PasienRujukanPsikiaterController::class)
            ->only(['index', 'show']);

        Route::resource('meditasi', PasienMeditasiController::class)
            ->only(['index', 'show']);

        /*
        |--------------------------------------------------------------------------
        | SUB-ROUTE NEW FEATURE: SELF-ASSESSMENT GRATIS
        |--------------------------------------------------------------------------
        */
        Route::get('/self-assessment', [SelfAssessmentController::class, 'index'])
            ->name('self-assessment.index');
            
        Route::get('/self-assessment/tes/{slug}', [SelfAssessmentController::class, 'show'])
            ->name('self-assessment.show');
            
        Route::post('/self-assessment/tes/{slug}', [SelfAssessmentController::class, 'store'])
            ->name('self-assessment.store');
            
        Route::get('/self-assessment/hasil/{id_hasil}', [SelfAssessmentController::class, 'result'])
            ->name('self-assessment.result');

        Route::get('/self-assessment/hasil/{id_hasil}/pdf', [SelfAssessmentController::class, 'exportPdf'])
            ->name('self-assessment.pdf');

        Route::post('/pembayaran/{pembayaran}/finish', [PasienPembayaranController::class, 'finish'])
            ->name('pembayaran.finish');

        Route::get('/pembayaran/{pembayaran}/upload-bukti', [PasienPembayaranController::class, 'uploadBukti'])
            ->name('pembayaran.upload-bukti');

        Route::post('/pembayaran/{pembayaran}/upload-bukti', [PasienPembayaranController::class, 'storeBukti'])
            ->name('pembayaran.store-bukti');

        Route::resource('pembayaran', PasienPembayaranController::class)
            ->only(['index', 'create', 'store', 'show']);

        Route::get('/jadwal-psikolog/{id}', [PasienKonsultasiController::class, 'getJadwalPsikolog'])
            ->name('konsultasi.jadwal-psikolog');
    });

Route::post('/midtrans/notification', [PasienPembayaranController::class, 'notification'])
    ->name('midtrans.notification');

/*
|--------------------------------------------------------------------------
| PSIKOLOG ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:psikolog'])
    ->prefix('psikolog')
    ->name('psikolog.')
    ->group(function () {

        Route::get('/dashboard', [PsikologDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/dashboard/pendapatan', [PsikologDashboardController::class, 'pendapatan'])
            ->name('pendapatan.index');

        Route::get('/profile', [PsikologDashboardController::class, 'profile'])
            ->name('profile');

        Route::put('/profile/password', [PsikologDashboardController::class, 'updatePassword'])
            ->name('profile.password');

        Route::get('/hasil-konsultasi', [PsikologKonsultasiController::class, 'hasilIndex'])
            ->name('hasil-konsultasi.index');

        Route::get('/konsultasi/{konsultasi}/hasil', [PsikologKonsultasiController::class, 'hasil'])
            ->name('konsultasi.hasil');

        Route::patch('/konsultasi/{konsultasi}/accept', [PsikologKonsultasiController::class, 'accept'])
            ->name('konsultasi.accept');

        Route::get('/konsultasi/{konsultasi}/sesi', [PsikologKonsultasiController::class, 'sesi'])
            ->name('konsultasi.sesi');

        Route::post('/konsultasi/{konsultasi}/messages', [KonsultasiMessageController::class, 'store'])
            ->name('konsultasi.messages.store');

        Route::get('/konsultasi/{konsultasi}/messages', [KonsultasiMessageController::class, 'fetch'])
            ->name('konsultasi.messages.fetch');

        Route::resource('konsultasi', PsikologKonsultasiController::class)
            ->only(['index', 'show', 'update']);

        Route::resource('pasien', PsikologPasienController::class)
            ->only(['index', 'show']);

        Route::resource('psikiater', PsikologPsikiaterController::class)
            ->only(['index', 'show']);

        Route::resource('rumah-sakit', PsikologRumahSakitController::class)
            ->only(['index', 'show']);

        Route::resource('rujukan-psikiater', PsikologRujukanPsikiaterController::class)
            ->only(['index', 'create', 'store', 'show', 'update']);

        Route::resource('rujukan', PsikologRujukanPsikiaterController::class)
            ->only(['index', 'create', 'store', 'show', 'update']);

        Route::get('/profile', [ProfileController::class, 'index'])
            ->name('profile');

        Route::put('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
            ->name('profile.password');
    });

/*
|--------------------------------------------------------------------------
| ADMIN / BACKEND ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('admin', AdminController::class);

        Route::resource('pasien', AdminPasienController::class)
            ->except(['create', 'store']);

        Route::resource('psikolog', AdminPsikologController::class)
            ->except(['create', 'store']);

        Route::put('/psikolog/{psikolog}/verifikasi', [AdminPsikologController::class, 'verifikasi'])
            ->name('psikolog.verifikasi');

        Route::resource('konsultasi', AdminKonsultasiController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::get('/pembayaran/{pembayaran}/verifikasi', [AdminPembayaranController::class, 'verifikasi'])
            ->name('pembayaran.verifikasi');

        Route::put('/pembayaran/{pembayaran}/verifikasi', [AdminPembayaranController::class, 'updateVerifikasi'])
            ->name('pembayaran.update-verifikasi');

        Route::resource('pembayaran', AdminPembayaranController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::resource('artikel', AdminArtikelController::class);

        Route::resource('meditasi', AdminMeditasiController::class);

        Route::resource('rumah-sakit', AdminRumahSakitController::class);

        Route::resource('psikiater', AdminPsikiaterController::class);

        Route::resource('rujukan', AdminRujukanPsikiaterController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::resource('rujukan-psikiater', AdminRujukanPsikiaterController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::get('/laporan', [AdminLaporanController::class, 'index'])
            ->name('laporan.index');

    });

/*
|--------------------------------------------------------------------------
| PUBLIC ARTIKEL
|--------------------------------------------------------------------------
*/

Route::resource('artikel', ArtikelController::class)
    ->only(['index', 'show']);