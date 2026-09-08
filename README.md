<p align="center">
  <img src="public/images/Logo_Mandalaloka.png" width="130" alt="Logo Mandalaloka">
</p>

<h1 align="center">Mandalaloka Apps</h1>

<p align="center">
  <strong>Sistem Informasi Pendataan, Pemetaan Geospasial, dan Pemberdayaan UMKM Terpadu</strong><br>
  <em>Pemerintah Kecamatan Mandalajati, Kota Bandung, Jawa Barat</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/PHP-8.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.4">
  <img src="https://img.shields.io/badge/MySQL-8.4-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL 8.4">
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/GIS-Leaflet_%26_OSM-199900?style=for-the-badge&logo=leaflet&logoColor=white" alt="Leaflet GIS">
  <img src="https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker Ready">
</p>

---

## 📌 Tentang Mandalaloka

**Mandalaloka** adalah platform web terpadu yang dirancang untuk mendigitalkan seluruh siklus pendataan, pemetaan wilayah, etalase promosi produk, serta fasilitasi program bagi Usaha Mikro, Kecil, dan Menengah (UMKM) di wilayah **Kecamatan Mandalajati, Kota Bandung** (meliputi 5 kelurahan: *Karang Pamulang, Sindangjaya, Cikadut, Pasir Impun, dan Jatihandap*).

Platform ini menghubungkan pihak **Pemerintah Kecamatan**, **Petugas Lapangan (Surveyor)**, **Pelaku Usaha**, dan **Masyarakat Publik** dalam satu ekosistem digital yang transparan dan mudah diakses.

---

## 🌟 Fitur Utama Sistem

```text
🏛️ Ekosistem Mandalaloka
├── 🌐 Portal Publik (Masyarakat & Tamu)
│   ├── 🛍️ Katalog Produk Unggulan UMKM & Rating Review
│   ├── 🗺️ Peta Interaktif Sebaran UMKM (Leaflet & OSM)
│   ├── 📰 Portal Warta & Publikasi Kegiatan Kecamatan
│   └── 💬 Navigasi Rute & Direct Chat WhatsApp Penjual
│
├── 🏪 Portal Pelaku Usaha (Pemilik UMKM)
│   ├── 👤 Manajemen Biodata Diri & Berkas KTP
│   ├── 📦 Manajemen Profil Usaha & Etalase Produk
│   ├── 📑 Pengajuan Program Bantuan Modal
│   ├── 🔍 Pemantauan Status Verifikasi Real-Time
│   └── 🏷️ Generator QR Code & Cetak Lembar Identitas Usaha
│
└── 🛡️ Panel Pengelola (Super Admin, Admin, & Petugas)
    ├── 📊 Dashboard Statistik & Grafik Wilayah Mandalajati
    ├── 🛡️ Pusat Validasi UMKM & Dynamic KTP Watermarking
    ├── 📍 Sensus Lapangan (GPS Tagging & Dokumentasi Foto)
    ├── 📋 Verifikasi Fisik Faktual di Lokasi Usaha
    └── 📑 Ekspor / Impor Rekapitulasi Data (Excel)
```

### 1. 📋 Pendataan & Manajemen Profil UMKM
* **Registrasi Mandiri & Sensus Lapangan**:
  * **Pelaku Usaha**: Mendaftarkan profil usaha, legalitas (NIB, PIRT, Halal), modal, omset, dan titik koordinat secara mandiri.
  * **Petugas Lapangan**: Melakukan sensus langsung di lapangan dengan GPS tagging dan dokumentasi foto tempat usaha.
* **Cetak QR Code & Dokumen Resmi**:
  * Generator **QR Code Usaha** untuk display etalase atau kemasan produk.
  * Cetak **Surat Identitas UMKM Terverifikasi** resmi lengkap dengan Kop Surat Pemerintah Kecamatan Mandalajati.

### 2. 🗺️ Pemetaan Geospasial Interaktif (Leaflet & OpenStreetMap)
* **Peta Sebaran Digital**: Menampilkan titik koordinat lokasi fisik seluruh UMKM di 5 Kelurahan Kecamatan Mandalajati.
* **Marker Clustering**: Pengelompokan visual marker saat peta diperkecil untuk performa cepat dan tampilan bersih.
* **Filter Wilayah & Sektor**: Penyaringan data berdasarkan Kelurahan (*Karang Pamulang, Sindangjaya, Cikadut, Pasir Impun, Jatihandap*) dan Sektor Usaha (*Kuliner, Fashion, Kerajinan, Jasa, Pertanian, dll.*).
* **Hubungi via WhatsApp**: Pop-up marker peta menyediakan informasi profil, alamat, dan tombol direct chat WhatsApp ke pemilik usaha.

### 3. 🛍️ Katalog Produk & Ulasan Komunitas
* **Etalase Produk Terverifikasi**: Menampilkan produk-produk unggulan lokal lengkap dengan foto, deskripsi, harga, dan kontak penjual.
* **Rating Bintang & Review**: Pengunjung publik dapat memberikan rating ulasan (1-5 bintang) serta reaksi *Like / Dislike* pada ulasan pembeli.
* **Pencarian Cepat**: Filter pencarian produk berdasarkan nama, sektor, maupun domisili kelurahan.

