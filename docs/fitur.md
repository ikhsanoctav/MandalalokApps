# Fitur Sistem Informasi UMKM Mandalaloka Berdasarkan Peran (Role)

Dokumen ini menjelaskan daftar fitur dan batasan akses untuk masing-masing peran (role) pengguna di dalam Sistem Informasi UMKM Mandalaloka. Sistem ini membagi penggunanya menjadi empat level akses utama.

---

## 1. Super Admin (Camat / Pimpinan Tertinggi)
**Akses Utama:** Memiliki hak kontrol penuh terhadap keseluruhan sistem, konfigurasi, dan manajemen data.

**Daftar Fitur:**
- **Dashboard & Analitik:** Melihat ringkasan data, grafik pertumbuhan, dan distribusi UMKM per wilayah/sektor.
- **Manajemen Data UMKM Penuh:** Menambah, melihat detail, mengedit, menghapus, serta melakukan Import/Export data UMKM dalam format Excel.
- **Verifikasi UMKM & Akun:** Berhak melakukan intervensi (Approve/Reject) terhadap data pengajuan UMKM maupun persetujuan pendaftaran akun Pelaku UMKM.
- **Manajemen Pengajuan Bantuan/Program:** Melihat daftar UMKM yang diajukan untuk program bantuan dan memperbarui status pencairan/pengajuannya.
- **Manajemen Pengguna (User Management):** Membuat, mengedit, menghapus, dan mengatur perizinan (Role) seluruh pengguna sistem (termasuk admin, petugas, dan pelaku).
- **Manajemen Master Data:** Mengelola operasi CRUD (Create, Read, Update, Delete) serta Import untuk data Sektor Usaha, Kategori, Kelurahan, dan RW.
- **Manajemen Warta / Berita:** Membuat dan mempublikasikan portal berita/informasi (Warta Dinas) untuk halaman depan (*landing page*), termasuk mengatur kategori/penulis.
- **Laporan & Log Aktivitas (System Logs):** Mengunduh rekapitulasi data lengkap dan memantau riwayat aktivitas operasional seluruh pengguna di sistem.
- **Pengaturan & Backup:** Mengelola pengaturan konfigurasi sistem serta melakukan *Backup* dan *Restore* database secara langsung.

---

## 2. Admin Kecamatan (Kasi Ekbang / Pengelola)
**Akses Utama:** Berfokus pada pengelolaan, validasi, dan verifikasi administrasi UMKM tanpa memiliki akses ke pengaturan inti sistem atau basis data teknis.

**Daftar Fitur:**
- **Dashboard Data:** Meninjau statistik pendaftaran UMKM harian dan penyebaran sektoral.
- **Pusat Verifikasi Data UMKM:** Melakukan tinjauan dokumen dan legalitas dari UMKM yang baru mendaftar (Klik "Verifikasi" atau "Tolak" beserta alasan).
- **Verifikasi Akun Pelaku:** Menyetujui atau menolak pendaftaran akun pengguna eksternal (Pelaku Usaha).
- **Manajemen Data UMKM:** Melakukan penambahan, pengeditan, atau penghapusan data UMKM (jika terjadi kesalahan pengisian oleh pelaku/petugas).
- **Eksplorasi Laporan:** Mencetak laporan data UMKM yang tervalidasi maupun belum, dan mengkespor laporan tersebut (Excel/PDF).

---

## 3. Petugas Lapangan (Operator / Surveyor)
**Akses Utama:** Menjembatani sistem dengan kondisi realita di lapangan, bertugas melakukan sensus, pendataan, dan validasi lokasi.

**Daftar Fitur:**
- **Dashboard Pendataan:** Melihat target pendataan dan wilayah kerja yang dibebankan.
- **Input Data Sensus UMKM:** Melakukan pencatatan/pendaftaran profil UMKM baru langsung di lapangan.
- **Dokumentasi & Galeri:** Mengunggah foto tempat usaha, foto produk, dan bukti fisik operasional UMKM yang disurvei.
- **Pemantauan Data Terinput:** Melihat riwayat data UMKM yang telah ia data sebelumnya dan melihat status validasinya (apakah sudah disetujui Admin atau belum).
- **Informasi Wilayah:** Menjelajahi informasi pemetaan kelurahan dan RW di area Kecamatan.

---

## 4. Pelaku UMKM (Pemilik Usaha)
**Akses Utama:** Pengguna publik yang telah mendaftar, memiliki akses untuk mengelola etalase profil usahanya masing-masing agar terdaftar secara resmi di kecamatan.

**Daftar Fitur:**
- **Dashboard Pelaku Usaha:** Memantau informasi terkini, pengumuman dari kecamatan, dan status usahanya.
- **Manajemen Profil Akun:** Mengedit biodata pribadi selaku pendaftar/pemilik.
- **Registrasi UMKM Mandiri:** Mengajukan permohonan pendaftaran usaha, mengisi kelengkapan dokumen legalitas (NIB, dll.), deskripsi usaha, alamat, dan titik peta.
- **Kelola Produk & Legalitas (Edit UMKM):** Memperbarui katalog produk, foto usaha, serta memperbaiki data UMKM jika mendapat penolakan (catatan revisi) dari Admin.
- **Pemantauan Status Verifikasi:** Melacak tahap pengajuan usahanya secara *real-time* (Contoh: *Draft, Menunggu Verifikasi, Terverifikasi, Ditolak*).
- **Notifikasi Personal:** Menerima pemberitahuan jika ada perubahan status pada perizinan usahanya atau jika ada pesan masuk (catatan perbaikan) dari pihak kecamatan.
