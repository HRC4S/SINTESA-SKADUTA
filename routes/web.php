<?php

use Illuminate\Support\Facades\Route;

// Controller Utama
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\Admin\KartuPelajarController;
use App\Http\Controllers\Admin\KonselingController;
use App\Http\Controllers\Admin\KeterlambatanController;
use App\Http\Controllers\Admin\DokumenSiswaController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserPasswordController;
use App\Http\Controllers\Admin\SiswaAksesController;
use App\Http\Controllers\Admin\PrestasiController;

use App\Http\Controllers\BK\DashboardBKController;
use App\Http\Controllers\Kesiswaan\KesiswaanDashboardController;
use App\Http\Controllers\WaliKelas\WaliSiswaController;
use App\Http\Controllers\WaliKelas\DokumenSiswaWaliController;
use App\Http\Controllers\WaliKelas\WaliKartuPelajarController;
use App\Http\Controllers\WaliKelas\DashboardWaliController;
use App\Http\Controllers\WaliKelas\UserPasswordWaliController;
use App\Http\Controllers\WaliKelas\WaliPrestasiController;

use App\Http\Controllers\Siswa\OrtuController;
use App\Http\Controllers\Siswa\DashboardSiswaController;
use App\Http\Controllers\SiswaImportController;
use App\Http\Controllers\Siswa\PasswordController;
use App\Http\Controllers\Siswa\KeterlambatanSiswaController;
use App\Http\Controllers\Siswa\DokumenController;
use App\Http\Controllers\Siswa\KonselingsiswaController;
use App\Http\Controllers\Siswa\SiswaDataController;
use App\Http\Controllers\Siswa\SiswaPrestasiController;
use App\Http\Controllers\Siswa\KoorPresensiController;
use App\Http\Controllers\Siswa\PresensiSayaController;
use App\Http\Controllers\Admin\PresensiController;
use App\Http\Controllers\Auth\ForgotPasswordController as UnifiedForgotPasswordController;
/*
|--------------------------------------------------------------------------
| 🏠 ROUTE ROOT
|--------------------------------------------------------------------------
| Mengarahkan user berdasarkan guard & role
*/
Route::get('/', function () {

    // Jika login melalui guard guru
    if (auth('guru')->check()) {

        $role = auth('guru')->user()->role;

        return match ($role) {
            'admin'      => redirect()->route('admin.dashboard'),
            'guru_bk'    => redirect()->route('bk.dashboard'),
            'kesiswaan'  => redirect()->route('kesiswaan.dashboard'),
            'walikelas'   => redirect()->route('wali.dashboard'),
            default      => redirect()->route('guru.dashboard'),
        };
    }

    // Jika login siswa
    if (auth('siswa')->check()) {
        return redirect()->route('siswa.dashboard');
    }

    // Belum login
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| 🔐 LOGIN & LOGOUT
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login'])->name('login.process');

    // 🔑 Lupa Password (universal: guru & siswa, dikirim via email)
    Route::get('/forgot-password', [UnifiedForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request')
        ->middleware('throttle:10,1');
    Route::post('/forgot-password', [UnifiedForgotPasswordController::class, 'sendResetLink'])
        ->name('password.email')
        ->middleware('throttle:5,1');
    Route::get('/reset-password/{token}', [UnifiedForgotPasswordController::class, 'showResetForm'])
        ->name('password.reset')
        ->middleware('throttle:10,1');
    Route::post('/reset-password', [UnifiedForgotPasswordController::class, 'reset'])
        ->name('password.update')
        ->middleware('throttle:5,1');
});

// Logout universal
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 🧑‍💼 ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')
    ->middleware(['auth:guru', 'role:admin,guru_bk,kesiswaan'])
    ->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

    // CRUD Data Siswa untuk ADMIN
    Route::resource('datasiswa', SiswaController::class);

    // Akses Edit Siswa
    Route::put('/datasiswa/{nis}/toggle-akses', [SiswaAksesController::class, 'toggleAkses'])->name('datasiswa.toggleAkses');

   // === DATA SISWA (Admin full, Kesiswaan read-only) ===
    Route::middleware(['kesiswaan.readonly'])->group(function () {
        Route::resource('datasiswa', SiswaController::class);
    });
    

    // 📤 Import Data Siswa
    Route::get('datasiswa/import', [SiswaImportController::class, 'showImportForm'])->name('datasiswa.import.form');
    Route::post('datasiswa/import', [SiswaImportController::class, 'import'])->name('datasiswa.import');

    // 🪪 Kartu Pelajar
    Route::prefix('kartupelajar')->name('kartupelajar.')->group(function () {
        Route::get('/', [KartuPelajarController::class, 'index'])->name('index');
        Route::get('/preview/{nis}', [KartuPelajarController::class, 'preview'])->name('preview');
        Route::post('/print-mass', [KartuPelajarController::class, 'printMass'])->name('printMass');
        Route::get('/search', [KartuPelajarController::class, 'search'])->name('search');
        Route::get('/download-pdf/{nis}', [KartuPelajarController::class, 'downloadPDF'])->name('download.pdf');
        Route::get('/frame/{nis}', [KartuPelajarController::class, 'previewFrame'])->name('frame');
        
    });

    // 💬 Konseling
    Route::get('konseling', [KonselingController::class, 'index'])->name('konseling.index');
    Route::get('konseling/create', [KonselingController::class, 'create'])->name('konseling.create');
    Route::post('konseling/store', [KonselingController::class, 'store'])->name('konseling.store');
    Route::get('konseling/{id}/edit', [KonselingController::class, 'edit'])->name('konseling.edit');
    Route::put('konseling/{id}', [KonselingController::class, 'update'])->name('konseling.update');
    Route::delete('konseling/{id}', [KonselingController::class, 'destroy'])->name('konseling.destroy');
    Route::get('/konseling/{id}', [KonselingController::class, 'show'])->name('konseling.show');
     Route::put('konseling/{id}/proses', [KonselingController::class, 'proses'])->name('konseling.proses');

    // ⏰ Keterlambatan
    Route::get('/keterlambatan', [KeterlambatanController::class, 'index'])->name('keterlambatan.index');
    Route::get('/keterlambatan/rekap-bulanan', [KeterlambatanController::class, 'rekapBulanan'])->name('keterlambatan.rekapBulanan');
    Route::get('/keterlambatan/create', [KeterlambatanController::class, 'create'])->name('keterlambatan.create');
    Route::post('/keterlambatan/store', [KeterlambatanController::class, 'store'])->name('keterlambatan.store');
    Route::get('/keterlambatan/cetak/{id}', [KeterlambatanController::class, 'cetakSurat'])->name('keterlambatan.cetak');
    Route::put('/admin/keterlambatan/{id}/status', [KeterlambatanController::class, 'updateStatus'])->name('keterlambatan.updateStatus');

    // 📄 Roles
    Route::resource('role', RoleController::class);
    Route::post('/role-koor-kelas/assign', [RoleController::class, 'assignKoorByNis'])->name('role.assignKoor');
    Route::middleware(['kesiswaan.readonly'])->group(function () {
    
});
    // dokumen siswa
    Route::resource('dokumensiswa', DokumenSiswaController::class);
    Route::post('dokumensiswa/ingatkan/{nis}', [App\Http\Controllers\Admin\DokumenSiswaController::class, 'kirimPeringatan'])->name('dokumensiswa.ingatkan');

    //Ubah Password Siswa
    Route::prefix('password')->name('password.')->group(function () {

    Route::get('/', [UserPasswordController::class, 'index'])
        ->name('index');

    Route::get('/edit/{type}/{id}', [UserPasswordController::class, 'edit'])
        ->name('edit');

    Route::post('/update/{type}/{id}', [UserPasswordController::class, 'update'])
        ->name('update');
    Route::post('/update-self', [UserPasswordController::class, 'updateSelf'])->name('updateSelf');

});

// 📌 PRESTASI SISWA (ADMIN)
Route::prefix('prestasi')->name('prestasi.')->group(function () {
    Route::get('/', [PrestasiController::class, 'index'])->name('index');
    Route::get('/create', [PrestasiController::class, 'create'])->name('create');
    Route::post('/store', [PrestasiController::class, 'store'])->name('store');
    Route::get('/{id}', [PrestasiController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [PrestasiController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PrestasiController::class, 'update'])->name('update');
    Route::delete('/{id}', [PrestasiController::class, 'destroy'])->name('destroy');
});

//Naik Kelas 
    Route::put('/naikkan-rombel/{rombel}', [SiswaController::class, 'naikkanRombel'])->name('naikkanRombel');
    Route::put('/naikkan-siswa/{nis}', [SiswaController::class, 'naikkanSiswa'])->name('naikkanSiswa');

});

/*
|--------------------------------------------------------------------------
| 📋 PRESENSI (khusus Admin & Kesiswaan — TIDAK termasuk guru_bk)
|--------------------------------------------------------------------------
*/
Route::prefix('admin/presensi')->name('admin.presensi.')
    ->middleware(['auth:guru', 'role:admin,kesiswaan'])
    ->group(function () {
        Route::get('/', [PresensiController::class, 'dashboard'])->name('dashboard');
        Route::get('/kelas/{rombel}', [PresensiController::class, 'kelas'])->name('kelas');
        Route::post('/kelas/{rombel}', [PresensiController::class, 'simpan'])->name('simpan');
        Route::get('/rekap-bulanan', [PresensiController::class, 'rekapBulanan'])->name('rekapBulanan');
        Route::get('/rekap-bulanan/export', [PresensiController::class, 'exportRekapBulanan'])->name('rekapBulanan.export');
        Route::get('/rekap-total', [PresensiController::class, 'rekapTotal'])->name('rekapTotal');
        Route::get('/rekap-total/export', [PresensiController::class, 'exportRekapTotal'])->name('rekapTotal.export');
    });

/*
|--------------------------------------------------------------------------
| 👨‍🏫 BK AREA (Guru BK)
|--------------------------------------------------------------------------
*/
Route::prefix('bk')->name('bk.')
    ->middleware(['auth:guru', 'role:guru_bk'])
    ->group(function () {

    // Dashboard BK
    Route::get('/dashboard', [DashboardBKController::class, 'index'])
        ->name('dashboard');

    // Konseling (BK mengelola pengajuan siswa)
    Route::resource('konseling', KonselingController::class);

    Route::put('/konseling/{id}/proses',
        [KonselingController::class, 'proses']
    )->name('konseling.proses');

    //Keterlambatan (BK Mengelola Pengajuan Keterlambatan)
    Route::resource('keterlambatan', KeterlambatanController::class);

    Route::put('/keterlambatan/{id}/proses',
        [KeterlambatanController::class, 'proses']
    )->name('keterlambatan.proses');

    // Dokumen siswa (read only)
    Route::get('/dokumen', [DokumenSiswaController::class, 'index'])
        ->name('dokumen.index');
    
     // Prestasi siswa (read only)
    Route::get('/prestasi', [PrestasiController::class, 'index'])
        ->name('prestasi.index');

});

/*
|--------------------------------------------------------------------------
| 👨‍🏫 Kesiswaan Area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:guru', 'role:kesiswaan'])
    ->prefix('kesiswaan')
    ->name('kesiswaan.')
    ->group(function () {

    Route::get('/dashboard', [KesiswaanDashboardController::class, 'index'])
        ->name('dashboard');

});

/*
|--------------------------------------------------------------------------
| 👨‍🏫 Wali Kelas Area
|--------------------------------------------------------------------------
*/

Route::prefix('wali')
    ->name('wali.')
    ->middleware(['auth:guru', 'walikelas'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardWaliController::class, 'dashboard'])->name('dashboard');

        // Data siswa wali kelas
        Route::get('/datasiswa', [WaliSiswaController::class, 'index'])->name('datasiswa');
        Route::get('/datasiswa/{nis}/edit', [WaliSiswaController::class, 'edit'])->name('datasiswa.edit');
        Route::put('/datasiswa/{nis}', [WaliSiswaController::class, 'update'])->name('datasiswa.update');
        Route::delete('/datasiswa/{nis}', [WaliSiswaController::class, 'destroy'])->name('datasiswa.destroy');
        Route::post('/datasiswa/{nis}/toggle', [WaliSiswaController::class, 'toggleAkses']) ->name('datasiswa.toggleAkses');
        Route::post('/datasiswa/{nis}/reset-password', [WaliSiswaController::class, 'resetPassword'])->name('datasiswa.resetPassword');
        Route::get('/datasiswa/{nis}', [WaliSiswaController::class, 'show'])->name('datasiswa.show');

        // 📌 KARTU PELAJAR WALI KELAS
        Route::prefix('/kartu-pelajar')->name('kartupelajar.')->group(function () {
        Route::get('/', [WaliKartuPelajarController::class, 'index'])->name('index');
        Route::get('/preview/{nis}', [WaliKartuPelajarController::class, 'preview'])->name('preview');
        Route::get('/frame/{nis}', [WaliKartuPelajarController::class, 'frame'])->name('frame');

    });

        // Dokumen Siswa
        Route::get('/dokumensiswa', [DokumenSiswaWaliController::class, 'index'])->name('dokumensiswa');
        Route::get('/dokumensiswa/{nis}', [DokumenSiswaWaliController::class, 'show'])->name('dokumensiswa.show');
        Route::get('/dokumensiswa/{nis}/edit', [DokumenSiswaWaliController::class, 'edit'])->name('dokumensiswa.edit');
        Route::put('/dokumensiswa/{nis}', [DokumenSiswaWaliController::class, 'update'])->name('dokumensiswa.update');
        Route::delete('/dokumensiswa/{nis}', [DokumenSiswaWaliController::class, 'destroy'])->name('dokumensiswa.destroy');

        // Kelola Password (opsional pake default)
        Route::prefix('password')->name('password.')->group(function () {
        Route::get('/', [UserPasswordWaliController::class, 'index'])->name('index');
        Route::get('/{nis}/edit', [UserPasswordWaliController::class, 'edit'])->name('edit');
        Route::post('/{nis}/update', [UserPasswordWaliController::class, 'update'])->name('update');
        Route::post('/update-self', [UserPasswordWaliController::class, 'updateSelf'])->name('updateSelf');
    });

        // Naik Kelas Rombel
        Route::post('/datasiswa/naikkan-semua', [WaliSiswaController::class, 'naikkanSemua'])->name('datasiswa.naikkanSemua');
        Route::post('/datasiswa/{nis}/naikkan', [WaliSiswaController::class, 'naikkanSatu'])->name('datasiswa.naikkanSatu');

        // 📌 PRESTASI SISWA WALI KELAS
        Route::prefix('prestasi')->name('prestasi.')->group(function () {
            Route::get('/', [WaliPrestasiController::class, 'index'])->name('index');
            Route::get('/create', [WaliPrestasiController::class, 'create'])->name('create');
            Route::post('/', [WaliPrestasiController::class, 'store'])->name('store');
            Route::get('/{nis}', [WaliPrestasiController::class, 'show'])->name('show');
            Route::delete('/{id}/delete', [WaliPrestasiController::class, 'destroy'])->name('destroy');
        });

    });