### 4. 📰 Portal Warta & Publikasi Kecamatan
* **Berita & Pengumuman Resmi**: Publikasi warta kegiatan kecamatan, bazar, pameran UMKM, dan sosialisasi perizinan usaha.
* **Informasi Program & Regulasi**: Panduan tata cara legalitas (NIB, sertifikasi Halal, BPOM) dan informasi agenda dinas.

### 5. 📑 Pengajuan Program Bantuan Modal & Fasilitasi
* **Pendaftaran Bantuan Online**: Pengunggahan dokumen proposal dan kelengkapan berkas secara mandiri oleh pelaku usaha.
* **Verifikasi Berjenjang**: Peninjauan berkas administratif oleh Admin Kecamatan dan verifikasi fisik lapangan oleh Petugas Lapangan.

### 6. 🛡️ Keamanan Data & Dynamic KTP Watermarking
* **Cek NIK Real-Time**: Validasi NIK otomatis untuk mencegah duplikasi identitas pemilik usaha.
* **Proteksi Watermark KTP**: Berkas scan KTP otomatis dilapisi watermark dinamis nama pemohon dan stempel sistem saat ditinjau Admin guna mencegah penyalahgunaan dokumen identitas.

---

## 👥 Hak Akses & Peran Pengguna (User Roles)

| Peran (Role) | Deskripsi & Tanggung Jawab Utama |
| :--- | :--- |
| 👑 **Super Admin** | Memegang kendali penuh atas sistem, manajemen akun pengguna & hak akses (RBAC), pengelolaan master data wilayah (Kelurahan/RW) dan sektor usaha, pemantauan audit log aktivitas, backup database, serta pengaturan kop surat resmi kecamatan. |
| 🏛️ **Admin Kecamatan** | Bertanggung jawab atas verifikasi identitas akun pendaftar (KTP), validasi pendaftaran data UMKM baru, peninjauan kelengkapan proposal program bantuan modal, publikasi warta kecamatan, dan ekspor rekapitulasi laporan data (Excel). |
| 📍 **Petugas Lapangan** | Bertugas melakukan sensus pendataan UMKM langsung dari lapangan, tagging titik koordinat geospasial (GPS), mengunggah dokumentasi foto fisik usaha/produk, serta melaksanakan verifikasi faktual di lapangan. |
| 🏪 **Pelaku UMKM** | Mengelola kelengkapan biodata diri dan legalitas usaha, mengunggah etalase katalog produk, mengajukan permohonan program bantuan modal, memantau status verifikasi secara real-time, serta mencetak QR Code profil usahanya. |
| 🌐 **Publik (Tamu)** | Menjelajahi etalase produk UMKM lokal, memberikan ulasan & rating bintang, meninjau peta interaktif sebaran UMKM, membaca warta berita kegiatan kecamatan, dan mendaftarkan akun baru. |

---

## 🛠️ Arsitektur Teknologi

