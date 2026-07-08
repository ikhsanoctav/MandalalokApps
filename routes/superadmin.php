<?php

use App\Http\Controllers\SuperAdmin\BackupController;
use App\Http\Controllers\SuperAdmin\BeritaController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\KategoriController;
use App\Http\Controllers\SuperAdmin\KelurahanController;
use App\Http\Controllers\SuperAdmin\PengajuanController;
use App\Http\Controllers\SuperAdmin\RwController;
use App\Http\Controllers\SuperAdmin\SektorController;
use App\Http\Controllers\SuperAdmin\SystemController;
use App\Http\Controllers\SuperAdmin\UmkmController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\FlyerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');

    // UMKM
    Route::get('/umkm', [UmkmController::class, 'umkm'])->name('umkm');
    Route::get('/umkm/export', [UmkmController::class, 'umkmExport'])->name('umkm.export');
    Route::get('/umkm/create', [UmkmController::class, 'umkmCreate'])->name('umkm.create');
    Route::post('/umkm/import', [UmkmController::class, 'umkmImport'])->name('umkm.import');
    Route::get('/umkm/import/template', [UmkmController::class, 'umkmImportTemplate'])->name('umkm.import.template');
    Route::post('/umkm', [UmkmController::class, 'umkmStore'])->name('umkm.store');
    Route::get('/umkm/{id}/edit', [UmkmController::class, 'umkmEdit'])->name('umkm.edit');
    Route::put('/umkm/{id}', [UmkmController::class, 'umkmUpdate'])->name('umkm.update');
    Route::delete('/umkm/{id}', [UmkmController::class, 'umkmDestroy'])->name('umkm.destroy');
    Route::get('/umkm/{id}/show', [UmkmController::class, 'umkmShow'])->name('umkm.show');
    Route::get('/umkm/{id}/print-qr', [UmkmController::class, 'printQr'])->name('umkm.print-qr');
    Route::get('/umkm/{id}/print-dokumen', [App\Http\Controllers\SuperAdmin\UmkmController::class, 'printDokumen'])->name('umkm.print-dokumen');
    Route::get('/umkm/{id}/modal', [UmkmController::class, 'umkmModal'])->name('umkm.modal');
    Route::post('/umkm/{id}/suspend', [UmkmController::class, 'umkmSuspend'])->name('umkm.suspend');
    Route::post('/umkm/{id}/verify', [UmkmController::class, 'umkmVerify'])->name('umkm.verify');
    // Verifikasi Akun (KTP)
    Route::get('/verifikasi-akun', [App\Http\Controllers\Admin\VerifikasiAkunController::class, 'index'])->name('verifikasi_akun.index');
    Route::get('/verifikasi-akun/{id}', [App\Http\Controllers\Admin\VerifikasiAkunController::class, 'show'])->name('verifikasi_akun.show');
    Route::post('/verifikasi-akun/{id}/verify', [App\Http\Controllers\Admin\VerifikasiAkunController::class, 'verify'])->name('verifikasi_akun.verify');
    Route::post('/verifikasi-akun/{id}/reject', [App\Http\Controllers\Admin\VerifikasiAkunController::class, 'reject'])->name('verifikasi_akun.reject');
    Route::get('/verifikasi-akun/{id}/ktp', [App\Http\Controllers\Admin\VerifikasiAkunController::class, 'showKtp'])->name('verifikasi_akun.ktp');

    // Pengajuan
    Route::get('/pengajuan', [PengajuanController::class, 'pengajuan'])->name('pengajuan');
    Route::post('/pengajuan', [PengajuanController::class, 'pengajuanStore'])->name('pengajuan.store');
    Route::get('/pengajuan/{id}/modal', [PengajuanController::class, 'pengajuanModal'])->name('pengajuan.modal');
    Route::post('/pengajuan/{id}/status', [PengajuanController::class, 'pengajuanUpdateStatus'])->name('pengajuan.status');

    // User Management (static paths before {id})
    Route::get('/users', [UserController::class, 'users'])->name('users');
    Route::get('/users/create', [UserController::class, 'userCreate'])->name('users.create');
    Route::post('/users', [UserController::class, 'userStore'])->name('users.store');
    Route::get('/users/roles', [UserController::class, 'rolesIndex'])->name('users.roles');
    Route::post('/users/roles', [UserController::class, 'rolesUpdate'])->name('users.roles.update');
    Route::get('/users/{id}/edit', [UserController::class, 'userEdit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'userUpdate'])->name('users.update');
    Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::delete('/users/{id}', [UserController::class, 'userDestroy'])->name('users.destroy');

    // Master Data (Kategori & Sektor)
    Route::post('/kategori/import', [KategoriController::class, 'import'])->name('kategori.import');
    Route::get('/kategori/import/template', [KategoriController::class, 'template'])->name('kategori.import.template');
    Route::resource('/kategori', KategoriController::class);

    Route::post('/sektor/import', [SektorController::class, 'import'])->name('sektor.import');
    Route::get('/sektor/import/template', [SektorController::class, 'template'])->name('sektor.import.template');
    Route::resource('/sektor', SektorController::class);

    // Master Data (Wilayah)
    Route::post('/kelurahan/import', [KelurahanController::class, 'import'])->name('kelurahan.import');
    Route::get('/kelurahan/import/template', [KelurahanController::class, 'template'])->name('kelurahan.import.template');
    Route::resource('/kelurahan', KelurahanController::class);
    Route::resource('/rw', RwController::class);

    // Backup & Restore
    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/run', [BackupController::class, 'create'])->name('backup.create');
    Route::get('/backup/download/{file_name}', [BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/delete/{file_name}', [BackupController::class, 'delete'])->name('backup.delete');

    // Berita / Warta
    Route::resource('/berita', BeritaController::class);

    // Flyer Program Bantuan
    Route::get('/flyer', [FlyerController::class, 'index'])->name('flyer.index');
    Route::post('/flyer', [FlyerController::class, 'update'])->name('flyer.update');

    // Laporan
    Route::get('/laporan', [SystemController::class, 'laporan'])->name('laporan');
    Route::post('/laporan/export', [SystemController::class, 'laporanExport'])->name('laporan.export');

    // Logs & Settings
    Route::get('/logs', [SystemController::class, 'logs'])->name('logs');
    Route::get('/settings', [SystemController::class, 'settings'])->name('settings');
    Route::post('/settings', [SystemController::class, 'settingsUpdate'])->name('settings.update');
    Route::post('/settings/kop-surat', [SystemController::class, 'settingsKopSurat'])->name('settings.kop_surat');
    // Pengaduan Masyarakat (Sentimen AI)
    Route::get('/pengaduan', [\App\Http\Controllers\Superadmin\PengaduanController::class, 'index'])->name('pengaduan.index');
    Route::post('/pengaduan/{id}/status', [\App\Http\Controllers\Superadmin\PengaduanController::class, 'updateStatus'])->name('pengaduan.status');

    // DSS Mandalaloka (Algoritma SAW)
    Route::get('/dss-saw', [\App\Http\Controllers\Superadmin\DssController::class, 'index'])->name('dss.saw.index');
    Route::get('/dss-saw/kriteria', [\App\Http\Controllers\Superadmin\DssController::class, 'kriteria'])->name('dss.saw.kriteria');
    Route::post('/dss-saw/kriteria', [\App\Http\Controllers\Superadmin\DssController::class, 'updateKriteria'])->name('dss.saw.update_kriteria');

    // Asisten Eksekutif
    Route::get('/assistant', [\App\Http\Controllers\Superadmin\ExecutiveAssistantController::class, 'index'])->name('assistant.index');
    Route::post('/assistant/chat', [\App\Http\Controllers\Superadmin\ExecutiveAssistantController::class, 'chat'])->name('assistant.chat');
});
