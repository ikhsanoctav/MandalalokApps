<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\UmkmController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Pelaku\ProfilController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DssController;
use App\Http\Middleware\RoleMiddleware;
use App\Models\Berita;
use App\Models\Kelurahan;
use App\Models\Produk;
use App\Http\Controllers\KatalogUmkmController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    $kelurahans = Kelurahan::orderBy('nama_kelurahan')->get()->map(function($kelurahan) {
        $count = DB::table('umkms')
            ->join('pemiliks', 'umkms.id_pemilik', '=', 'pemiliks.id_pemilik')
            ->where('pemiliks.id_kelurahan', $kelurahan->id)
            ->where('umkms.status_verifikasi', 'terverifikasi')
            ->count();
        $kelurahan->umkm_count = $count;
        return $kelurahan;
    });
    $produkUnggulan = Produk::with(['umkm.sektor', 'umkm.pemilik.kelurahanRel'])
        ->whereHas('umkm', function ($query) {
            $query->terverifikasi()->aktif();
        })
        ->latest()
        ->take(12)
        ->get();

    return view('welcome', compact('kelurahans', 'produkUnggulan'));
})->name('welcome');

Route::get('/fix-dates-secret', function () {
    $umkms = \App\Models\UMKM::all();
    $fixedCount = 0;
    foreach ($umkms as $umkm) {
        $randomTime = mt_rand(strtotime('2024-01-01'), time());
        $umkm->timestamps = false;
        $umkm->created_at = date('Y-m-d H:i:s', $randomTime);
        $umkm->updated_at = date('Y-m-d H:i:s', $randomTime);
        if (in_array($umkm->status_verifikasi, ['terverifikasi', 'ditolak'])) {
            $umkm->tanggal_verifikasi = date('Y-m-d H:i:s', $randomTime + mt_rand(86400, 604800));
        }
        $umkm->save();
        $fixedCount++;
    }
    return 'Berhasil memperbaiki tanggal untuk ' . $fixedCount . ' data UMKM! Silakan kembali ke halaman dashboard.';
});

Route::get('/katalog-umkm', [KatalogUmkmController::class, 'index'])->name('katalog.umkm');
Route::post('/katalog-umkm/{id_umkm}/rate', [KatalogUmkmController::class, 'storeRating'])->name('katalog.umkm.rate');
Route::post('/katalog-umkm/ulasan/{id}/like', [KatalogUmkmController::class, 'likeRating'])->name('katalog.umkm.like');
Route::post('/katalog-umkm/ulasan/{id}/dislike', [KatalogUmkmController::class, 'dislikeRating'])->name('katalog.umkm.dislike');


Route::post('/api/chat', [ChatbotController::class, 'sendMessage'])->name('api.chat')->middleware('web');
Route::get('/api/n8n/umkm-search', [ChatbotController::class, 'searchUmkm'])->name('api.n8n.umkm_search')->middleware('web');

Route::get('/warta', function (\Illuminate\Http\Request $request) {
    $query = Berita::where('status', 'published')
        ->where('published_at', '<=', now());

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
              ->orWhere('konten', 'like', "%{$search}%")
              ->orWhere('penulis', 'like', "%{$search}%")
              ->orWhere('kategori', 'like', "%{$search}%");
        });
    }

    if ($request->filled('kategori')) {
        $query->where('kategori', $request->kategori);
    }

    $beritaList = $query->latest('published_at')->paginate(9)->withQueryString();
    $kategoris = Berita::where('status', 'published')
        ->whereNotNull('kategori')
        ->where('kategori', '!=', '')
        ->distinct()
        ->pluck('kategori');

    return view('warta.index', compact('beritaList', 'kategoris'));
})->name('warta.index');

