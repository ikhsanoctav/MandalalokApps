<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PublicStatistikController;
use App\Http\Controllers\Api\UmkmApiController;
use App\Http\Controllers\Api\VerifikasiApiController;
use App\Http\Controllers\Api\WilayahApiController;
use App\Http\Controllers\DssController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MandalalokaApk — REST API Routes
|--------------------------------------------------------------------------
| Semua route di sini menggunakan prefix /api secara otomatis
| oleh Laravel. Autentikasi menggunakan Laravel Sanctum (token-based).
|
| File konfigurasi API di Flutter:
| MandalalokaApk/lib/config/api_config.dart
|--------------------------------------------------------------------------
*/

// ─── Public Routes (tanpa autentikasi) ────────────────────────────────────
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('api.login');
Route::get('/chart-data', [PublicStatistikController::class, 'getChartData'])->name('api.public.chart');
Route::get('/statistics', [PublicStatistikController::class, 'getStatistics'])->name('api.public.stats');
Route::get('/map-data', [PublicStatistikController::class, 'getMapData'])->name('api.public.map');
Route::get('/chatbot/context', [PublicStatistikController::class, 'getChatbotContext'])->name('api.chatbot.context');
Route::post('/dss/callback', [DssController::class, 'callback'])->name('api.dss.callback');

// ─── Protected Routes (wajib token Sanctum) ───────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/me', [AuthController::class, 'me'])->name('api.me');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('api.dashboard');

    // ── Master Data (untuk dropdown form) ────────────────────────────────
    Route::get('/wilayah/kelurahan', [WilayahApiController::class, 'kelurahan']);
    Route::get('/wilayah/rw/{kelurahan_id}', [WilayahApiController::class, 'rw']);
    Route::get('/wilayah/rt/{rw_id}', [WilayahApiController::class, 'rt']);
    Route::get('/kategori-umkm', [WilayahApiController::class, 'kategori']);
    Route::get('/sektor-umkm', [WilayahApiController::class, 'sektor']);

    // ── UMKM ─────────────────────────────────────────────────────────────
    Route::get('/umkm', [UmkmApiController::class, 'index']);
    Route::post('/umkm', [UmkmApiController::class, 'store']);
    Route::get('/umkm/{id}', [UmkmApiController::class, 'show']);
    Route::put('/umkm/{id}', [UmkmApiController::class, 'update']);
    Route::post('/umkm/{id}/upload-foto', [UmkmApiController::class, 'uploadFoto']);
    Route::post('/umkm/{id}/input-gps', [UmkmApiController::class, 'inputGps']);

    // ── Verifikasi (Admin Kecamatan) ──────────────────────────────────────
    Route::get('/verifikasi', [VerifikasiApiController::class, 'index']);
    Route::get('/verifikasi/{id}', [VerifikasiApiController::class, 'show']);
    Route::post('/verifikasi/{id}/verify', [VerifikasiApiController::class, 'verify']);
    Route::post('/verifikasi/{id}/reject', [VerifikasiApiController::class, 'reject']);
});
