<p align="center">
  <img src="https://raw.githubusercontent.com/ikhsanoctav/MandalalokApps/main/public/images/Logo_Mandalaloka.png" width="130" alt="Logo Mandalaloka">
</p>

<h1 align="center">Mandalaloka Apps</h1>

<p align="center">
  <strong>Sistem Informasi Pendataan, Pemetaan Geospasial, dan Pemberdayaan UMKM Terpadu Berbasis Kecerdasan Buatan (AI)</strong><br>
  <em>Kecamatan Mandalajati, Kota Bandung, Jawa Barat</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/PHP-8.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.4">
  <img src="https://img.shields.io/badge/MySQL-8.4-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL 8.4">
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker Ready">
  <img src="https://img.shields.io/badge/AI_Engine-n8n_%26_Ollama-EA4B71?style=for-the-badge&logo=openai&logoColor=white" alt="AI Engine">
</p>

---

## 📌 Tentang Mandalaloka

**Mandalaloka** adalah platform web terpadu yang dirancang khusus untuk memodernisasi ekosistem Usaha Mikro, Kecil, dan Menengah (UMKM) di wilayah **Kecamatan Mandalajati, Kota Bandung**. Platform ini menjembatani kolaborasi antara pemerintah kecamatan, petugas survei lapangan, pelaku usaha, dan masyarakat umum melalui digitalisasi data, pemetaan spasial presisi, dan analisis kebijakan berbasis kecerdasan buatan (*AI Decision Support System*).

---

## 🌟 Fitur Utama

### 1. 🗺️ Pemetaan Geospasial Interaktif (OpenStreetMap & Leaflet)
* **Visualisasi Sebaran Spasial**: Peta digital titik lokasi seluruh UMKM se-Kecamatan Mandalajati (Karang Pamulang, Sindangjaya, Pasir Impun, Jatihandap).
* **Clustering Dinamis**: Pengelompokan marker otomatis saat peta diperkecil untuk menjaga performa rendering.
* **Filter Multi-Kriteria**: Pencarian dan penyaringan data secara langsung berdasarkan Sektor Usaha, Kelurahan, hingga radius wilayah.
* **Integrasi Direct Contact**: Pop-up interaktif dilengkapi profil usaha, kategori, rute navigasi, dan tombol direct chat WhatsApp.

### 2. 🤖 AI Decision Support System (DSS) & Policy Generator
* **Analisis Kebijakan Otomatis**: Integrasi dengan engine **n8n Workflow** & **Ollama LLM** untuk menganalisis tren pertumbuhan, omzet, dan disparitas antar wilayah.
* **SWOT & Priority Matrix**: Pembuatan analisis SWOT real-time, identifikasi sektor dominan, dan penetapan prioritas intervensi bantuan modal/pelatihan.
* **Rekomendasi Program Kerja**: Saran langkah operasional jangka pendek, menengah, dan panjang untuk pimpinan kecamatan.

### 3. 🛍️ Etalase Digital & Katalog Publik UMKM
* **Katalog Produk Terverifikasi**: Menampilkan ragam produk unggulan lokal lengkap dengan foto, deskripsi, harga, dan identitas UMKM.
* **Sistem Rating & Ulasan Komunitas**: Masyarakat dapat memberikan rating bintang serta reaksi *like/dislike* pada testimoni ulasan.
* **Pencarian Cepat**: Filter pencarian produk berdasarkan nama, sektor usaha, atau lokasi kelurahan.

### 4. 📚 Manajemen Pelatihan & Absensi QR Code
* **Publikasi & Pendaftaran Pelatihan**: Pendaftaran pelatihan kewirausahaan secara mandiri oleh pelaku usaha dengan kuota dinamis.
* **Scanner Presensi Real-Time**: Petugas lapangan dapat memverifikasi kehadiran peserta pelatihan melalui pemindaian QR Code peserta via kamera perangkat.

### 5. 📑 Pengajuan & Penyaluran Program Bantuan
* **Pendaftaran Program Digital**: Pelaku usaha dapat mengunggah dokumen persyaratan (Proposal, KTP, NIB) secara mandiri.
* **Verifikasi Berjenjang**: Alur verifikasi dokumen dan verifikasi faktual lapangan oleh Admin Kecamatan & Operator Lapangan.

### 6. 💬 Chatbot Cerdas "Mang Loka"
* **Asisten Virtual 24/7**: Chatbot interaktif di halaman depan yang dibekali basis pengetahuan regulasi UMKM, tata cara perizinan (NIB/Halal), informasi bantuan, serta statistik kecamatan.

---

## 👥 Hak Akses Pengguna (Role-Based Access Control)

