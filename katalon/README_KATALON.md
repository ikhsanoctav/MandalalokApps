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
| 1 | **Auth & Multi-Role** | Login Super Admin, Admin Kecamatan, Petugas Lapangan, Pelaku UMKM | Berhasil redirect ke Dashboard sesuai role masing-masing |
| 2 | **Super Admin** | Manajemen UMKM, Filter & Search, Modal Detail, Lightbox Foto, Verifikasi Akun, User Management, Data Master | Seluruh menu data master & modal detail responsive tanpa error |
| 3 | **Admin Kecamatan** | Verifikasi Lapangan UMKM, Approval/Rejection, Export Rekap & Laporan | Modal verifikasi terbuka, tombol ekspor laporan merespons |
| 4 | **Petugas Lapangan** | Input Pendataan UMKM Baru, Upload Foto & GPS | Form pendataan terisi valid dan tersimpan |
| 5 | **Pelaku UMKM** | Edit Profil, Manajemen Katalog Produk, Pengajuan Bantuan | Halaman profil & katalog produk dimuat secara lancar |

---

## 🚀 Langkah Menggunakan Script di Katalon Studio

### 1. Buat Proyek Baru di Katalon Studio
1. Buka Katalon Studio.
2. Klik **File -> New -> Project**.
3. Beri nama proyek: `Mandalaloka_Automation_Testing`.
4. Pilih tipe: **Web**.

### 2. Tambahkan Global Variable
1. Di jendela Katalon Studio, buka folder **Profiles -> default**.
2. Tambahkan Variable baru:
   - **Name**: `G_SiteUrl`
   - **Type**: `String`
   - **Value**: `http://103.89.4.245`

### 3. Salin Script Test Case
1. Buat Test Case baru di Katalon Studio: Klik kanan folder **Test Cases -> New -> Test Case**.
2. Beri nama: `TC_All_Features_Mandalaloka`.
3. Pindah ke mode **Script** (di bagian bawah editor Katalon).
4. Tempel (*paste*) isi berkas `katalon/Test_Cases_All_Features.groovy`.

### 4. Menjalankan Pengujian (Execution)
1. Klik tombol **Run** (Ikon Panah Hijau) di toolbar atas.
2. Pilih Browser: **Chrome**, **Edge**, atau **Chrome (Headless)**.
3. Katalon Studio akan secara otomatis membuka browser, melakukan eksekusi seluruh alur pengujian multi-role, dan menampilkan laporan **PASSED / FAILED**.

---

## 📊 Laporan Pengujian (Test Reports)
Setelah pengujian selesai, laporan eksekusi lengkap dengan bukti screenshot dapat diunduh di folder **Reports** pada Katalon Studio dalam format:
- **HTML Report**
- **PDF Report**
- **CSV / Log Execution**
