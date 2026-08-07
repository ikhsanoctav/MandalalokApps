# Penjelasan Lengkap Fitur Sistem Informasi UMKM Mandalaloka (MandalalokaApps)

Sistem Informasi UMKM Mandalaloka (**MandalalokaApps**) adalah platform manajemen dan pendataan UMKM terpadu berbasis digital untuk Kecamatan Mandalajati. Platform ini mengintegrasikan alur pendataan lapangan, validasi administrasi, sistem pendukung keputusan (DSS), analisis kecerdasan buatan (AI), serta publikasi katalog usaha lokal secara *real-time*.

---

## 1. Modul Autentikasi & Pengaturan Akses (RBAC)

Sistem menggunakan alur keamanan berbasis peran (**Role-Based Access Control**) untuk memastikan setiap level pengguna mengakses fitur yang relevan dengan tugas operasionalnya.

### Fitur Utama:
* **Multi-Role Authentication:** Mendukung 4 role utama: `Super Admin`, `Admin Kecamatan`, `Operator Lapangan`, dan `Pelaku UMKM`.
* **Cek NIK Real-time:** Validasi NIK otomatis saat pendaftaran akun maupun pendataan untuk mencegah duplikasi identitas pemilik usaha.
* **Manajemen Profil & Password:** Pengguna dapat memperbarui biodata pribadi, foto profil, dan kata sandi secara mandiri.
* **Smart Route Redirection:** Pengguna yang berhasil login secara otomatis diarahkan ke *dashboard* masing-masing sesuai hak aksesnya.

---

## 2. Modul Pendataan & Manajemen Profil UMKM

Fungsi utama untuk mengumpulkan, memperbarui, dan mengelola informasi usaha secara komprehensif.

### Fitur Utama:
* **Registrasi Mandiri & Sensus Lapangan:**
  * **Pelaku UMKM:** Mengajukan permohonan pendaftaran usahanya secara mandiri melalui portal pelaku.
  * **Operator Lapangan:** Melakukan pendaftaran/sensus UMKM langsung di lapangan menggunakan perangkat *mobile/tablet*.
* **Kelengkapan Profil Usaha:**
  * Biodata Pemilik (NIK, Nama, Kelurahan, RW, No. Telp, Alamat).
  * Legalitas Usaha (NIB, SKU, PIRT, Sertifikat Halal, BPOM).
  * Karakteristik Usaha (Nama Usaha, Sektor Usaha, Kategori, Modal, Omset, Jumlah Tenaga Kerja).
  * Lokasi Pemetaan GIS (Titik Koordinat Latitude/Longitude pada peta interaktif).
* **Cetak Dokumen & QR Code:**
  * **QR Code Usaha:** Pencetakan QR Code resmi untuk setiap profil UMKM yang dapat dipasang di lokasi usaha/kemasan.
  * **Cetak Dokumen Terverifikasi:** Mencetak lembar identitas UMKM resmi bertanda tangan/kop surat kecamatan.
* **Galeri Foto Usaha & Produk:** Pengunggahan foto tempat usaha, bukti fisik, dan etalase produk unggulan.

---

## 3. Modul Verifikasi Data & Keamanan Berkas KTP

Menjamin validitas data UMKM dan keamanan dokumen identitas yang sensitif.

### Fitur Utama:
* **Verifikasi Berkas UMKM:**
  * Admin meninjau data pengajuan dengan alur status: `Draft` ➔ `Menunggu Verifikasi` ➔ `Terverifikasi` / `Ditolak`.
  * Fitur **Catatan Revisi** jika berkas ditolak agar Pelaku UMKM dapat memperbaiki data yang kurang pas.
* **Verifikasi Akun & Secure KTP Watermarking:**
  * Modul khusus validasi KTP Pemilik sebelum akun pelaku aktif sepenuhnya.
  * **Watermarking Otomatis:** Setiap peninjauan KTP dilengkapi *watermark* dinamis secara otomatis pada layer gambar KTP untuk mencegah penyalahgunaan dokumen identitas.
  * Akses penyimpanan privat terisolasi untuk berkas KTP.