// ------------------------------------------------------------
// 👨‍🏫 GURU BIASA (Role: guru)
// ------------------------------------------------------------
Route::prefix('guru')->name('guru.')
    ->middleware(['auth:guru', 'role:guru'])
    ->group(function () {

    Route::get('/dashboard', function () {
        return view('guru.dashboard'); // nanti kamu buat view-nya
    })->name('dashboard');

});


/*
|--------------------------------------------------------------------------
| 🎓 SISWA AREA (Dashboard & Halaman Siswa)
|--------------------------------------------------------------------------
*/
Route::prefix('siswa')->name('siswa.')->middleware(['auth:siswa'])->group(function () {
    Route::get('/dashboard', [DashboardSiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/datasiswa', [DashboardSiswaController::class, 'dataSiswa'])->name('datasiswa');
    Route::get('/orangtua', [DashboardSiswaController::class, 'dataOrangtua'])->name('orangtua');
        Route::post('/notifikasi-baca/{id}', [DashboardSiswaController::class, 'bacaPeringatan'])->name('baca-peringatan');


    Route::get('/profil', [SiswaDataController::class, 'show'])->name('profil.show');
    Route::get('/profil/{nis}/edit', [SiswaDataController::class, 'edit'])->name('profil.edit');
    Route::put('/profil/{nis}/update', [SiswaDataController::class, 'update'])->name('profil.update');

    Route::get('/ortu/{nis}', [OrtuController::class, 'show']) ->name('ortu.show');
    Route::get('/ortu/{nis}/edit', [OrtuController::class, 'edit'])->name('ortu.edit');
    Route::put('/ortu/{nis}/update', [OrtuController::class, 'update']) ->name('ortu.update');

    //Kartu Pelajar Siswa 
    Route::get('/kartu-pelajar', [DashboardSiswaController::class, 'kartuPelajar'])->name('kartupelajar.index');
    Route::get('/kartu-pelajar/frame/{nis}', [DashboardSiswaController::class, 'frame'])->name('kartupelajar.frame');

    // Konseling Siswa
    Route::get('/konseling', [KonselingsiswaController::class, 'index'])->name('konseling.index');
    Route::get('/konseling/create', [KonselingsiswaController::class, 'create'])->name('konseling.create');
    Route::post('/konseling', [KonselingsiswaController::class, 'store'])->name('konseling.store');
    Route::get('/konseling/{id}', [KonselingsiswaController::class, 'show'])->name('konseling.show');
    Route::get('/konseling/{id}/edit', [KonselingsiswaController::class, 'edit'])->name('konseling.edit');

    //Keterlambatan Siswa 
    Route::get('/keterlambatan', [KeterlambatanSiswaController::class, 'index'])->name('keterlambatan.index');
    Route::post('/keterlambatan/ajukan', [KeterlambatanSiswaController::class, 'ajukan'])->name('keterlambatan.ajukan');
    Route::get('/cetak-sit/{id}', [KeterlambatanSiswaController::class, 'cetakSIT'])->name('cetak.sit');


    //Dokumen Siswa
    Route::get('/dokumensiswa', [DokumenController::class, 'index'])->name('dokumensiswa');
    Route::post('/dokumensiswa/upload/{id}', [DokumenController::class, 'upload'])->name('dokumensiswa.upload');

    //Ubah dan Lupa Password Siswa 
    Route::get('/ubah-password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::post('/ubah-password', [PasswordController::class, 'update'])->name('password.update');
     // 📌 Prestasi Siswa
    Route::get('/prestasi', [SiswaPrestasiController::class, 'index'])->name('prestasi.index');
    Route::post('/prestasi/store', [SiswaPrestasiController::class, 'store'])->name('prestasi.store');
    Route::get('/{id}/edit', [SiswaPrestasiController::class, 'edit'])->name('prestasi.edit');
    Route::put('/{id}', [SiswaPrestasiController::class, 'update'])->name('prestasi.update');
    Route::delete('/{id}', [SiswaPrestasiController::class, 'destroy'])->name('prestasi.destroy');

    // 📋 Presensi — riwayat & upload bukti (semua siswa)
    Route::get('/presensi', [PresensiSayaController::class, 'index'])->name('presensi.index');
    Route::post('/presensi/{id}/upload-bukti', [PresensiSayaController::class, 'uploadBukti'])->name('presensi.uploadBukti');

    // 📋 Presensi — khusus Koor Kelas
    Route::middleware(['role:koor_kelas'])->prefix('presensi-koor')->name('presensi.koor.')->group(function () {
        Route::get('/', [KoorPresensiController::class, 'create'])->name('create');
        Route::post('/', [KoorPresensiController::class, 'store'])->name('store');
        Route::get('/riwayat', [KoorPresensiController::class, 'riwayat'])->name('riwayat');
    });
});



