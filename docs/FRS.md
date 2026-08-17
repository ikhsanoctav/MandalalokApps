# Functional Requirements Specification (FRS) - Sistem Informasi UMKM Mandalaloka

## 1. Pengenalan
Dokumen FRS mendetailkan fungsi-fungsi atau *use case* yang dapat dilakukan oleh sistem maupun oleh aktor pengguna (pengguna akhir) di dalam Sistem Informasi UMKM Mandalaloka. Penjelasan dibagi berdasarkan peran (*Role*).

## 2. Spesifikasi Fungsional per Modul / Peran

### 2.1. Modul Umum / Autentikasi
- **F-AUTH-01 (Login)**: Sistem memverifikasi kombinasi email dan kata sandi pengguna. Setelah login, *middleware* akan mengarahkan pengguna ke *dashboard* masing-masing sesuai Role.
- **F-AUTH-02 (Verifikasi Email)**: Pengguna harus memverifikasi email yang didaftarkan untuk dapat mengakses fitur-fitur beranda/dashboard (terintegrasi dengan middleware `verified`).

### 2.2. Modul Super Admin
- **F-SADM-01 (Manajemen Pengguna)**: Mampu melihat, menambah, mengedit, dan menghapus pengguna sistem beserta *role* mereka.
- **F-SADM-02 (Manajemen Data Master)**: Mampu mengelola data turunan (Kategori UMKM, Sektor UMKM) dan data wilayah (Kelurahan, RW, RT).
- **F-SADM-03 (Manajemen Pengaturan)**: Mengonfigurasi pengaturan umum sistem.
- **F-SADM-04 (Akses Super)**: Super Admin memiliki *bypass* untuk melakukan verifikasi, pengeditan, atau penghapusan terhadap data yang dientri oleh peran lain.

### 2.3. Modul Admin Kecamatan
- **F-ADM-01 (Dashboard Analytics)**: Menampilkan visualisasi data seperti total UMKM terverifikasi, jumlah menunggu (*pending*), dan grafik statistik per wilayah kelurahan.
- **F-ADM-02 (Verifikasi UMKM)**: 
  - Admin dapat memfilter dan melihat daftar UMKM dengan status `menunggu_verifikasi` (menunggu validasi).
  - Admin dapat menekan tombol *Verifikasi* untuk menyetujui, sehingga status UMKM menjadi `terverifikasi`.
  - Admin dapat melakukan *Tolak/Reject* (wajib melampirkan teks alasan/`catatan_penolakan`).
- **F-ADM-03 (Verifikasi Akun Pelaku UMKM)**: Mengulas profil Pemilik dan gambar KTP (yang diwatermark secara dinamis & dilindungi route privat), lalu mengubah status `status_verifikasi_ktp` dari `pending` ke `terverifikasi` atau `ditolak`.
- **F-ADM-04 (Pelaporan/Reporting)**: Memfilter, melihat dan melakukan ekspor laporan data agregat (misalnya per bulan atau per sektor).

### 2.4. Modul Operator Lapangan
- **F-OPR-01 (Dashboard Personal)**: Menampilkan statistik dari hasil input mandiri (hanya UMKM yang dikaitkan dengan `id_petugas` miliknya).
- **F-OPR-02 (Pendataan UMKM Baru)**: Mengisi *form* panjang pendaftaran (kategori, izin, alamat, jumlah pekerja). Ketika dikirim, sistem menetapkan status data sebagai `menunggu_verifikasi`. Operator *tidak memiliki* kewenangan memverifikasi.
- **F-OPR-03 (Revisi Pendataan)**: Apabila Admin menolak sebuah data UMKM, Operator akan melihat daftar data yang `ditolak`. Operator dapat memperbarui/mengedit data tersebut untuk dikirimkan kembali (status otomatis kembali `menunggu_verifikasi` dan catatan penolakan dihapus).
- **F-OPR-04 (Unggah Foto / Titik GPS)**: Menambahkan informasi geolokasi dan galeri foto ke UMKM yang didatanya.

### 2.5. Modul Pelaku UMKM
- **F-PLK-01 (Pengelolaan Profil Dasar)**: Melakukan pembaruan profil kontak dan data pemilik UMKM pribadi.
- **F-PLK-02 (Pendaftaran UMKM Daring)**: Memasukkan informasi profil usahanya ke sistem tanpa melalui Operator Lapangan (berfungsi sebagai pendaftaran swadaya).
- **F-PLK-03 (Pemantauan Status)**: Dapat melacak dan melihat status tahapan pengajuan yang sedang diproses oleh Admin (contoh: sedang diproses, disetujui, perlu perbaikan).
