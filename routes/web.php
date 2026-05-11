<?php

use Illuminate\Support\Facades\Route;

// Import Controller Dashboard
use App\Http\Controllers\Backend\Dashboard\DashboardAdminController; // Sesuaikan jika nama controllernya beda
// Import Controller PROFILE
use App\Http\Controllers\Backend\MyProfile\AccountController;
use App\Http\Controllers\Backend\MyProfile\ProfileController;
use App\Http\Controllers\Backend\MyProfile\SecurityController;
use App\Http\Controllers\Backend\MyProfile\ActivityController;
use App\Http\Controllers\Backend\MyProfile\LoginSessionController;

// Import Controller USER MANAGEMENT
use App\Http\Controllers\Backend\UserManagement\UserController;
use App\Http\Controllers\Backend\UserManagement\RoleController;

// Import Controller HELP/LOG
use App\Http\Controllers\Backend\Help\LogActivityController;
use App\Http\Controllers\Backend\Settings\SettingController;

// Frontend Controllers
use App\Http\Controllers\Frontend\Auth\FrontendLoginController;
use App\Http\Controllers\Frontend\Dashboard\FrontendDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Halaman Depan (Langsung diarahkan ke Login)
// Halaman Depan (Langsung diarahkan ke Login)
Route::any('/', function () {
    return redirect('/admin/login');
});

Route::any('/dine-sync-pos', function () {
    return redirect('/admin/login');
});



// --- TARUH DEBUG DISINI (DI LUAR MIDDLEWARE AUTH) ---
Route::get('/admin/debug-session', function () {
    $user = auth()->user();

    // Cek manual apakah tabel bans error
    $bannedStatus = 'Tidak dicek';
    $error = null;

    if ($user) {
        try {
            // Kita coba panggil paksa relasi banned-nya
            $bannedStatus = $user->isBanned() ? 'YA TER-BANNED' : 'AMAN';
        } catch (\Exception $e) {
            $bannedStatus = 'ERROR SAAT CEK BANNED: ' . $e->getMessage();
        }
    }

    return [
        'status_login' => $user ? 'SUDAH LOGIN' : 'BELUM LOGIN / SESI HILANG',
        'user_id' => $user?->id,
        'user_name' => $user?->name,
        'session_id' => session()->getId(),
        'driver_session' => config('session.driver'),
        'cek_banned' => $bannedStatus,
    ];
});

// NOTE: Route /login POST dihapus dari sini karena sudah ada di auth.php
// agar tidak bentrok "Route [login] defined twice".