* **Backend Framework**: [Laravel 13](https://laravel.com/) (PHP 8.4+)
* **Basis Data**: [MySQL 8.4](https://www.mysql.com/)
* **Frontend UI**: Blade Modular Partials, [Tailwind CSS](https://tailwindcss.com/), [Alpine.js](https://alpinejs.dev/), [Chart.js](https://www.chartjs.org/)
* **Peta & GIS**: [Leaflet.js](https://leafletjs.com/) & [OpenStreetMap](https://www.openstreetmap.org/)
* **Manajemen Peran**: [Spatie Laravel-Permission](https://spatie.be/docs/laravel-permission/)
* **Keamanan Dokumen**: Dynamic Image Watermarking, Hash SHA-256 NIK, CSRF Protection
* **Mobile REST API**: Laravel Sanctum (Token Authentication untuk aplikasi mobile Flutter `MandalalokaApk`)
* **DevOps & Lingkungan**: Docker, Docker Compose, Laravel Sail, Nginx Reverse Proxy (Gzip & Security Headers)

---

## 🌐 Akses Sistem (Live Deployment)

Platform Mandalaloka telah di-deploy dan dapat diakses secara langsung:
* 🔗 **Portal Utama Mandalaloka**: [http://103.89.4.245/](http://103.89.4.245/)
* 🛍️ **Katalog Produk UMKM**: [http://103.89.4.245/katalog-umkm](http://103.89.4.245/katalog-umkm)
* 📰 **Portal Warta & Informasi**: [http://103.89.4.245/warta](http://103.89.4.245/warta)

---

<details>
<summary>🛠️ <strong>Panduan Instalasi & Menjalankan di Lokal (Developer Guide - Klik untuk Membuka)</strong></summary>

<br>

### 🐳 Menggunakan Docker / Laravel Sail (Rekomendasi)

1. **Clone Repositori:**
   ```bash
   git clone https://github.com/ikhsanoctav/MandalalokApps.git
   cd MandalalokApps
   ```

2. **Setup File Konfigurasi Environment:**
   ```bash
   cp .env.example .env
   ```

3. **Jalankan Container Docker:**
   ```bash
   ./vendor/bin/sail up -d
   ```

4. **Install Dependensi & Generate Key:**
   ```bash
   ./vendor/bin/sail composer install
   ./vendor/bin/sail artisan key:generate
   ```

5. **Jalankan Migrasi Database & Seeder:**
   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```

6. **Buat Symlink Penyimpanan Berkas:**
   ```bash
   ./vendor/bin/sail artisan storage:link
   ```

7. **Kompilasi Asset Frontend:**
   ```bash
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run dev
   ```

8. **Akses Aplikasi Lokal:**
   * **Web Portal:** `http://localhost:8080` (atau `http://localhost`)
   * **phpMyAdmin:** `http://localhost:8081`

</details>

<details>
<summary>🧪 <strong>Pengujian Sistem (Automated Testing - Klik untuk Membuka)</strong></summary>

<br>

Aplikasi dilengkapi dengan rangkaian automated test suite (*Feature & Unit Tests*) berbasis PHPUnit:

```bash
# Menjalankan seluruh pengujian fitur dan unit
./vendor/bin/sail test
```

Tersedia juga modul skenario pengujian otomatis end-to-end:
* **Katalon Studio**: Direktori `katalon/`
* **Selenium WebDriver**: Direktori `selenium/`

</details>

---

## 📁 Struktur Direktori Proyek

```text
MandalalokaApps/
├── app/
│   ├── Http/Controllers/
│   │   ├── SuperAdmin/          # Kontrol Master Data, Users, Logs, Settings, Kop Surat
│   │   ├── Admin/               # Verifikasi Akun KTP, Validasi UMKM, Bantuan
│   │   ├── Petugas/             # Pendataan Lapangan, Verifikasi Fisik Lapangan
│   │   ├── Pelaku/              # Profil Pemilik, Registrasi Usaha, Produk, Pengajuan
│   │   └── Api/                 # Endpoint REST API (Statistik, Peta, Mobile)
│   ├── Models/                  # Model Eloquent (UMKM, Pemilik, Produk, Pengajuan, Berita, dll.)
│   ├── Services/                # Service Layer (UmkmService, PengajuanService, AktivitasLogger)
│   └── Http/Middleware/         # Middleware Role & EnsureProfilCompleted
├── database/
│   ├── migrations/              # Skema migrasi tabel database
│   └── seeders/                 # Seeder master 5 Kelurahan, RW/RT, Sektor, & Akun Default
├── docs/                        # Dokumentasi Sistem (PRD, Skema Database, Panduan Fitur)
├── resources/
│   ├── css/                     # Styling custom & asset desain
│   ├── js/                      # Script JS frontend
│   └── views/
│       ├── landing/partials/    # Komponen modular Landing Page (Head, Navbar, Hero, Map, dll.)
│       ├── superadmin/          # Panel UI Super Admin (Kasi Ekbang)
│       ├── admin/               # Panel UI Admin Kecamatan (Staf Ekbang)
│       ├── petugas/             # Panel UI Petugas Lapangan (Staf Kelurahan/RW)
│       ├── pelaku/              # Dashboard UI Pelaku UMKM
│       ├── warta/               # Halaman Portal Berita & Warta Kecamatan
│       ├── katalog-umkm.blade.php # Halaman Etalase & Katalog Produk Publik
│       └── welcome.blade.php    # Layout utama halaman depan (Modular)
├── routes/
│   ├── web.php                  # Rute antarmuka web
│   ├── api.php                  # Rute REST API (Sanctum)
│   └── auth.php                 # Rute autentikasi pengguna
├── docker-compose.prod.yml      # Konfigurasi container deploy production VPS
├── nginx.prod.conf              # Konfigurasi Nginx Reverse Proxy Server
└── compose.yaml                 # Konfigurasi container development lokal (Sail)
```

---

## 🏛️ Wilayah Cakupan Kecamatan Mandalajati

Sistem ini melayani pendataan UMKM pada 5 Kelurahan resmi di Kecamatan Mandalajati, Kota Bandung:
1. **Kelurahan Karang Pamulang** (Kode: `KEL-001`)
2. **Kelurahan Sindangjaya** (Kode: `KEL-002`)
3. **Kelurahan Cikadut** (Kode: `KEL-003`)
4. **Kelurahan Pasir Impun** (Kode: `KEL-004`)
5. **Kelurahan Jatihandap** (Kode: `KEL-005`)

---

## 📄 Lisensi

Platform Mandalaloka dikembangkan untuk tata kelola pendataan dan pemberdayaan UMKM Kecamatan Mandalajati, Kota Bandung. Dilindungi di bawah lisensi [MIT License](LICENSE).

---

**Dibuat oleh Ikhsan Octaviana Subagja**
