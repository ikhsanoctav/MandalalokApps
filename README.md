<p align="center">
  <img src="https://raw.githubusercontent.com/ikhsanoctav/MandalalokApps/main/public/images/Logo_Mandalaloka.png" width="130" alt="Logo Mandalaloka">
</p>

<h1 align="center">Mandalaloka Apps</h1>

<p align="center">
  <strong>Sistem Informasi Pendataan, Pemetaan Geospasial, dan Pemberdayaan UMKM Terpadu Berbasis Hybrid DSS (AHP-SAW) & AI Assistant</strong><br>
  <em>Pemerintah Kecamatan Mandalajati, Kota Bandung, Jawa Barat</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/PHP-8.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.4">
  <img src="https://img.shields.io/badge/MySQL-8.4-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL 8.4">
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker Ready">
  <img src="https://img.shields.io/badge/AI_Engine-n8n_%26_Ollama-EA4B71?style=for-the-badge&logo=openai&logoColor=white" alt="AI Engine">
  <img src="https://img.shields.io/badge/GIS-Leaflet_%26_OSM-199900?style=for-the-badge&logo=leaflet&logoColor=white" alt="Leaflet GIS">
</p>

---

## 📌 Tentang Sistem Mandalaloka

**Mandalaloka** adalah platform web terintegrasi yang dikembangkan untuk mendigitalkan seluruh siklus pendataan, pembinaan, pemetaan wilayah, dan pengambilan keputusan terkait Usaha Mikro, Kecil, dan Menengah (UMKM) di wilayah **Kecamatan Mandalajati, Kota Bandung** (mencakup 5 kelurahan: *Karang Pamulang, Sindangjaya, Cikadut, Pasir Impun, dan Jatihandap*).

Sistem ini memadukan **Sistem Pendukung Keputusan (DSS) Hybrid AHP & SAW**, **Asisten AI Eksekutif & Publik (Mang Loka)**, **Pemetaan Geospasial Presisi (GIS)**, serta **Manajemen Pelatihan & Bantuan Modal**.

---

## 🌟 Modul & Fitur Utama Sistem

```
┌────────────────────────────────────────────────────────────────────────┐
│                          MANDALALOKA ECOSYSTEM                         │
├───────────────────┬───────────────────┬────────────────────────────────┤
│    PUBLIC ZONE    │    PELAKU ZONE    │      ADMIN & PETUGAS ZONE      │
│  - Landing Portal │  - Profile & KTP  │  - Super Admin Control Panel   │
│  - Katalog UMKM   │  - Kelola Produk  │  - Verifikasi KTP Watermarking │
│  - Peta Interaktif│  - Ajukan Bantuan │  - DSS Hybrid AHP-SAW Engine   │
│  - Warta & Berita │  - Daftar Training│  - Petugas GPS Sensus & QR Scan│
│  - AI Mang Loka   │  - Live Tracking  │  - Ekspor/Impor Laporan Excel  │
└───────────────────┴───────────────────┴────────────────────────────────┘
```

### 1. 🗺️ Pemetaan Geospasial Interaktif (GIS - Leaflet & OpenStreetMap)
* **Visualisasi Spasial Sebaran**: Peta digital interaktif titik lokasi UMKM di 5 Kelurahan Kecamatan Mandalajati.
* **Smart Marker Clustering**: Pengelompokan visual dinamis marker UMKM dengan radius zoom cerdas.
* **Penyaringan Multi-Dimensi**: Filter peta berdasarkan Sektor Usaha (Kuliner, Fashion, Jasa, Agribisnis, dll.) dan Kelurahan.
* **Integrasi Direct WhatsApp & Rute**: Pop-up detail usaha lengkap dengan foto profil, alamat, dan tombol langsung terhubung ke WhatsApp pemilik.

### 2. ⚖️ Sistem Pendukung Keputusan Hybrid (DSS: AHP & SAW)
* **AHP (Analytic Hierarchy Process)**: Kalkulasi bobot prioritas kriteria secara objektif melalui matriks perbandingan berpasangan (*Pairwise Comparison Matrix*).
* **SAW (Simple Additive Weighting)**: Perankingan otomatis alternatif penerima bantuan modal/program UMKM berdasarkan matriks ternormalisasi.
* **Adaptive DSS & Explainable AI**: Penjelasan rekomendasi (*reasoning*) mengapa suatu UMKM direkomendasikan mendapat bantuan, serta integrasi AI Policy Generator berbasis n8n & Ollama LLM.