| Role | Tanggung Jawab & Akses Utama |
| :--- | :--- |
| **Super Admin** | Manajemen pengguna & role (RBAC), kontrol master wilayah & sektor, audit log sistem, backup database, dan ringkasan DSS tingkat eksekutif. |
| **Admin Kecamatan** | Verifikasi legalitas KTP akun UMKM, verifikasi proposal bantuan, pengelolaan agenda pelatihan, dan warta publikasi kecamatan. |
| **Petugas Lapangan** | Input survei UMKM baru langsung dari lapangan, tagging GPS & upload foto usaha, verifikasi lapangan fisik, dan scanner QR presensi pelatihan. |
| **Pelaku UMKM** | Manajemen profil usaha, input katalog produk/jasa, pendaftaran program bantuan, dan riwayat keikutsertaan pelatihan. |
| **Publik (Tamu)** | Eksplorasi katalog produk, peta sebaran UMKM, membaca warta berita, konsultasi dengan chatbot Mang Loka, dan pendaftaran akun baru. |

---

## 🛠️ Arsitektur Teknologi

* **Backend**: [Laravel 11](https://laravel.com/) (PHP 8.4+)
* **Database**: [MySQL 8.4](https://www.mysql.com/)
* **Frontend**: Blade Templating, [Tailwind CSS](https://tailwindcss.com/), [Alpine.js](https://alpinejs.dev/), [Chart.js](https://www.chartjs.org/)
* **Peta & GIS**: [Leaflet.js](https://leafletjs.com/) & [OpenStreetMap](https://www.openstreetmap.org/)
* **Otomasi & AI**: [n8n Automation](https://n8n.io/) + [Ollama LLM](https://ollama.com/) (Qwen 2.5)
* **Autentikasi & Otorisasi**: Laravel Breeze & [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/)
* **Containerization**: [Docker](https://www.docker.com/) & Docker Compose / Laravel Sail

---

## 🚀 Panduan Memulai (Instalasi Lokal)

### Prasyarat
* [Docker Desktop](https://www.docker.com/products/docker-desktop/) (atau Docker Engine di Linux)
* [Git](https://git-scm.com/)

### Langkah Instalasi (Menggunakan Docker / Laravel Sail)

1. **Clone repositori:**
   ```bash
   git clone https://github.com/ikhsanoctav/MandalalokApps.git
   cd MandalalokApps
   ```

2. **Salin file konfigurasi environment:**
   ```bash
   cp .env.example .env
   ```

3. **Jalankan container menggunakan Laravel Sail:**
   ```bash
   ./vendor/bin/sail up -d
   ```

4. **Install dependensi & generate Application Key:**
   ```bash
   ./vendor/bin/sail composer install
   ./vendor/bin/sail artisan key:generate
   ```

5. **Jalankan migrasi database & seeder:**
   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```

6. **Buat symlink penyimpanan berkas:**
   ```bash
   ./vendor/bin/sail artisan storage:link
   ```

7. **Kompilasi asset frontend:**
   ```bash
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run dev
   ```

8. **Buka aplikasi di browser:**
   * Aplikasi Web: `http://localhost:8080` (atau `http://localhost`)
   * phpMyAdmin: `http://localhost:8081`

---

## 🧪 Menjalankan Pengujian (Testing)

Aplikasi dilengkapi dengan rangkaian automated test suite (*Feature & Unit Tests*):

```bash
# Menjalankan seluruh test suite via Sail
./vendor/bin/sail test
```

---

## 📁 Struktur Direktori Utama

```text
MandalalokaApps/
├── app/
│   ├── Http/Controllers/     # Controller per role (SuperAdmin, Admin, Petugas, Pelaku, Api)
│   ├── Models/                  # Model Eloquent (UMKM, Pemilik, Pelatihan, Pengajuan, DSS)
│   └── Services/                # Service Layer (DssService, UmkmService, OllamaService)
├── database/
│   ├── migrations/              # Skema migrasi database
│   └── seeders/                 # Seeder master data (Wilayah, Kategori, Role)
├── resources/
│   └── views/
│       ├── landing/partials/    # Komponen modular halaman depan (Navbar, Hero, Map, dll.)
│       ├── superadmin/          # Panel kontrol Super Admin
│       ├── admin/               # Panel kontrol Admin Kecamatan
│       ├── petugas/             # Panel operasional Petugas Lapangan
│       ├── pelaku/              # Dashboard Pelaku UMKM
│       └── welcome.blade.php    # Halaman depan utama
├── routes/
│   ├── web.php                  # Routing antarmuka web
│   ├── api.php                  # REST API publik & aplikasi mobile
│   └── auth.php                 # Routing autentikasi pengguna
└── compose.yaml                 # Konfigurasi container Docker / Laravel Sail
```

---

## 📄 Lisensi

Platform Mandalaloka dikembangkan untuk kebutuhan tata kelola data dan pemberdayaan UMKM Kecamatan Mandalajati, Kota Bandung. Proyek ini dilindungi di bawah lisensi [MIT License](LICENSE).
