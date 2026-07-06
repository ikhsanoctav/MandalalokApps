<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = \App\Models\UMKM::where('status_verifikasi', 'terkirim')->update([
    'status_verifikasi' => 'terverifikasi',
    'status_usaha' => 'aktif',
    'tanggal_verifikasi' => now()
]);

echo "Updated $count UMKM from terkirim to terverifikasi.\n";