### 3. 🛡️ Verifikasi Identitas & Proteksi Dokumen KTP (Watermarking)
* **Validasi NIK Real-Time**: Pemeriksaan integritas NIK pada tabel `users` dan `pemiliks` untuk mencegah duplikasi identitas.
* **Dynamic KTP Watermarking**: Berkas scan KTP otomatis dilapisi watermark dinamis nama pemohon & stempel sistem saat ditinjau Admin guna mencegah penyalahgunaan identitas.
* **Alur Status Berjenjang**: Peninjauan status transparan: `Draft` ➔ `Menunggu Verifikasi` ➔ `Terverifikasi` / `Ditolak` (dengan catatan revisi).

### 4. 🛍️ Katalog Publik & Interaksi Komunitas
* **Etalase Produk Unggulan**: Showcase produk lokal dengan galeri foto, kategori harga, dan status legalitas usaha (NIB, Halal, PIRT).
* **Sistem Ulasan & Rating**: Penilaian bintang (1-5) dari publik dengan fitur *Like / Dislike* pada ulasan ulasan komunitas.
* **Pencarian Cepat**: Filter pencarian produk instan berbasis nama produk, sektor, maupun wilayah.

### 5. 📚 Manajemen Pelatihan & Presensi QR Code Scanner
* **Publikasi & Pendaftaran Pelatihan**: Alokasi kuota peserta otomatis dengan validasi profil terverifikasi (`EnsureProfilCompleted`).
* **Presensi Kamera Real-Time**: Petugas lapangan dapat memindai tiket QR Code peserta di lokasi pelatihan untuk mencatat kehadiran secara instan.

### 6. 🤖 Asisten Pintar AI "Mang Loka" & Executive Assistant
* **Chatbot Publik (Mang Loka)**: AI interaktif 24/7 di halaman depan untuk menjawab konsultasi perizinan, syarat bantuan, dan pencarian UMKM lokal.
* **Executive AI Assistant**: Asisten analitik di panel Super Admin untuk merangkum tren ekonomi, disparitas wilayah, dan proyeksi UMKM.

### 7. 📄 Legalitas Usaha, Cetak QR & Kop Surat Kecamatan
* **QR Code Profil Usaha**: Generator QR Code unik per UMKM untuk kemasan produk atau etalase toko.
* **Cetak Dokumen Resmi**: Ekspor lembar identitas UMKM tervalidasi dengan kop surat resmi Pemerintah Kecamatan Mandalajati.

---

## 👥 Matriks Hak Akses Pengguna (RBAC)

| Modul / Fitur | Super Admin | Admin Kecamatan | Petugas Lapangan | Pelaku UMKM | Publik |
| :--- | :---: | :---: | :---: | :---: | :---: |
| **Dashboard Analitik & Grafik** | ✅ Penuh | ✅ Kecamatan | ✅ Wilayah | ✅ Usaha Sendiri | ❌ |
| **Manajemen Master Data (Wilayah/Sektor)** | ✅ CRUD/Import | ❌ | ❌ | ❌ | ❌ |
| **Verifikasi Akun & KTP Watermarking** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Validasi Pengajuan UMKM & Bantuan** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Input Data Sensus Lapangan (GPS Tagging)** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Scanner Absensi QR Pelatihan** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Kelola Profil & Etalase Produk** | ✅ | ✅ | ❌ | ✅ Usaha Sendiri | ❌ |
| **Daftar Program Pelatihan & Bantuan** | ❌ | ❌ | ❌ | ✅ | ❌ |
| **Engine DSS (AHP & SAW Calculator)** | ✅ Penuh | ✅ Lihat/Ranking | ❌ | ❌ | ❌ |
| **Portal Warta & Berita Kecamatan** | ✅ Kelola | ✅ Kelola | ❌ | ❌ | ✅ Membaca |
| **Katalog Produk & Review Komunitas** | ✅ Moderasi | ✅ Moderasi | ❌ | ❌ | ✅ Rating/Ulas |
| **Peta Interaktif OpenStreetMap** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **AI Chatbot Mang Loka** | ✅ Executive | ❌ | ❌ | ❌ | ✅ 24/7 |
| **Audit Logs & Backup Database** | ✅ | ❌ | ❌ | ❌ | ❌ |

---

## 🛠️ Tech Stack & Integrasi