// Group Middleware untuk User yang sudah Login
// Kita tambahkan 'forbid-banned-user' agar user yang di-banned tidak bisa akses
Route::middleware(['auth', 'forbid-banned-user'])->group(function () {

    // --- SHARED ROLE ROUTES (generate-permissions helper, select) ---
    Route::post('/admin/roles/generate-permissions', [RoleController::class, 'generatePermissions'])->name('roles.generate');
    Route::get('/admin/select/role', [RoleController::class, 'select'])->name('role.select');

    // --- DASHBOARD (accessible by ALL authenticated roles) ---
    Route::get('/admin/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

    // --- MY ACCOUNT / PROFILE (accessible by ALL authenticated users) ---
    Route::get('/admin/my-account', [AccountController::class, 'index'])->name('account.index');
    Route::get('/admin/my-account/{id}/avatar', [AccountController::class, 'editAvatar'])->name('avatar-edit');
    Route::post('/admin/my-account/{id}/update-avatar', [AccountController::class, 'updateAvatar'])->name('avatar-update');

    Route::resource('/admin/my-profile', ProfileController::class);
    Route::resource('/admin/my-security', SecurityController::class);
    Route::post('/admin/my-security', [SecurityController::class, 'store'])->name('change.password');
    Route::post('/admin/my-security/logout-other-devices', [SecurityController::class, 'logoutOtherDevices'])->name('security.logout-other-devices');

    Route::get('/admin/my-activity', [ActivityController::class, 'index'])->name('my-activity.index');
    Route::get('/admin/mget-my-activity', [ActivityController::class, 'getActivity'])->name('get-my-activity');

    Route::get('/admin/mmy-login-session', [LoginSessionController::class, 'index'])->name('my-login-session.index');
    Route::get('/admin/mget-my-login-session', [LoginSessionController::class, 'getLoginSession'])->name('get-my-login-session');

    // --- SETTINGS (accessible by ALL authenticated users) ---
    Route::get('/admin/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/admin/settings/update', [SettingController::class, 'update'])->name('settings.update');

    // --- DEBUG/CHECK AUTH ---
    Route::get('/admin/check-auth', function () {
        $u = auth()->user();
        return [
            'user' => $u,
            'roles' => $u?->getRoleNames(),
            'permissions' => $u?->getAllPermissions()->pluck('name'),
        ];
    });
    Route::get('/admin/debug-session', function () {
        $user = auth()->user();
        return ['user' => $user?->name, 'roles' => $user?->getRoleNames()];
    });

    // ====================================================
    // RESOURCES (User & Role Mgmt): view_resources — Superadmin only
    // ====================================================
    Route::middleware('can:view_resources')->group(function () {
        Route::resource('/admin/users', UserController::class);
        Route::get('/admin/get-datauser', [UserController::class, 'getDataUsers'])->name('get-users');
        Route::post('/admin/users/mass-delete', [UserController::class, 'massDelete'])->name('users.mass-delete');
        Route::get('/admin/get-user-show-log/{id}', [UserController::class, 'getLoginSession'])->name('get-user-show-log');
        Route::get('/admin/get-user-show-log-activity/{id}', [UserController::class, 'getActivity'])->name('get-user-show-log-activity');
        Route::post('/admin/users/{id}/ban', [UserController::class, 'ban'])->name('users.ban');
        Route::post('/admin/users/{id}/unban', [UserController::class, 'unban'])->name('users.unban');

        Route::resource('/admin/roles', RoleController::class);
        Route::get('/admin/get-datarole', [RoleController::class, 'getDataRoles'])->name('get-datarole');
        Route::post('/admin/roles/mass-delete', [RoleController::class, 'massDelete'])->name('roles.mass-delete');
    });

    // ====================================================
    // SAKIP DATA MASTER
    // ====================================================
    Route::prefix('admin/sakip')->name('sakip.')->group(function () {
        Route::resource('urusan', \App\Http\Controllers\Backend\Sakip\SakipUrusanController::class);
        Route::resource('bidang', \App\Http\Controllers\Backend\Sakip\SakipBidangController::class);
        Route::get('bidang/get-by-urusan/{urusan_id}', [\App\Http\Controllers\Backend\Sakip\SakipBidangController::class, 'getByUrusan'])->name('bidang.get-by-urusan');
        Route::resource('periode', \App\Http\Controllers\Backend\Sakip\SakipPeriodeController::class);
        Route::resource('program', \App\Http\Controllers\Backend\Sakip\SakipProgramController::class);
        Route::get('program/get-by-bidang/{bidang_id}', [\App\Http\Controllers\Backend\Sakip\SakipProgramController::class, 'getByBidang'])->name('program.get-by-bidang');
        Route::resource('visi', \App\Http\Controllers\Backend\Sakip\SakipVisiController::class);
        Route::get('visi/get-by-periode/{periode_id}', [\App\Http\Controllers\Backend\Sakip\SakipVisiController::class, 'getByPeriode'])->name('visi.get-by-periode');
        Route::resource('misi', \App\Http\Controllers\Backend\Sakip\SakipMisiController::class);
        Route::resource('kegiatan', \App\Http\Controllers\Backend\Sakip\SakipKegiatanController::class);
        Route::get('kegiatan/get-by-program/{program_id}', [\App\Http\Controllers\Backend\Sakip\SakipKegiatanController::class, 'getByProgram'])->name('kegiatan.get-by-program');
        Route::resource('subkegiatan', \App\Http\Controllers\Backend\Sakip\SakipSubkegiatanController::class);
        Route::resource('pimpinan', \App\Http\Controllers\Backend\Sakip\SakipPimpinanController::class);
        Route::resource('subunit', \App\Http\Controllers\Backend\Sakip\SakipSubunitController::class);
        Route::resource('title', \App\Http\Controllers\Backend\Sakip\SakipTitleController::class);
        Route::resource('unitkerja', \App\Http\Controllers\Backend\Sakip\SakipUnitkerjaController::class);
        Route::resource('pegawaibappeda', \App\Http\Controllers\Backend\Sakip\SakipPegawaibappedaController::class);
        
        // SKPD & Penjabat (Combined)
        Route::resource('skpd', \App\Http\Controllers\Backend\Sakip\SakipSkpdController::class);
        Route::get('skpd/penjabat/{skpd_id}', [\App\Http\Controllers\Backend\Sakip\SakipSkpdController::class, 'getPenjabat'])->name('skpd.penjabat');
        Route::post('skpd/penjabat-store', [\App\Http\Controllers\Backend\Sakip\SakipSkpdController::class, 'storePenjabat'])->name('skpd.store-penjabat');
        Route::get('skpd/penjabat-edit/{id}', [\App\Http\Controllers\Backend\Sakip\SakipSkpdController::class, 'editPenjabat'])->name('skpd.edit-penjabat');
        Route::delete('skpd/penjabat-delete/{id}', [\App\Http\Controllers\Backend\Sakip\SakipSkpdController::class, 'destroyPenjabat'])->name('skpd.destroy-penjabat');
    });

    // ====================================================
    // HELP (Log Activity): view_help — Superadmin, admin
    // ====================================================
    Route::middleware('can:view_help')->group(function () {
        Route::resource('/admin/log-activity', LogActivityController::class);
        Route::get('/admin/get-datalogactivity', [LogActivityController::class, 'getDataLogActivity'])->name('get-datalogactivity');
    });
});

// Load Routes Authentication (Login, Register, Reset Password)
require __DIR__ . '/auth.php';

// ====================================================
// FRONTEND ROUTES
// ====================================================
Route::prefix('frontend')->name('frontend.')->group(function () {
    
    // Guest Routes
    Route::middleware('guest:frontend')->group(function () {
        Route::get('login', [FrontendLoginController::class, 'create'])->name('login');
        Route::post('login', [FrontendLoginController::class, 'store']);
    });

    // Authenticated Routes
    Route::middleware(['auth:frontend', 'forbid-banned-user'])->group(function () {
        Route::get('dashboard', [FrontendDashboardController::class, 'index'])->name('dashboard');
        Route::post('logout', [FrontendLoginController::class, 'destroy'])->name('logout');
        
        // RENSTRA
        Route::prefix('renstra')->name('renstra.')->group(function () {
            Route::get('dataskpd', [\App\Http\Controllers\Frontend\Renstra\DataSkpdController::class, 'index'])->name('dataskpd.index');
            
            // Sasaran Renstra
            Route::resource('sasaran', \App\Http\Controllers\Frontend\Renstra\SasaranRenstraController::class);
            Route::get('sasaran/get-sasaran-rpjmd/{periode_id}', [\App\Http\Controllers\Frontend\Renstra\SasaranRenstraController::class, 'getSasaranRpjmd'])->name('sasaran.get-rpjmd');
            Route::get('sasaran/get-tujuan-renstra/{skpd_id}/{periode_id}', [\App\Http\Controllers\Frontend\Renstra\SasaranRenstraController::class, 'getTujuanRenstra'])->name('sasaran.get-tujuan-renstra');
            Route::post('sasaran/link-tujuan/{id}', [\App\Http\Controllers\Frontend\Renstra\SasaranRenstraController::class, 'linkTujuanPost'])->name('sasaran.link-tujuan-post');
            
            // Indikator Routes
            Route::get('sasaran/{sasaran_id}/indikators', [\App\Http\Controllers\Frontend\Renstra\SasaranRenstraController::class, 'getIndikators'])->name('sasaran.get-indikators');
            Route::post('sasaran/indikator/store', [\App\Http\Controllers\Frontend\Renstra\SasaranRenstraController::class, 'storeIndikator'])->name('sasaran.indikator.store');
            Route::get('sasaran/indikator/{id}/edit', [\App\Http\Controllers\Frontend\Renstra\SasaranRenstraController::class, 'editIndikator'])->name('sasaran.indikator.edit');
            Route::put('sasaran/indikator/{id}', [\App\Http\Controllers\Frontend\Renstra\SasaranRenstraController::class, 'updateIndikator'])->name('sasaran.indikator.update');
            Route::delete('sasaran/indikator/{id}', [\App\Http\Controllers\Frontend\Renstra\SasaranRenstraController::class, 'deleteIndikator'])->name('sasaran.indikator.destroy');

            // Indikator Tujuan Renstra
            Route::resource('indikator-tujuan', \App\Http\Controllers\Frontend\Renstra\IndikatorTujuanRenstraController::class);

            // Tujuan Renstra
            Route::resource('tujuan', \App\Http\Controllers\Frontend\Renstra\TujuanRenstraController::class);
            Route::post('tujuan/get-data', [\App\Http\Controllers\Frontend\Renstra\TujuanRenstraController::class, 'getData'])->name('tujuan.get-data');
            Route::get('tujuan/get-misi/{periode_id}', [\App\Http\Controllers\Frontend\Renstra\TujuanRenstraController::class, 'getMisi'])->name('tujuan.get-misi');
            Route::get('tujuan/get-tujuan-rpjmd/{misi_id}', [TujuanRenstraController::class, 'getTujuanRpjmd'])->name('tujuan.get-tujuan-rpjmd');
            Route::get('tujuan/get-sasaran-rpjmd/{tujuan_id}', [TujuanRenstraController::class, 'getSasaranRpjmd'])->name('tujuan.get-sasaran-rpjmd');

            // Strategi Renstra
            Route::resource('strategi', \App\Http\Controllers\Frontend\Renstra\StrategiRenstraController::class);

            // Kebijakan Renstra
            Route::resource('kebijakan', \App\Http\Controllers\Frontend\Renstra\KebijakanRenstraController::class);

            // Formulasi Renstra
            Route::resource('formulasi', \App\Http\Controllers\Frontend\Renstra\FormulasiRenstraController::class);

            // Cascading Program
            Route::get('cascadingprogram/data', [\App\Http\Controllers\Frontend\Renstra\CascadingProgramController::class, 'data'])->name('cascadingprogram.data');
            Route::get('cascadingprogram/get-sasaran-renstra', [\App\Http\Controllers\Frontend\Renstra\CascadingProgramController::class, 'getSasaranRenstra'])->name('cascadingprogram.getSasaranRenstra');
            Route::get('cascadingprogram/get-indikator-sasaran-renstra', [\App\Http\Controllers\Frontend\Renstra\CascadingProgramController::class, 'getIndikatorSasaranRenstra'])->name('cascadingprogram.getIndikatorSasaranRenstra');
            Route::get('cascadingprogram/get-associated-values', [\App\Http\Controllers\Frontend\Renstra\CascadingProgramController::class, 'getAssociatedValues'])->name('cascadingprogram.getAssociatedValues');
            Route::get('cascadingprogram/get-programs', [\App\Http\Controllers\Frontend\Renstra\CascadingProgramController::class, 'getPrograms'])->name('cascadingprogram.getPrograms');
            Route::resource('cascadingprogram', \App\Http\Controllers\Frontend\Renstra\CascadingProgramController::class);

            // Penjabat Cascading
            Route::get('penjabat-cascading/fetch', [\App\Http\Controllers\Frontend\Renstra\PenjabatCascadingController::class, 'fetchPenjabatskpd'])->name('penjabat-cascading.fetch');
            Route::post('penjabat-cascading', [\App\Http\Controllers\Frontend\Renstra\PenjabatCascadingController::class, 'store'])->name('penjabat-cascading.store');
            Route::delete('penjabat-cascading/{id}', [\App\Http\Controllers\Frontend\Renstra\PenjabatCascadingController::class, 'destroy'])->name('penjabat-cascading.destroy');

            // Cascading Kegiatan
            Route::get('cascadingkegiatan/data', [\App\Http\Controllers\Frontend\Renstra\CascadingKegiatanController::class, 'data'])->name('cascadingkegiatan.data');
            Route::get('cascadingkegiatan/get-program-cascading', [\App\Http\Controllers\Frontend\Renstra\CascadingKegiatanController::class, 'getProgramCascading'])->name('cascadingkegiatan.getProgramCascading');
            Route::get('cascadingkegiatan/get-indikator-program', [\App\Http\Controllers\Frontend\Renstra\CascadingKegiatanController::class, 'getIndikatorProgram'])->name('cascadingkegiatan.getIndikatorProgram');
            Route::get('cascadingkegiatan/get-associated-values', [\App\Http\Controllers\Frontend\Renstra\CascadingKegiatanController::class, 'getAssociatedValues'])->name('cascadingkegiatan.getAssociatedValues');
            Route::get('cascadingkegiatan/get-kegiatans', [\App\Http\Controllers\Frontend\Renstra\CascadingKegiatanController::class, 'getKegiatans'])->name('cascadingkegiatan.getKegiatans');
            Route::resource('cascadingkegiatan', \App\Http\Controllers\Frontend\Renstra\CascadingKegiatanController::class);

            // Penjabat Kegiatan
            Route::get('penjabat-kegiatan/fetch', [\App\Http\Controllers\Frontend\Renstra\PenjabatKegiatanController::class, 'fetchPenjabatskpd'])->name('penjabat-kegiatan.fetch');
            Route::post('penjabat-kegiatan', [\App\Http\Controllers\Frontend\Renstra\PenjabatKegiatanController::class, 'store'])->name('penjabat-kegiatan.store');
            Route::delete('penjabat-kegiatan/{id}', [\App\Http\Controllers\Frontend\Renstra\PenjabatKegiatanController::class, 'destroy'])->name('penjabat-kegiatan.destroy');

            // Cascading Sub Kegiatan
            Route::get('cascadingsubkegiatan/data', [\App\Http\Controllers\Frontend\Renstra\CascadingSubKegiatanController::class, 'data'])->name('cascadingsubkegiatan.data');
            Route::get('cascadingsubkegiatan/get-kegiatan-cascading', [\App\Http\Controllers\Frontend\Renstra\CascadingSubKegiatanController::class, 'getKegiatanCascading'])->name('cascadingsubkegiatan.getKegiatanCascading');
            Route::get('cascadingsubkegiatan/get-associated-values', [\App\Http\Controllers\Frontend\Renstra\CascadingSubKegiatanController::class, 'getAssociatedValues'])->name('cascadingsubkegiatan.getAssociatedValues');
            Route::get('cascadingsubkegiatan/get-subkegiatans', [\App\Http\Controllers\Frontend\Renstra\CascadingSubKegiatanController::class, 'getSubKegiatans'])->name('cascadingsubkegiatan.getSubKegiatans');
            Route::resource('cascadingsubkegiatan', \App\Http\Controllers\Frontend\Renstra\CascadingSubKegiatanController::class);

            // Penjabat Sub Kegiatan
            Route::get('penjabat-subkegiatan/fetch', [\App\Http\Controllers\Frontend\Renstra\PenjabatSubKegiatanController::class, 'fetchPenjabatskpd'])->name('penjabat-subkegiatan.fetch');
            Route::post('penjabat-subkegiatan', [\App\Http\Controllers\Frontend\Renstra\PenjabatSubKegiatanController::class, 'store'])->name('penjabat-subkegiatan.store');
            Route::delete('penjabat-subkegiatan/{id}', [\App\Http\Controllers\Frontend\Renstra\PenjabatSubKegiatanController::class, 'destroy'])->name('penjabat-subkegiatan.destroy');
        });
        
        // RKT Routes
        Route::prefix('rkt')->name('rkt.')->group(function () {
            // Sasaran
            Route::get('sasaran/data', [\App\Http\Controllers\Frontend\Rkt\RktSasaranController::class, 'data'])->name('sasaran.data');
            Route::post('sasaran/filter', [\App\Http\Controllers\Frontend\Rkt\RktSasaranController::class, 'storeFilter'])->name('sasaran.filter');
            Route::resource('sasaran', \App\Http\Controllers\Frontend\Rkt\RktSasaranController::class);

            // Program
            Route::get('program/data', [\App\Http\Controllers\Frontend\Rkt\RktProgramController::class, 'data'])->name('program.data');
            Route::post('program/filter', [\App\Http\Controllers\Frontend\Rkt\RktProgramController::class, 'storeFilter'])->name('program.filter');
            Route::resource('program', \App\Http\Controllers\Frontend\Rkt\RktProgramController::class);

            // Kegiatan
            Route::get('kegiatan/data', [\App\Http\Controllers\Frontend\Rkt\RktKegiatanController::class, 'data'])->name('kegiatan.data');
            Route::post('kegiatan/filter', [\App\Http\Controllers\Frontend\Rkt\RktKegiatanController::class, 'storeFilter'])->name('kegiatan.filter');
            Route::resource('kegiatan', \App\Http\Controllers\Frontend\Rkt\RktKegiatanController::class);

            // Subkegiatan
            Route::get('subkegiatan/data', [\App\Http\Controllers\Frontend\Rkt\RktSubkegiatanController::class, 'data'])->name('subkegiatan.data');
            Route::post('subkegiatan/filter', [\App\Http\Controllers\Frontend\Rkt\RktSubkegiatanController::class, 'storeFilter'])->name('subkegiatan.filter');
            Route::resource('subkegiatan', \App\Http\Controllers\Frontend\Rkt\RktSubkegiatanController::class);

            // Anggaran Program
            Route::get('anggaran-program', [\App\Http\Controllers\Frontend\Rkt\RktAnggaranProgramController::class, 'index'])->name('anggaran-program.index');
            Route::get('anggaran-program/data', [\App\Http\Controllers\Frontend\Rkt\RktAnggaranProgramController::class, 'data'])->name('anggaran-program.data');
            Route::post('anggaran-program/filter', [\App\Http\Controllers\Frontend\Rkt\RktAnggaranProgramController::class, 'storeFilter'])->name('anggaran-program.filter');

            // Anggaran Kegiatan
            Route::get('anggaran-kegiatan', [\App\Http\Controllers\Frontend\Rkt\RktAnggaranKegiatanController::class, 'index'])->name('anggaran-kegiatan.index');
            Route::get('anggaran-kegiatan/data', [\App\Http\Controllers\Frontend\Rkt\RktAnggaranKegiatanController::class, 'data'])->name('anggaran-kegiatan.data');
            Route::post('anggaran-kegiatan/filter', [\App\Http\Controllers\Frontend\Rkt\RktAnggaranKegiatanController::class, 'storeFilter'])->name('anggaran-kegiatan.filter');

            // Anggaran Sub Kegiatan
            Route::get('anggaran-subkegiatan', [\App\Http\Controllers\Frontend\Rkt\RktAnggaranSubkegiatanController::class, 'index'])->name('anggaran-subkegiatan.index');
            Route::get('anggaran-subkegiatan/data', [\App\Http\Controllers\Frontend\Rkt\RktAnggaranSubkegiatanController::class, 'data'])->name('anggaran-subkegiatan.data');
            Route::post('anggaran-subkegiatan/update', [\App\Http\Controllers\Frontend\Rkt\RktAnggaranSubkegiatanController::class, 'update'])->name('anggaran-subkegiatan.update');
        });

        // PK (Perjanjian Kinerja)
        Route::prefix('pk')->name('pk.')->group(function() {
            // Target PK Sasaran
            Route::get('sasaran', [\App\Http\Controllers\Frontend\Pk\PkSasaranController::class, 'index'])->name('sasaran.index');
            Route::get('sasaran/data', [\App\Http\Controllers\Frontend\Pk\PkSasaranController::class, 'data'])->name('sasaran.data');
            Route::get('sasaran/{id}/edit', [\App\Http\Controllers\Frontend\Pk\PkSasaranController::class, 'edit'])->name('sasaran.edit');
            Route::post('sasaran/store', [\App\Http\Controllers\Frontend\Pk\PkSasaranController::class, 'store'])->name('sasaran.store');

            // Target PK Program
            Route::get('program', [\App\Http\Controllers\Frontend\Pk\PkProgramController::class, 'index'])->name('program.index');
            Route::get('program/data', [\App\Http\Controllers\Frontend\Pk\PkProgramController::class, 'data'])->name('program.data');
            Route::get('program/{id}/edit', [\App\Http\Controllers\Frontend\Pk\PkProgramController::class, 'edit'])->name('program.edit');
            Route::post('program/store', [\App\Http\Controllers\Frontend\Pk\PkProgramController::class, 'store'])->name('program.store');

            // Target PK Kegiatan
            Route::get('kegiatan', [\App\Http\Controllers\Frontend\Pk\PkKegiatanController::class, 'index'])->name('kegiatan.index');
            Route::get('kegiatan/data', [\App\Http\Controllers\Frontend\Pk\PkKegiatanController::class, 'data'])->name('kegiatan.data');
            Route::get('kegiatan/{id}/edit', [\App\Http\Controllers\Frontend\Pk\PkKegiatanController::class, 'edit'])->name('kegiatan.edit');
            Route::post('kegiatan/store', [\App\Http\Controllers\Frontend\Pk\PkKegiatanController::class, 'store'])->name('kegiatan.store');

            // Target PK Sub Kegiatan
            Route::get('subkegiatan', [\App\Http\Controllers\Frontend\Pk\PkSubkegiatanController::class, 'index'])->name('subkegiatan.index');
            Route::get('subkegiatan/data', [\App\Http\Controllers\Frontend\Pk\PkSubkegiatanController::class, 'data'])->name('subkegiatan.data');
            Route::get('subkegiatan/{id}/edit', [\App\Http\Controllers\Frontend\Pk\PkSubkegiatanController::class, 'edit'])->name('subkegiatan.edit');
            Route::post('subkegiatan/store', [\App\Http\Controllers\Frontend\Pk\PkSubkegiatanController::class, 'store'])->name('subkegiatan.store');

            // Anggaran Program PK
            Route::get('anggaran-program', [\App\Http\Controllers\Frontend\Pk\PkAnggaranProgramController::class, 'index'])->name('anggaran-program.index');
            Route::get('anggaran-program/data', [\App\Http\Controllers\Frontend\Pk\PkAnggaranProgramController::class, 'data'])->name('anggaran-program.data');

            // Anggaran Kegiatan PK
            Route::get('anggaran-kegiatan', [\App\Http\Controllers\Frontend\Pk\PkAnggaranKegiatanController::class, 'index'])->name('anggaran-kegiatan.index');
            Route::get('anggaran-kegiatan/data', [\App\Http\Controllers\Frontend\Pk\PkAnggaranKegiatanController::class, 'data'])->name('anggaran-kegiatan.data');

            // Anggaran Sub Kegiatan PK
            Route::get('anggaran-subkegiatan', [\App\Http\Controllers\Frontend\Pk\PkAnggaranSubkegiatanController::class, 'index'])->name('anggaran-subkegiatan.index');
            Route::get('anggaran-subkegiatan/data', [\App\Http\Controllers\Frontend\Pk\PkAnggaranSubkegiatanController::class, 'data'])->name('anggaran-subkegiatan.data');
            Route::post('anggaran-subkegiatan/update', [\App\Http\Controllers\Frontend\Pk\PkAnggaranSubkegiatanController::class, 'update'])->name('anggaran-subkegiatan.update');
        });

        // Add more frontend routes here
    });
});