Route::get('/warta/{id}', function ($id) {
    $berita = Berita::findOrFail($id);

    return view('warta.show', compact('berita'));
})->name('warta.show');



Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('super_admin')) {
        return redirect()->route('superadmin.dashboard');
    } elseif ($user->hasRole('admin_kecamatan')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('operator_lapangan')) {
        return redirect()->route('operator.dashboard');
    } elseif ($user->hasRole('pelaku_umkm')) {
        return redirect()->route('pelaku.dashboard');
    }

    // Fallback: user tanpa role yang dikenali diarahkan ke halaman depan
    return redirect()->route('welcome')
        ->with('info', 'Akun Anda belum memiliki role. Silakan hubungi administrator.');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
    Route::post('/berita/store', [BeritaController::class, 'store'])->name('berita.store');
    Route::delete('/berita/{id}', [BeritaController::class, 'destroy'])->name('berita.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

Route::middleware(['auth', RoleMiddleware::class.':super_admin,admin_kecamatan'])->group(function () {
    Route::get('/dss', [DssController::class, 'index'])->name('dss.index');
    Route::post('/api/dss/generate', [DssController::class, 'generate'])->name('dss.generate');
    Route::get('/dss/status', [DssController::class, 'checkStatus'])->name('dss.status');
    Route::get('/dss/history/{id}', [DssController::class, 'loadHistory'])->name('dss.history.show');
    Route::delete('/dss/history/{id}', [DssController::class, 'deleteHistory'])->name('dss.history.destroy');

    // Adaptive DSS
    Route::get('/dss/adaptive', [\App\Http\Controllers\AdaptiveDssController::class, 'index'])->name('dss.adaptive.index');
    Route::post('/api/dss/adaptive/indicators', [\App\Http\Controllers\AdaptiveDssController::class, 'getIndicators'])->name('dss.adaptive.indicators');
    Route::post('/api/dss/adaptive/score', [\App\Http\Controllers\AdaptiveDssController::class, 'scoreAndRank'])->name('dss.adaptive.score');
    Route::post('/api/dss/adaptive/explain', [\App\Http\Controllers\AdaptiveDssController::class, 'explainRecommendation'])->name('dss.adaptive.explain');

    // AHP-SAW Routes
    Route::get('/dss/kriteria', [\App\Http\Controllers\DssAhpController::class, 'kriteriaIndex'])->name('dss.kriteria.index');
    Route::post('/dss/kriteria', [\App\Http\Controllers\DssAhpController::class, 'storeKriteria'])->name('dss.kriteria.store');
    Route::put('/dss/kriteria/{id}', [\App\Http\Controllers\DssAhpController::class, 'updateKriteria'])->name('dss.kriteria.update');
    Route::delete('/dss/kriteria/{id}', [\App\Http\Controllers\DssAhpController::class, 'deleteKriteria'])->name('dss.kriteria.destroy');

    Route::get('/dss/ahp', [\App\Http\Controllers\DssAhpController::class, 'ahpPembobotan'])->name('dss.ahp.index');
    Route::post('/dss/ahp/calculate', [\App\Http\Controllers\DssAhpController::class, 'calculateAhp'])->name('dss.ahp.calculate');

    Route::post('/dss/saw/calculate', [\App\Http\Controllers\DssAhpController::class, 'sawRanking'])->name('dss.saw.calculate');
    Route::get('/dss/saw/hasil', [\App\Http\Controllers\DssAhpController::class, 'hasilSaw'])->name('dss.saw.hasil');

});

require __DIR__.'/superadmin.php';

/*
|--------------------------------------------------------------------------
| Route Admin Kecamatan
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleMiddleware::class.':admin_kecamatan'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');

    // Data UMKM
    Route::get('/umkm', [UmkmController::class, 'index'])->name('umkm.index');
    Route::get('/umkm/create', [UmkmController::class, 'create'])->name('umkm.create');
    Route::post('/umkm', [UmkmController::class, 'store'])->name('umkm.store');
    Route::get('/umkm/scan-result', [UmkmController::class, 'scanResult'])->name('umkm.scan-result');
    Route::get('/umkm/{id}/modal', [UmkmController::class, 'umkmModal'])->name('umkm.modal');
    Route::get('/umkm/{id}', [UmkmController::class, 'show'])->name('umkm.show');
    Route::get('/umkm/{id}/print-qr', [UmkmController::class, 'printQr'])->name('umkm.print-qr');
    Route::get('/umkm/{id}/print-dokumen', [UmkmController::class, 'printDokumen'])->name('umkm.print-dokumen');
    Route::get('/umkm/{id}/edit', [UmkmController::class, 'edit'])->name('umkm.edit');
    Route::put('/umkm/{id}', [UmkmController::class, 'update'])->name('umkm.update');
    Route::delete('/umkm/{id}', [UmkmController::class, 'destroy'])->name('umkm.destroy');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

    // Verifikasi Data UMKM
    Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
    Route::get('/verifikasi/{id}', [VerifikasiController::class, 'show'])->name('verifikasi.show');
    Route::post('/verifikasi/{id}/verify', [VerifikasiController::class, 'verify'])->name('verifikasi.verify');
    Route::post('/verifikasi/{id}/reject', [VerifikasiController::class, 'reject'])->name('verifikasi.reject');

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

    // Pelatihan
    Route::resource('pelatihan', \App\Http\Controllers\Admin\PelatihanController::class);
    Route::post('pelatihan/{id}/register-user', [\App\Http\Controllers\Admin\PelatihanController::class, 'registerUser'])->name('pelatihan.register_user');
});

/*
|--------------------------------------------------------------------------
| Route Operator Lapangan
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleMiddleware::class.':operator_lapangan'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Petugas\DashboardController::class, 'dashboard'])->name('dashboard');

    // Fitur Input & Pendataan
    Route::get('/umkm/create', [App\Http\Controllers\Petugas\UmkmController::class, 'create'])->name('umkm.create');
    Route::post('/umkm', [App\Http\Controllers\Petugas\UmkmController::class, 'store'])->name('umkm.store');
    Route::get('/umkm', [App\Http\Controllers\Petugas\UmkmController::class, 'index'])->name('umkm.index');
    Route::get('/umkm/scan-result', [App\Http\Controllers\Petugas\UmkmController::class, 'scanResult'])->name('umkm.scan-result');
    Route::get('/umkm/{id}/show', [App\Http\Controllers\Petugas\UmkmController::class, 'show'])->name('umkm.show');
    Route::get('/umkm/{id}/print-qr', [App\Http\Controllers\Petugas\UmkmController::class, 'printQr'])->name('umkm.print-qr');
    Route::get('/umkm/{id}/print-dokumen', [App\Http\Controllers\Petugas\UmkmController::class, 'printDokumen'])->name('umkm.print-dokumen');
    Route::get('/umkm/{id}/edit', [App\Http\Controllers\Petugas\UmkmController::class, 'edit'])->name('umkm.edit');
    Route::put('/umkm/{id}', [App\Http\Controllers\Petugas\UmkmController::class, 'update'])->name('umkm.update');
    Route::get('/umkm/{id}/upload-foto', [App\Http\Controllers\Petugas\UmkmController::class, 'uploadFoto'])->name('umkm.upload-foto');
    Route::post('/umkm/{id}/upload-foto', [App\Http\Controllers\Petugas\UmkmController::class, 'storeUploadFoto'])->name('umkm.upload-foto.store');
    Route::delete('/umkm/{id}/foto-gallery/{index}', [App\Http\Controllers\Petugas\UmkmController::class, 'deleteFotoGallery'])->name('umkm.foto-gallery.delete');


    // Verifikasi Lapangan
    Route::get('/verifikasi', [App\Http\Controllers\Petugas\VerifikasiController::class, 'index'])->name('verifikasi.index');
    Route::get('/verifikasi/{umkmId}', [App\Http\Controllers\Petugas\VerifikasiController::class, 'show'])->name('verifikasi.show');
    Route::get('/verifikasi/{umkmId}/create', [App\Http\Controllers\Petugas\VerifikasiController::class, 'create'])->name('verifikasi.create');
    Route::post('/verifikasi/{umkmId}', [App\Http\Controllers\Petugas\VerifikasiController::class, 'store'])->name('verifikasi.store');

    // Scanner Pelatihan
    Route::get('/pelatihan-scanner', [\App\Http\Controllers\Petugas\PelatihanScannerController::class, 'index'])->name('pelatihan.scanner');
    Route::post('/pelatihan-scanner/scan', [\App\Http\Controllers\Petugas\PelatihanScannerController::class, 'scan'])->name('pelatihan.scanner.scan');
});

/*
|--------------------------------------------------------------------------
| Route Pelaku UMKM
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleMiddleware::class.':pelaku_umkm', 'profil.completed'])->prefix('pelaku')->name('pelaku.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Pelaku\DashboardController::class, 'dashboard'])->name('dashboard');

    // Profil
    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');

    // Pelatihan
    Route::get('/pelatihan', [\App\Http\Controllers\Pelaku\PelatihanController::class, 'index'])->name('pelatihan.index');
    Route::get('/pelatihan/{id}', [\App\Http\Controllers\Pelaku\PelatihanController::class, 'show'])->name('pelatihan.show');
    Route::post('/pelatihan/{id}/daftar', [\App\Http\Controllers\Pelaku\PelatihanController::class, 'daftar'])->name('pelatihan.daftar');

    // Produk
    Route::resource('produk', App\Http\Controllers\Pelaku\ProdukController::class);

    // UMKM
    Route::get('/umkm/create', [App\Http\Controllers\Pelaku\UmkmController::class, 'create'])->name('umkm.create');
    Route::post('/umkm', [App\Http\Controllers\Pelaku\UmkmController::class, 'store'])->name('umkm.store')->middleware('throttle:10,1');
    Route::get('/umkm/{id}/edit', [App\Http\Controllers\Pelaku\UmkmController::class, 'edit'])->name('umkm.edit');
    Route::get('/umkm/{id}/print-qr', [App\Http\Controllers\Pelaku\UmkmController::class, 'printQr'])->name('umkm.print-qr');
    Route::get('/umkm/{id}/print-dokumen', [App\Http\Controllers\Pelaku\UmkmController::class, 'printDokumen'])->name('umkm.print-dokumen');
    Route::put('/umkm/{id}', [App\Http\Controllers\Pelaku\UmkmController::class, 'update'])->name('umkm.update')->middleware('throttle:10,1');
    Route::delete('/umkm/{id}/foto-gallery/{index}', [App\Http\Controllers\Pelaku\UmkmController::class, 'deleteFotoGallery'])->name('umkm.foto-gallery.delete');
    Route::delete('/produk/{id}/foto-produk/{index}', [App\Http\Controllers\Pelaku\ProdukController::class, 'deleteFotoProduk'])->name('produk.foto-produk.delete');

    // Pengajuan Bantuan
    Route::get('/pengajuan', [App\Http\Controllers\Pelaku\PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::post('/pengajuan', [App\Http\Controllers\Pelaku\PengajuanController::class, 'store'])->name('pengajuan.store');
});

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':super_admin,admin_kecamatan,operator_lapangan'])
    ->get('/api/check-nik', [\App\Http\Controllers\Api\PemilikApiController::class, 'checkNik'])
    ->name('api.check-nik');

Route::get('/api/check-nik-register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'checkNik'])
    ->name('api.check-nik-register');

require __DIR__.'/auth.php';
