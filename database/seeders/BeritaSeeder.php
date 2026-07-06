<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Berita::truncate();

        $berita = [
            [
                'judul' => 'Pendaftaran NIB Gratis untuk UMKM se-Kecamatan Mandalajati',
                'konten' => 'Dalam upaya mempermudah legalitas usaha, Kecamatan Mandalajati menyelenggarakan program pembuatan Nomor Induk Berusaha (NIB) secara gratis bagi 500 pelaku UMKM pertama yang mendaftar melalui portal Mandalaloka. Proses ini dibantu langsung oleh petugas pendamping UMKM.',
                'penulis' => 'Humas Kecamatan',
                'published_at' => now()->subDays(1),
            ],
            [
                'judul' => 'Festival Kuliner Jatihandap Sukses Tarik Ribuan Pengunjung',
                'konten' => 'Festival jajanan pasar dan kuliner khas yang diselenggarakan di Kelurahan Jatihandap akhir pekan lalu berhasil meraup omzet hingga puluhan juta rupiah. Acara ini menjadi wadah bagi UMKM kuliner lokal untuk memperkenalkan produk mereka ke masyarakat luas.',
                'penulis' => 'Tim Pembina UMKM',
                'published_at' => now()->subDays(3),
            ],
            [
                'judul' => 'Sosialisasi Sertifikasi Halal bagi Pelaku Usaha Sindangjaya',
                'konten' => 'Menyambut kebijakan kewajiban sertifikat halal, jajaran Kelurahan Sindangjaya bersama Kemenag Kota Bandung memberikan penyuluhan tata cara pengajuan sertifikasi halal. Ratusan peserta yang didominasi pengusaha makanan ringan antusias mengikuti acara ini.',
                'penulis' => 'Admin Mandalaloka',
                'published_at' => now()->subDays(5),
            ],
            [
                'judul' => 'Pelatihan Pemasaran Digital Cerdas untuk Pengrajin Mandalajati',
                'konten' => 'Kecamatan Mandalajati bekerjasama dengan platform e-commerce nasional mengadakan workshop pemasaran digital. Pelaku UMKM kerajinan tangan diajarkan cara memotret produk dengan smartphone dan mengoptimalkan deskripsi produk agar menarik pembeli online.',
                'penulis' => 'Humas Kecamatan',
                'published_at' => now()->subDays(7),
            ],
            [
                'judul' => 'Kunjungan Wali Kota Tinjau Sentra Konveksi Karangpamulang',
                'konten' => 'Wali Kota Bandung melakukan kunjungan kerja ke sentra konveksi dan fesyen di Kelurahan Karangpamulang. Beliau mengapresiasi daya tahan UMKM fesyen Mandalajati yang mampu terus menyerap tenaga kerja lokal di tengah tantangan ekonomi global.',
                'penulis' => 'Tim Pemberitaan',
                'published_at' => now()->subDays(8),
            ],
            [
                'judul' => 'Pembinaan Manajemen Keuangan Dasar bagi Pemilik Warung',
                'konten' => 'Banyak pelaku usaha mikro yang masih mencampuradukkan keuangan pribadi dan usaha. Oleh karena itu, Mandalaloka menggelar kelas dasar pembukuan untuk pemilik toko kelontong dan warung makan agar arus kas mereka lebih sehat.',
                'penulis' => 'Tim Pembina UMKM',
                'published_at' => now()->subDays(10),
            ],
            [
                'judul' => 'Kolaborasi Mandalaloka dan Bank Daerah Salurkan KUR',
                'konten' => 'Guna mengatasi masalah permodalan, Pemerintah Kecamatan Mandalajati memfasilitasi pertemuan antara pengusaha kecil dengan pihak Bank Daerah. Penyaluran Kredit Usaha Rakyat (KUR) difokuskan pada UMKM yang telah terverifikasi di Mandalaloka.',
                'penulis' => 'Admin Mandalaloka',
                'published_at' => now()->subDays(12),
            ],
            [
                'judul' => 'Pameran Produk Unggulan Mandalajati di Cihampelas Walk',
                'konten' => 'Sebanyak 20 UMKM terpilih dari Kecamatan Mandalajati berkesempatan memamerkan produk unggulan mereka di mall terkemuka Cihampelas Walk (Ciwalk). Produk yang dipamerkan meliputi rajutan, kriya bambu, hingga camilan kemasan premium.',
                'penulis' => 'Humas Kecamatan',
                'published_at' => now()->subDays(15),
            ],
            [
                'judul' => 'Workshop Desain Kemasan Menarik Tingkatkan Nilai Jual',
                'konten' => 'Kemasan adalah ujung tombak pemasaran. Pelaku UMKM Pasir Impun mendapatkan pelatihan khusus tentang desain kemasan dan pemilihan material ramah lingkungan yang tidak hanya menjaga kualitas produk, tetapi juga memanjakan mata pembeli.',
                'penulis' => 'Tim Pembina UMKM',
                'published_at' => now()->subDays(18),
            ],
            [
                'judul' => 'Program Bantuan Gerobak Usaha untuk Pedagang Kaki Lima',
                'konten' => 'Sebanyak 15 gerobak usaha baru telah diserahkan kepada pedagang kaki lima di sekitar jalan AH Nasution, wilayah Mandalajati. Program ini merupakan inisiatif dari Corporate Social Responsibility (CSR) perusahaan swasta setempat.',
                'penulis' => 'Admin Mandalaloka',
                'published_at' => now()->subDays(20),
            ],
            [
                'judul' => 'Komunitas Kopi Mandalajati Rilis Varian Blend Lokal',
                'konten' => 'Beberapa kedai kopi lokal (coffee shop) di Mandalajati berkolaborasi menciptakan varian beans blend khas daerah. Inovasi ini diharapkan mampu menarik penikmat kopi dari seluruh penjuru Kota Bandung untuk berkunjung ke Mandalajati.',
                'penulis' => 'Tim Pemberitaan',
                'published_at' => now()->subDays(22),
            ],
            [
                'judul' => 'Evaluasi Kinerja Pendamping UMKM Kuartal Pertama 2026',
                'konten' => 'Pemerintah Kecamatan mengadakan rapat evaluasi untuk para pendamping UMKM. Fokus utama adalah peningkatan jumlah pendaftaran legalitas usaha dan keberhasilan implementasi program Mandalaloka di setiap Rukun Warga (RW).',
                'penulis' => 'Humas Kecamatan',
                'published_at' => now()->subDays(25),
            ],
            [
                'judul' => 'Sinergi Karang Taruna Pasir Impun Dorong Usaha Kreatif',
                'konten' => 'Karang Taruna Kelurahan Pasir Impun sukses meluncurkan wadah inkubator bagi pemuda yang ingin merintis usaha kreatif seperti sablon digital, desain grafis, dan kerajinan resin. Dukungan alat produksi juga telah disalurkan.',
                'penulis' => 'Tim Pembina UMKM',
                'published_at' => now()->subDays(26),
            ],
            [
                'judul' => 'Bazar Sembako Murah dan Ekspo UMKM di Halaman Kecamatan',
                'konten' => 'Menjelang hari raya, warga Mandalajati dimanjakan dengan bazar sembako murah yang dibarengi dengan ekspo produk-produk UMKM lokal. Acara ini berhasil meningkatkan daya beli dan sirkulasi ekonomi di tingkat kecamatan.',
                'penulis' => 'Admin Mandalaloka',
                'published_at' => now()->subDays(28),
            ],
            [
                'judul' => 'Mandalaloka Tembus Angka 1000 UMKM Terdaftar',
                'konten' => 'Sebuah pencapaian luar biasa! Portal Mandalaloka resmi mencatat lebih dari 1000 UMKM yang beroperasi di Kecamatan Mandalajati telah mendaftar dan terverifikasi. Ini menjadi pijakan kuat untuk pembinaan yang lebih terukur dan masif.',
                'penulis' => 'Tim Pemberitaan',
                'published_at' => now()->subDays(30),
            ]
        ];

        foreach ($berita as $item) {
            \App\Models\Berita::create([
                'judul' => $item['judul'],
                'konten' => $item['konten'],
                'gambar' => 'news_default.jpg',
                'penulis' => $item['penulis'],
                'status' => 'published',
                'published_at' => $item['published_at'],
            ]);
        }
    }
}