* **Core Framework**: [Laravel 11](https://laravel.com/) (PHP 8.4+)
* **Database**: [MySQL 8.4](https://www.mysql.com/)
* **Frontend UI**: Blade Modular Partials, [Tailwind CSS](https://tailwindcss.com/), [Alpine.js](https://alpinejs.dev/), [Chart.js](https://www.chartjs.org/)
* **Peta & GIS**: [Leaflet.js](https://leafletjs.com/) & [OpenStreetMap Tile Layer](https://www.openstreetmap.org/)
* **Decision Support System**: AHP Matrix Comparison & SAW Normalization Algoritma
* **Kecerdasan Buatan (AI)**: [n8n Workflow](https://n8n.io/) + [Ollama LLM](https://ollama.com/) (Model Qwen 2.5)
* **Hak Akses & Keamanan**: [Spatie Laravel-Permission](https://spatie.be/docs/laravel-permission/), CSRF Protection, Hash SHA-256 NIK, Dynamic Watermarking
* **Mobile REST API**: Laravel Sanctum Token Authentication (untuk integrasi aplikasi mobile Flutter `MandalalokaApk`)
* **DevOps & Lingkungan**: Docker, Docker Compose, Laravel Sail, Nginx Reverse Proxy (Gzip & Security Headers)

---

## 🚀 Panduan Menjalankan Aplikasi

### 🐳 Menggunakan Docker & Laravel Sail (Rekomendasi)

1. **Clone Repositori:**
   ```bash
   git clone https://github.com/ikhsanoctav/MandalalokApps.git
   cd MandalalokApps
   ```

2. **Setup Konfigurasi Environment:**
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

5. **Jalankan Migrasi & Database Seeder:**
   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```

6. **Buat Symlink Storage Publik:**
   ```bash
   ./vendor/bin/sail artisan storage:link
   ```

7. **Kompilasi Asset Frontend:**
   ```bash
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run dev
   ```

8. **Akses Aplikasi:**
   * **Web Portal:** `http://localhost:8080` (atau `http://localhost`)
   * **phpMyAdmin:** `http://localhost:8081`
   * **n8n Automation:** `http://localhost:5678`

---

## 🧪 Rangkaian Pengujian (Automated Testing)

Aplikasi dilengkapi dengan rangkaian automated test suite (*Feature & Unit Tests*) berbasis PHPUnit:

```bash
# Menjalankan seluruh pengujian unit dan fitur
./vendor/bin/sail test
```

Tersedia juga modul pengujian otomatis end-to-end:
* **Katalon Studio**: Direktori `katalon/`
* **Selenium WebDriver**: Direktori `selenium/`

---

## 📁 Struktur Direktori Proyek

```text
MandalalokaApps/
├── app/
│   ├── Http/Controllers/
│   │   ├── SuperAdmin/          # Kontrol Master Data, Users, DSS SAW/AHP, Logs, Settings
│   │   ├── Admin/               # Verifikasi Akun KTP, Validasi UMKM, Bantuan, Pelatihan
│   │   ├── Petugas/             # Pendataan Lapangan, Verifikasi Fisik, QR Presensi
│   │   ├── Pelaku/              # Profil Pemilik, Registrasi Usaha, Produk, Pengajuan
│   │   └── Api/                 # Endpoint REST API (Statistik, Peta, Chatbot, Mobile)
│   ├── Models/                  # Model Eloquent (UMKM, Pemilik, Pelatihan, DSS, dll.)
│   ├── Services/                # Service Layer (DssService, UmkmService, OllamaService)
│   └── Http/Middleware/         # Middleware Role & EnsureProfilCompleted
├── database/
│   ├── migrations/              # Skema tabel database (53 migrasi)
│   └── seeders/                 # Seeder master 5 Kelurahan, RW/RT, Sektor, & Akun
├── resources/views/
│   ├── landing/partials/        # Partials modular Landing Page (Head, Navbar, Hero, Map, dll.)
│   ├── superadmin/              # Panel UI Super Admin
│   ├── admin/                   # Panel UI Admin Kecamatan
│   ├── petugas/                 # Panel UI Petugas Lapangan
│   ├── pelaku/                  # Panel UI Pelaku UMKM
│   └── welcome.blade.php        # Layout utama halaman depan
├── routes/
│   ├── web.php                  # Rute antarmuka web
│   ├── api.php                  # Rute API RESTful (Sanctum)
│   └── auth.php                 # Rute autentikasi
├── docker-compose.prod.yml      # Konfigurasi deploy production VPS
└── compose.yaml                 # Konfigurasi container lokal (Sail)
```

---

## 🏛️ Wilayah Cakupan Kecamatan Mandalajati

Sistem ini melayani pendataan UMKM pada 5 Kelurahan resmi di Kecamatan Mandalajati:
1. **Kelurahan Karang Pamulang** (Kode: `KEL-001`)
2. **Kelurahan Sindangjaya** (Kode: `KEL-002`)
3. **Kelurahan Cikadut** (Kode: `KEL-003`)
4. **Kelurahan Pasir Impun** (Kode: `KEL-004`)
5. **Kelurahan Jatihandap** (Kode: `KEL-005`)

---

## 📄 Lisensi

Platform Mandalaloka dikembangkan untuk tata kelola data dan akselerasi pemberdayaan UMKM Kecamatan Mandalajati, Kota Bandung. Dilindungi di bawah lisensi [MIT License](LICENSE).