---

## 4. Modul Katalog Publik & Ulasan Interaktif

Halaman publik (*Landing Page*) yang dirancang untuk memperkenalkan produk-produk lokal Mandalajati kepada masyarakat luas.

### Fitur Utama:
* **Katalog Produk Interaktif:** Pencarian dan filter produk berdasarkan Sektor Usaha (Kuliner, Fashion, Jasa, Kerajinan), Kategori, dan Wilayah Kelurahan.
* **Sistem Rating & Ulasan (Review):**
  * Pengunjung publik dapat memberikan penilaian bintang (1-5) dan ulasan terhadap produk UMKM.
  * Fitur **Like / Dislike** pada ulasan untuk menentukan tingkat relevansi dan kualitas ulasan masyarakat.
* **Etalase Produk Unggulan:** Pemajangan otomatis 12 produk unggulan teratas di halaman depan aplikasi.

---

## 5. Modul Sistem Pendukung Keputusan (Decision Support System - DSS)

Sistem analitik berbasis matematika untuk membantu pimpinan (Camat/Kasi Ekbang) menentukan penerima program bantuan/pembinaan UMKM secara objektif.

### Fitur Utama:
* **Metode Hybrid AHP & SAW:**
  * **AHP (Analytical Hierarchy Process):** Menghitung pembobotan kriteria secara konsisten (Modul *Pairwise Comparison*).
  * **SAW (Simple Additive Weighting):** Melakukan perankingan alternatif UMKM berdasarkan skor akhir terbobot.
* **Adaptive DSS:**
  * Penyesuaian kriteria secara dinamis berdasarkan jenis program bantuan (misal: Bantuan Modal, Pelatihan Digital, atau Pameran).
  * Penjelasan rekomendasi (*Explainable Recommendation*) yang menguraikan alasan logis mengapa suatu UMKM menempati peringkat tertentu.
* **Riwayat DSS & Laporan Perankingan:** Menyimpan histori hasil kalkulasi perankingan untuk transparansi dan audit auditabilitas.

---

## 6. Modul Asisten AI "Mang Loka" & Executive Assistant

Integrasi Kecerdasan Buatan (AI) untuk pelayanan publik 24/7 dan asisten analisis eksekutif pimpinan.

### Fitur Utama:
* **Chatbot Pintar "Mang Loka" (Public Portal):**
  * **Static FAQ Fast Response:** Tombol pertanyaan sering ditanyakan (FAQ) dengan jawaban instan dari *cache* tanpa latensi.
  * **Tanya Mang Loka (AI Natural Language):** Menggunakan integrasi **n8n** dan model **LLM (Ollama Qwen2.5:3b / Groq / Gemini)** untuk menjawab pertanyaan kontekstual seputar syarat verifikasi, lokasi UMKM, dan perizinan.
  * **Rekomendasi UMKM Interaktif:** AI dapat memilah dan memberikan rekomendasi UMKM beserta link profil langsung kepada pengunjung.
* **Executive Assistant (Super Admin Panel):**
  * Asisten AI khusus pimpinan untuk melakukan *query* statistik, analisis tren ekonomi wilayah, dan ringkasan eksekutif data UMKM Mandalajati.

---

## 7. Modul Manajemen Pelatihan & Scanner Absensi QR

Fasilitasi pengembangan kapasitas pelaku usaha melalui program pelatihan terstruktur.

### Fitur Utama:
* **Publikasi & Pendaftaran Pelatihan:**
  * Admin membuat program pelatihan (misal: Pelatihan Pemasaran Digital, Keuangan UMKM, Legalitas NIB).
  * Pelaku UMKM dapat mendaftar pelatihan secara langsung melalui *dashboard*.
* **Scanner Absensi QR Code (Petugas Lapangan):**
  * Operator Lapangan dapat memindai (*scan*) tiket QR Code peserta pelatihan di lokasi acara untuk konfirmasi kehadiran secara *real-time*.

