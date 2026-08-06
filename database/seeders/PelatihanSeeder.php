<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelatihan;

class PelatihanSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks before truncating to prevent constraints failure
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Pelatihan::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $pelatihans = [
            [
                'judul' => 'Digital Marketing & Social Media Branding untuk Pemula',
                'banner' => null,
                'deskripsi' => 'Pelatihan intensif bagi pelaku UMKM di Mandalajati untuk mengoptimalkan penggunaan Instagram, TikTok, dan Facebook Ads guna melipatgandakan penjualan.',
                'tanggal_mulai' => now()->addDays(5)->setTime(9, 0, 0),
                'tanggal_selesai' => now()->addDays(5)->setTime(16, 0, 0),
                'lokasi' => 'Aula Kantor Kecamatan Mandalajati',
                'kuota' => 50,
                'syarat_dokumen' => '["KTP", "NIB/Surat Keterangan Usaha"]',
                'status' => 'published',
            ],
            [
                'judul' => 'Sertifikasi Halal & Higienitas Produk Makanan',
                'banner' => null,
                'deskripsi' => 'Bimbingan teknis dan tata cara pendaftaran Sertifikasi Halal gratis (Sehati) serta standar pengolahan makanan higienis untuk menjaga kepercayaan konsumen.',
                'tanggal_mulai' => now()->addDays(12)->setTime(8, 30, 0),
                'tanggal_selesai' => now()->addDays(12)->setTime(15, 0, 0),
                'lokasi' => 'Aula Kelurahan Jatihandap',
                'kuota' => 30,
                'syarat_dokumen' => '["KTP", "Foto Produk"]',
                'status' => 'published',
            ],
            [
                'judul' => 'Pelatihan Pembukuan Keuangan Digital dengan Aplikasi Smartphone',
                'banner' => null,
                'deskripsi' => 'Pelajari cara mencatat pengeluaran, pemasukan, dan membuat laporan keuangan bulanan sederhana untuk UMKM menggunakan aplikasi BukuKas/BukuWarung.',
                'tanggal_mulai' => now()->addDays(20)->setTime(9, 0, 0),
                'tanggal_selesai' => now()->addDays(20)->setTime(12, 0, 0),
                'lokasi' => 'Ruang Rapat Kelurahan Sindangjaya',
                'kuota' => 25,
                'syarat_dokumen' => '["KTP"]',
                'status' => 'published',
            ]
        ];

        foreach ($pelatihans as $p) {
            Pelatihan::create($p);
        }
    }
}
