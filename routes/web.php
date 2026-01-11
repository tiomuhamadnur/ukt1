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
use App\Http\Controllers\admin\PermissionController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\StatusAbsensiController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return redirect()->route('dashboard.index');
})->name('home');

Route::get('/register', function () {
    return redirect()->route('dashboard.index');
});

Route::get('/dashboard', function () {
    return redirect()->route('dashboard.index');
});

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::group(['middleware' => ['auth', 'CheckBanned', 'CheckKonfigurasiPJLP']], function () {
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



    Route::prefix('admin')->middleware('permission:admin')->group(function () {
        Route::middleware(['permission:superadmin'])->group(function() {
            Route::controller(DashboardController::class)->group(function () {
                Route::get('/data-essentials', 'dataEssentials')->name('dataEssentials.index');
            });
            Route::resource('/provinsi', ProvinsiController::class);
            Route::resource('/kota', KotaController::class)->parameters(['kota' => 'kota']);
            Route::resource('/kecamatan', KecamatanController::class);
            Route::resource('/kelurahan', KelurahanController::class);
            Route::resource('/pulau', PulauController::class);
            Route::resource('/unit-kerja', UnitKerjaController::class);
            Route::resource('/seksi', SeksiController::class);
            Route::resource('/tim', TimController::class);
            Route::resource('/gender', GenderController::class);
            Route::resource('/user-type', UserTypeController::class);
            Route::resource('/jabatan', JabatanController::class);
            Route::resource('/jenis-cuti', JenisCutiController::class);
            Route::resource('/status-cuti', StatusCutiController::class);
            Route::resource('/jenis-absensi', JenisAbsensiController::class);
            Route::resource('/status-absensi', StatusAbsensiController::class);
            Route::resource('/konfigurasi-absensi', KonfigurasiAbsensiController::class);
            Route::resource('/permission', PermissionController::class);
            Route::resource('/role', RoleController::class);
        });

        Route::resource('/formasi-tim', FormasiTimController::class);
        Route::resource('/user', UserController::class);
        Route::resource('/konfigurasi-cuti', KonfigurasiCutiController::class);
        Route::resource('/kegiatan', KegiatanController::class);
        Route::resource('/kinerja', KinerjaController::class);
        Route::resource('/absensi', AbsensiController::class);
        Route::resource('/cuti', CutiController::class);
        Route::controller(CutiController::class)->group(function () {
            Route::post('/cuti/revoke/{uuid}', 'revoke')->name('cuti.revoke');
        });
    });

    Route::prefix('user')->group(function () {
        // MENU DASHBOARD
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('dashboard.index');
        });

        Route::controller(DashboardController::class)->group(function () {
            Route::get('/kanit', 'kanit')->name('kanit.index')->middleware('permission:kanit');
            Route::get('/kasi', 'kasi')->name('kasi.index')->middleware('permission:kasi');
            Route::get('/pjlp', 'pjlp')->name('pjlp.index')->middleware('permission:pjlp');
        });




        // MENU PROFILE
        Route::controller(UserController::class)->group(function () {
            Route::get('/profile', 'profile')->name('profile.index');
            Route::get('/update-password', 'password')->name('password.index');
            Route::put('/update-password', 'update_password')->name('user.password.update');
            Route::put('/update-photo/{uuid}', 'update_photo')->name('user.photo.update');
        });




        //MENU ABSENSI
        Route::controller(AbsensiController::class)->middleware('permission:kasi|kanit')->group(function () {
            Route::get('/kanit-absensi', 'kanit_index')->name('kanit-absensi.index')->middleware('permission:kanit');
            Route::get('/kasi-absensi', 'kasi_index')->name('kasi-absensi.index')->middleware('permission:kasi');
        });

        Route::controller(AbsensiController::class)->middleware('permission:pjlp|kanit|kasi')->group(function () {
            Route::middleware('permission:pjlp')->group(function () {
                Route::get('/pjlp-absensi', 'pjlp_index')->name('pjlp-absensi.index');
                Route::get('/pjlp-absensi-create', 'pjlp_create')->name('pjlp-absensi.create');
                Route::post('/pjlp-absensi', 'pjlp_store')->name('pjlp-absensi.store');
            });

            Route::get('/absensi/export/excel', 'export_excel')->name('absensi.export.excel');
            Route::get('/absensi/export/pdf', 'export_pdf')->name('absensi.export.pdf');
        });




        // MENU CUTI
        Route::controller(CutiController::class)->middleware('permission:kasi|kanit')->group(function () {
            Route::get('/approval-cuti', 'approval_cuti')->name('approval-cuti.index');
            Route::put('/approval-cuti/approve', 'cuti_approve')->name('approval-cuti.approve');
            Route::put('/approval-cuti/reject', 'cuti_reject')->name('approval-cuti.reject');
        });

        Route::controller(CutiController::class)->middleware('permission:kanit')->group(function () {
            Route::get('/kanit-cuti', 'kanit_index')->name('kanit-cuti.index');
            Route::get('/kanit-cuti-approval', 'kanit_approval')->name('kanit-cuti-approval.index');
        });

        Route::controller(CutiController::class)->middleware('permission:kasi')->group(function () {
            Route::get('/kasi-cuti', 'kasi_index')->name('kasi-cuti.index');
            Route::get('/kasi-cuti-approval', 'kasi_approval')->name('kasi-cuti-approval.index');
        });

        Route::controller(CutiController::class)->middleware('permission:pjlp')->group(function () {
            Route::get('/pjlp-cuti', 'pjlp_index')->name('pjlp-cuti.index');
            Route::get('/pjlp-cuti-create', 'pjlp_create')->name('pjlp-cuti.create');
            Route::post('/pjlp-cuti', 'pjlp_store')->name('pjlp-cuti.store');
            Route::post('/pjlp-cuti', 'pjlp_store')->name('pjlp-cuti.store');
            Route::delete('/pjlp-cuti/{uuid}', 'pjlp_destroy')->name('pjlp-cuti.destroy');
        });

        Route::controller(CutiController::class)->middleware('permission:pjlp|kanit|kasi')->group(function () {
            Route::get('/cuti/export/pdf/{uuid}', 'export_pdf')->name('cuti.export.pdf');
            Route::get('/cuti/export/excel', 'export_excel')->name('cuti.export.excel');
        });




        // MENU KINERJA
        Route::controller(KinerjaController::class)->middleware('permission:kanit|kasi')->group(function () {
            Route::get('/kanit-kinerja', 'kanit_index')->name('kanit-kinerja.index')->middleware('permission:kanit');
            Route::get('/kasi-kinerja', 'kasi_index')->name('kasi-kinerja.index')->middleware('permission:kasi');
        });

        Route::controller(KinerjaController::class)->middleware('permission:pjlp')->group(function () {
            Route::get('/pjlp-kinerja', 'pjlp_index')->name('pjlp-kinerja.index');
            Route::get('/pjlp-kinerja-create', 'pjlp_create')->name('pjlp-kinerja.create');
            Route::post('/pjlp-kinerja', 'pjlp_store')->name('pjlp-kinerja.store');
        });

        Route::controller(KinerjaController::class)->middleware('permission:pjlp|kanit|kasi')->group(function () {
            Route::get('/kinerja/export/pdf', 'export_pdf')->name('kinerja.export.pdf');
            Route::get('/kinerja/export/pdf-personel', 'export_pdf_personel')->name('kinerja.personel.export.pdf');
            Route::get('/kinerja/export/excel', 'export_excel')->name('kinerja.export.excel');
        });
    });
});
