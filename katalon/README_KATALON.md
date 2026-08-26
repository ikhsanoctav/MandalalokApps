# Katalon Studio Automation Test Guide - Mandalaloka Apps

Dokumen ini berisi panduan lengkap penggunaan script pengujian otomatis (*Automation Testing*) menggunakan **Katalon Studio** untuk seluruh fitur pada aplikasi **Mandalaloka**.

---

## 📁 Struktur Berkas Testing
- **Script Utama**: `katalon/Test_Cases_All_Features.groovy`
- **Target Host**: `http://103.89.4.245`

---

## 🎯 Coverage Fitur yang Diuji

| No | Modul / Role | Skenario Pengujian | Hasil Pengujian Yang Diharapkan |
|---|---|---|---|
| 1 | **Auth & Multi-Role** | Login Super Admin, Admin Kecamatan, Operator Lapangan, Pelaku UMKM | Berhasil redirect ke Dashboard sesuai role masing-masing |
| 2 | **Super Admin** | Manajemen UMKM, Filter & Search AJAX, Navigasi Detail UMKM, Verifikasi Akun, User Management, Data Master Kelurahan & Sektor | Seluruh menu data master & halaman detail terbuka lancar tanpa freeze/error |
| 3 | **Admin Kecamatan** | Verifikasi Lapangan UMKM, Rekap & Laporan, Verifikasi Akun KTP | Halaman verifikasi terbuka, navigasi laporan berfungsi normal |
| 4 | **Operator Lapangan** | Input & **Simpan Data UMKM Baru** (NIK valid, biodata lengkap, omset, sektor, alamat), Verifikasi Lapangan | Form terisi lengkap dan data berhasil disimpan ke database |
| 5 | **Pelaku UMKM** | Edit Profil, **Tambah Usaha Baru** (Simpan form), **Tambah Produk Baru di Katalog** (Simpan form) | Seluruh form berhasil diinput dan data produk/usaha tersimpan |

---

## 🚀 Langkah Menggunakan Script di Katalon Studio

### 1. Buat Test Case di Katalon Studio
1. Buka proyek Anda di Katalon Studio.
2. Klik kanan pada folder **Test Cases -> New -> Test Case**.
3. Beri nama: `ALL TEST CASE` (atau nama lain yang Anda inginkan).

### 2. Salin Script (Mode Script)
1. Buka Test Case yang baru dibuat, lalu pindah ke tab **Script** (di bagian bawah editor).
2. Salin (*copy*) seluruh isi berkas `katalon/Test_Cases_All_Features.groovy`.
3. Tempel (*paste*) ke dalam editor Katalon Studio.

> [!TIP]
> **Tanpa Perlu Setup Tambahan**: Script ini sudah menggunakan **XPath Inline** dan penanganan logout berbasis cookie, sehingga Anda **TIDAK perlu** membuat *Object Repository* maupun *Global Variable* secara manual.

### 3. Menjalankan Pengujian (Execution)
1. Klik tombol **Run** (Ikon Play / Panah Hijau) di toolbar atas.
2. Pilih Browser: **Edge** atau **Chrome**.
3. Katalon Studio akan otomatis membuka browser, menjalankan pengujian multi-role secara terisolasi, dan menyimpan data pengujian secara otomatis.

---

## 📊 Laporan Pengujian (Test Reports)
Setelah pengujian selesai, laporan eksekusi lengkap dapat dilihat pada tab **Log Viewer** atau di folder **Reports** pada Katalon Studio dalam format:
- **HTML Report**
- **PDF Report**
- **CSV / Execution Log**
