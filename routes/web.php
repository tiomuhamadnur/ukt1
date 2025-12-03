<?php

use Livewire\Volt\Volt;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\TimController;
use App\Http\Controllers\admin\CutiController;
use App\Http\Controllers\admin\KotaController;
use App\Http\Controllers\admin\UnitController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\PulauController;
use App\Http\Controllers\admin\SeksiController;
use App\Http\Controllers\admin\GenderController;
use App\Http\Controllers\admin\AbsensiController;
use App\Http\Controllers\admin\JabatanController;
use App\Http\Controllers\admin\KinerjaController;
use App\Http\Controllers\admin\KegiatanController;
use App\Http\Controllers\admin\ProvinsiController;
use App\Http\Controllers\admin\UserTypeController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\JenisCutiController;
use App\Http\Controllers\admin\KecamatanController;
use App\Http\Controllers\admin\KelurahanController;
use App\Http\Controllers\admin\UnitKerjaController;
use App\Http\Controllers\admin\FormasiTimController;
use App\Http\Controllers\admin\StatusCutiController;
use App\Http\Controllers\admin\JenisAbsensiController;
use App\Http\Controllers\admin\KonfigurasiAbsensiController;
use App\Http\Controllers\admin\KonfigurasiCutiController;
use App\Http\Controllers\admin\StatusAbsensiController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return redirect()->route('dashboard.index');
})->name('home');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    Route::prefix('admin')->group(function () {
        Route::controller(DashboardController::class)->group(function () {
            // Mainmenu
            Route::get('/dashboard', 'index')->name('dashboard.index');
            Route::get('/data-essentials', 'dataEssentials')->name('dataEssentials.index');
        });

        Route::resource('/user', UserController::class);
        Route::resource('/provinsi', ProvinsiController::class);
        Route::resource('/kota', KotaController::class)->parameters(['kota' => 'kota']);
        Route::resource('/kecamatan', KecamatanController::class);
        Route::resource('/kelurahan', KelurahanController::class);
        Route::resource('/pulau', PulauController::class);
        Route::resource('/unit-kerja', UnitKerjaController::class);
        Route::resource('/seksi', SeksiController::class);
        Route::resource('/tim', TimController::class);
        Route::resource('/formasi-tim', FormasiTimController::class);
        Route::resource('/gender', GenderController::class);
        Route::resource('/user-type', UserTypeController::class);
        Route::resource('/jabatan', JabatanController::class);
        Route::resource('/jenis-cuti', JenisCutiController::class);
        Route::resource('/status-cuti', StatusCutiController::class);
        Route::resource('/jenis-absensi', JenisAbsensiController::class);
        Route::resource('/status-absensi', StatusAbsensiController::class);
        Route::resource('/cuti', CutiController::class);
        Route::resource('/kegiatan', KegiatanController::class);
        Route::resource('/kinerja', KinerjaController::class);
        Route::resource('/absensi', AbsensiController::class);
        Route::resource('/konfigurasi-cuti', KonfigurasiCutiController::class);
        Route::resource('/konfigurasi-absensi', KonfigurasiAbsensiController::class);

        // CUTI
        Route::controller(CutiController::class)->group(function () {
            // Mainmenu
            Route::get('/konfigurasi-cuti', 'konfigurasi_cuti')->name('konfigurasi-cuti.index');
            Route::get('/approval-cuti', 'approval_cuti')->name('approval-cuti.index');
        });
    });

    Route::prefix('user')->group(function () {
        Route::controller(DashboardController::class)->group(function () {
            // Mainmenu
            Route::get('/kanit', 'kanit')->name('kanit.index');
            Route::get('/kasi', 'kasi')->name('kasi.index');
            Route::get('/pjlp', 'pjlp')->name('pjlp.index');
        });

        Route::controller(AbsensiController::class)->group(function () {
            // Mainmenu
            Route::get('/kanit-absensi', 'kanit_index')->name('kanit-absensi.index');
            Route::get('/kasi-absensi', 'kasi_index')->name('kasi-absensi.index');

            Route::get('/pjlp-absensi', 'pjlp_index')->name('pjlp-absensi.index');
            Route::get('/pjlp-absensi-create', 'pjlp_create')->name('pjlp-absensi.create');
        });

        Route::controller(KinerjaController::class)->group(function () {
            // Mainmenu
            Route::get('/kanit-kinerja', 'kanit_index')->name('kanit-kinerja.index');
            Route::get('/kasi-kinerja', 'kasi_index')->name('kasi-kinerja.index');

            Route::get('/pjlp-kinerja', 'pjlp_index')->name('pjlp-kinerja.index');
            Route::get('/pjlp-kinerja-create', 'pjlp_create')->name('pjlp-kinerja.create');
        });

        Route::controller(CutiController::class)->group(function () {
            // Mainmenu
            Route::get('/kanit-cuti', 'kanit_index')->name('kanit-cuti.index');
            Route::get('/kanit-cuti-approval', 'kanit_approval')->name('kanit-cuti-approval.index');

            Route::get('/kasi-cuti', 'kasi_index')->name('kasi-cuti.index');
            Route::get('/kasi-cuti-approval', 'kasi_approval')->name('kasi-cuti-approval.index');

            Route::get('/pjlp-cuti', 'pjlp_index')->name('pjlp-cuti.index');
            Route::get('/pjlp-cuti-create', 'pjlp_create')->name('pjlp-cuti.create');
        });

        Route::controller(UserController::class)->group(function () {
            // Mainmenu
            Route::get('/profile', 'profile')->name('profile.index');
            Route::get('/update_password', 'update_password')->name('update_password.index');
        });
    });
});
