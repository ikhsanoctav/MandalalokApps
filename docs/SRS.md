# Software Requirements Specification (SRS) - Sistem Informasi UMKM Mandalaloka

## 1. Pengenalan
Sistem Informasi UMKM Mandalaloka adalah aplikasi berbasis web yang dibangun untuk manajemen data UMKM terpadu. Dokumen SRS ini menjelaskan spesifikasi teknis tingkat tinggi, arsitektur, dan kebutuhan non-fungsional dari sistem ini untuk memandu tim IT.

## 2. Arsitektur & Teknologi (Tech Stack)
- **Framework Backend**: Laravel (PHP)
- **Database**: MySQL / MariaDB (Skema database dikelola melalui *Laravel Migrations & Seeders*)
- **Manajemen Dependensi**: Composer untuk PHP, NPM untuk aset Frontend
- **Manajemen Hak Akses**: *Spatie Laravel Permission* untuk kontrol autentikasi peran (RBAC)
- **Lingkungan Pengembangan (Dev Environment)**: Docker (via Laravel Sail)

## 3. Model Data Utama (Core Data Models)
Sistem memiliki struktur database relasional dengan tabel-tabel utama sebagai berikut:
- `users`: Menyimpan kredensial autentikasi pengguna (email, password yang di-hash, role).
- `pemiliks`: Menyimpan data profil entitas pelaku usaha (Pemilik UMKM), termasuk data demografis, kontak, NIK, dan *status_verifikasi_ktp*.
- `umkms`: Entitas utama yang menyimpan detail usaha (nama usaha, nomor izin/NIB, skala usaha, titik koordinat peta, alamat) dan berelasi *(belongsTo)* dengan entitas Pemilik, Kategori, Sektor, dan Petugas/Operator.
- `pengajuans`: Menyimpan log pengajuan/permohonan oleh UMKM.
- **Master Data**: `kategori_umkms` (Mikro, Kecil, Menengah), `sektor_umkms` (Kuliner, Fashion, dll), `kelurahans`, `rws`, `rts`.

## 4. Kebutuhan Non-Fungsional (Non-Functional Requirements)

### 4.1. Keamanan Data (Security)
- **Enkripsi Data Sensitif**: Data *Nomor Induk Kependudukan (NIK)* pada tabel `pemiliks` dan `users` tidak disimpan secara *plaintext*. Data tersebut dienkripsi (AES) dan didekripsi di level model menggunakan fitur *Casts/Attribute Crypt* bawaan Laravel.
- **Proteksi Endpoint**: Implementasi *Middleware* standar Laravel (`auth`, `verified`, dan pengecekan role `Spatie`) pada setiap rute.
- **Pencegahan Injeksi**: Sistem murni menggunakan parameter *binding* (melalui *Eloquent ORM*) untuk menghindari kerentanan *SQL Injection*.
- **XSS & CSRF Protection**: Proteksi otomatis terhadap pemalsuan form menggunakan *CSRF Token* dari Laravel dan *escaping* output Blade.

### 4.2. Performa (Performance)
- Optimalisasi query database dengan menggunakan *Eager Loading* (`with()`) untuk menghindari masalah *N+1 query* pada halaman tabel, daftar UMKM, dan penyusunan laporan.

### 4.3. Antarmuka & Ketersediaan (Usability & Availability)
- Antarmuka (UI/UX) dirancang agar responsif (*Responsive Web Design*) dan interaktif.
- Dapat diakses secara optimal di berbagai peramban modern (Google Chrome, Mozilla Firefox, Safari, Microsoft Edge), baik di perangkat desktop maupun *mobile*.
- Layout dashboard dipisahkan menjadi *layout admin*, *layout operator*, dan *layout pelaku* demi kerapian kode presentasi.