---

## 8. Modul Pengajuan Program Bantuan & Flyer Digital

Wadah komunikasi program bantuan dari pemerintah ke pelaku usaha.

### Fitur Utama:
* **Pengajuan Bantuan Mandiri:** Pelaku UMKM dapat mengajukan permohonan bantuan (modal/alat/sertifikasi) dan memantau status pencairan (*Status Tracking*).
* **Flyer Program Bantuan:** Admin dapat mengunggah dan mempublikasikan *flyer/banner* program bantuan terbaru di *dashboard* pelaku usaha.

---

## 9. Modul Master Data & Wilayah

Pengelolaan data referensi dasar sistem untuk menjamin kerapihan struktur data.

### Fitur Utama:
* **Manajemen Wilayah:** CRUD dan Import data **Kelurahan** (Karang Pamulang, Jatihandap, Pasir Impun, Sindang Jaya) serta data **RW**.
* **Manajemen Klasifikasi Usaha:** CRUD dan Import data **Sektor Usaha** dan **Kategori Produk**.
* **Import & Export Template:** Menyediakan *template* Excel standar untuk *bulk import* master data.

---

## 10. Modul Pengaduan Masyarakat & Analisis Sentimen AI

Fasilitas penampungan aspirasi dan masukan dari publik.

### Fitur Utama:
* **Input Pengaduan:** Masyarakat dapat mengirimkan aduan atau masukan terkait pelayanan UMKM.
* **Analisis Sentimen AI:** Sistem menganalisis tingkat sentimen (Positif, Netral, Negatif) dari aduan masyarakat secara otomatis untuk membantu prioritas penanganan oleh Admin.

---

## 11. Modul Pelaporan, Ekspor Data & Kop Surat Resmi

Modul rekapitulasi data untuk keperluan administrasi dinas dan pelaporan berkala.

### Fitur Utama:
* **Ekspor Laporan Multiformat:** Mengunduh rekapitulasi data UMKM terfilter berdasarkan Wilayah, Sektor, Skala Usaha, maupun Status Verifikasi dalam format Excel / PDF.
* **Pengaturan Kop Surat Resmi:** Super Admin dapat mengunggah header/kop surat resmi instansi kecamatan untuk dicetak pada dokumen verifikasi dan surat keterangan UMKM.

---

## 12. Modul Backup, System Logs & Pengaturan Sistem

Modul pemeliharaan teknis dan keamanan platform.

### Fitur Utama:
* **Database Backup & Restore:** Pembuatan cadangan (*backup*) database secara berkala dan pemulihan data (*restore*) jika diperlukan.
* **Audit System Logs:** Memantau seluruh catatan riwayat aktivitas operasional pengguna (siapa, melakukan apa, kapan) untuk mencegah tindakan tanpa otoritas.
* **System Settings:** Pengaturan nama aplikasi, deskripsi portal, email kontak, dan parameter konfigurasi global.

---

## Ringkasan Matriks Hak Akses Peran (Role Matrix)

| Fitur / Modul | Super Admin | Admin Kecamatan | Operator Lapangan | Pelaku UMKM | Publik |
| :--- | :---: | :---: | :---: | :---: | :---: |
| **Melihat Katalog & Warta** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Chatbot AI "Mang Loka"** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Ulasan & Rating Produk** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Registrasi Usaha Mandiri** | ❌ | ❌ | ❌ | ✅ | ❌ |
| **Input Pendataan Lapangan** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Scan Absensi QR Pelatihan**| ❌ | ❌ | ✅ | ❌ | ❌ |
| **Verifikasi Data & KTP** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Akses Modul DSS (AHP/SAW)**| ✅ | ✅ | ❌ | ❌ | ❌ |
| **Manajemen User & Role** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Manajemen Master Data** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Backup & System Logs** | ✅ | ❌ | ❌ | ❌ | ❌ |
