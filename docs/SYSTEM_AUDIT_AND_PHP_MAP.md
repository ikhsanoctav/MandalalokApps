# Audit Sistem dan Peta File PHP MandalalokaApps

Tanggal audit: 2026-07-04

## Ringkasan Kesiapan

Status kode aplikasi: layak lanjut ke staging/publish setelah konfigurasi production dibereskan.

Hasil validasi:
- PHP lint untuk `app`, `routes`, `config`, dan `database`: lulus.
- Blade compile/cache: lulus.
- Route cache: lulus.
- Config cache: lulus.
- Frontend build via Docker: lulus.
- Test suite via Docker: 59 passed, 245 assertions.
- Composer validate: valid, dengan warning dependency version yang terlalu longgar.

Catatan publish yang wajib diselesaikan di server:
- `.env` masih local: `APP_ENV=local`, `APP_DEBUG=true`, `LOG_LEVEL=debug`.
- `APP_URL` masih `http://localhost:8080`.
- `SESSION_SECURE_COOKIE=false`; untuk HTTPS production sebaiknya `true`.
- Mail masih `MAIL_MAILER=log`; email asli belum aktif.
- `composer.json` memakai versi `*` untuk `laravel/reverb` dan `laravel/sanctum`; sebaiknya dikunci ke versi kompatibel.
- Pastikan scheduler/queue worker jalan jika fitur queue dipakai: `QUEUE_CONNECTION=database`.
- Pastikan `php artisan storage:link` tersedia di server. Saat audit symlink lokal sudah ada.

## Temuan dan Perbaikan Saat Audit

Perbaikan yang sudah dilakukan:
- Memperbaiki bug penolakan UMKM di `Admin\VerifikasiController`, `SuperAdmin\UmkmController`, dan `Api\VerifikasiApiController`. Sebelumnya action reject malah menyimpan `status_verifikasi = terverifikasi`; sekarang benar menjadi `ditolak`.
- Memperbaiki `database/seeders/DssKriteriaSeeder.php` yang punya baris kosong sebelum `<?php`, sehingga sebelumnya gagal `php -l`.
- Menyesuaikan `phpunit.xml` agar testing Docker memakai `DB_HOST=mysql`, sesuai service database di `compose.yaml`.
- Menyinkronkan registrasi pelaku UMKM di `RegisteredUserController`: data wilayah tersimpan ke `users`, record `pemiliks` dibuat, dan status KTP otomatis `terverifikasi`.

Potensi tabrakan:
- Tidak ditemukan route collision fatal. Prefix role memisahkan `admin`, `superadmin`, `operator`, dan `pelaku`.
- Ada dua namespace folder yang berbeda kapitalisasi: `SuperAdmin` dan `Superadmin`. Di Linux ini legal karena foldernya memang ada dua, tetapi untuk pengembangan jangka panjang sebaiknya disatukan agar tidak membingungkan.
- Istilah "verifikasi" masih dipakai pada beberapa fitur admin/operator/API. Secara bisnis pelaku UMKM tidak perlu menunggu verifikasi, tetapi module lama masih ada untuk kompatibilitas, pelaporan, atau kontrol admin.

Penilaian maintainability: 7/10.

Kuat:
- Role dan prefix route cukup jelas.
- Test coverage sudah lumayan untuk auth, DSS, flyer, pengajuan, profil, notifikasi, dan beberapa UI flow.
- Model utama punya relasi dan accessor yang membantu kompatibilitas data lama.
- Service layer sudah mulai dipakai untuk aktivitas, DSS, pengajuan, report, UMKM.

Perlu dirapikan agar lebih mudah dikembangkan:
- Controller masih besar, terutama `SuperAdmin\UmkmController`.
- Banyak logika status verifikasi tersebar di controller, view, API, dan test; idealnya dibuat enum/constant/helper terpusat.
- Banyak Blade inline style dan script panjang; lebih mudah dirawat kalau dipindah ke component/asset terpisah.
- Namespace `SuperAdmin` vs `Superadmin` perlu distandarkan.
- Route web cukup padat; bisa dipisah per role (`routes/admin.php`, `routes/pelaku.php`, `routes/operator.php`).

## Role dan File Terkait

### Public / Tamu

Route:
- `routes/web.php`: welcome, katalog UMKM, warta, chatbot web endpoint.
- `routes/api.php`: statistik publik, peta publik, chatbot context, login API.

Controller:
- `app/Http/Controllers/KatalogUmkmController.php`: katalog publik, rating, like/dislike ulasan.
- `app/Http/Controllers/ChatbotController.php`: chatbot publik/n8n.
- `app/Http/Controllers/BeritaController.php`: warta publik untuk user login.
- `app/Http/Controllers/Api/PublicStatistikController.php`: data chart, statistik, map, context chatbot.

View:
- `resources/views/welcome.blade.php`: landing page, visualisasi data, carousel katalog, peta, chatbot.
- `resources/views/katalog-umkm.blade.php`: katalog publik UMKM.
- `resources/views/warta/show.blade.php`: detail warta.
- `resources/views/partials/program-flyer-popup.blade.php`: popup program bantuan.
- `resources/views/partials/unified-theme.blade.php`: tema UI terpusat yang discoping ke content.

### Auth dan Profil

Route:
- `routes/auth.php`: login, register, logout, reset password, email verification.
- `routes/web.php`: profile umum dan redirect dashboard berdasarkan role.

Controller:
- `app/Http/Controllers/Auth/RegisteredUserController.php`: registrasi pelaku UMKM, sinkron `users` dan `pemiliks`.
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`: login/logout.
- `app/Http/Controllers/Auth/PasswordResetLinkController.php`: request reset password via approval admin.
- `app/Http/Controllers/Auth/NewPasswordController.php`: reset password token.
- `app/Http/Controllers/Auth/PasswordController.php`: update password.
- `app/Http/Controllers/Auth/ConfirmablePasswordController.php`: konfirmasi password.
- `app/Http/Controllers/Auth/EmailVerificationPromptController.php`: halaman verifikasi email.
- `app/Http/Controllers/Auth/VerifyEmailController.php`: proses verifikasi email.
- `app/Http/Controllers/Auth/EmailVerificationNotificationController.php`: kirim ulang verifikasi email.
- `app/Http/Controllers/ProfileController.php`: profil bawaan user.

View:
- `resources/views/auth/*.blade.php`: halaman login, register, forgot/reset password, verify email.
- `resources/views/profile/**/*.blade.php`: halaman profil umum.

### Super Admin

Route:
- `routes/superadmin.php`: seluruh route superadmin dengan prefix `superadmin`.

Controller:
- `app/Http/Controllers/SuperAdmin/DashboardController.php`: dashboard dan chart superadmin.
- `app/Http/Controllers/SuperAdmin/UmkmController.php`: CRUD/import/export/QR/dokumen/suspend/verifikasi UMKM.
- `app/Http/Controllers/SuperAdmin/UserController.php`: CRUD user, role, reset password.
- `app/Http/Controllers/SuperAdmin/KategoriController.php`: master kategori UMKM.
- `app/Http/Controllers/SuperAdmin/SektorController.php`: master sektor UMKM.
- `app/Http/Controllers/SuperAdmin/KelurahanController.php`: master kelurahan.
- `app/Http/Controllers/SuperAdmin/RwController.php`: master RW.
- `app/Http/Controllers/SuperAdmin/PengajuanController.php`: pengajuan bantuan/program.
- `app/Http/Controllers/SuperAdmin/BeritaController.php`: warta/berita.
- `app/Http/Controllers/SuperAdmin/BackupController.php`: backup download/delete/run.
- `app/Http/Controllers/SuperAdmin/FlyerController.php`: flyer program bantuan.
- `app/Http/Controllers/SuperAdmin/SystemController.php`: laporan, logs, settings.
- `app/Http/Controllers/Superadmin/DssController.php`: DSS SAW versi superadmin.
- `app/Http/Controllers/Superadmin/PengaduanController.php`: pengaduan/sentimen AI.
- `app/Http/Controllers/Superadmin/ExecutiveAssistantController.php`: asisten eksekutif.

View:
- `resources/views/layouts/superadmin.blade.php`: layout superadmin.
- `resources/views/superadmin/dashboard.blade.php`: dashboard.
- `resources/views/superadmin/umkm/*.blade.php`: UMKM superadmin.
- `resources/views/superadmin/users/*.blade.php`: manajemen user.
- `resources/views/superadmin/master/**/*.blade.php`: master data.
- `resources/views/superadmin/pengajuan/*.blade.php`: pengajuan.
- `resources/views/superadmin/dss/*.blade.php`: DSS.
- `resources/views/superadmin/flyer/index.blade.php`: flyer.
- `resources/views/superadmin/backup/index.blade.php`: backup.
- `resources/views/superadmin/settings.blade.php`: settings.
- `resources/views/superadmin/assistant.blade.php`: asisten eksekutif.
- `resources/views/superadmin/pengaduan/index.blade.php`: pengaduan.

### Admin Kecamatan

Route:
- `routes/web.php`: group prefix `admin`.

Controller:
- `app/Http/Controllers/Admin/DashboardController.php`: dashboard admin.
- `app/Http/Controllers/Admin/UmkmController.php`: CRUD UMKM admin.
- `app/Http/Controllers/Admin/VerifikasiController.php`: modul verifikasi/penolakan UMKM lama.
- `app/Http/Controllers/Admin/VerifikasiAkunController.php`: verifikasi KTP akun.
- `app/Http/Controllers/Admin/PengajuanController.php`: pengajuan bantuan/program.
- `app/Http/Controllers/Admin/LaporanController.php`: laporan/export.

View:
- `resources/views/layouts/admin.blade.php`: layout admin.
- `resources/views/admin/dashboard.blade.php`: dashboard.
- `resources/views/admin/umkm/*.blade.php`: UMKM admin.
- `resources/views/admin/verifikasi/*.blade.php`: verifikasi UMKM lama.
- `resources/views/admin/verifikasi_akun/*.blade.php`: verifikasi akun/KTP.
- `resources/views/admin/pengajuan/*.blade.php`: pengajuan.
- `resources/views/admin/laporan/index.blade.php`: laporan.

### Operator Lapangan

Route:
- `routes/web.php`: group prefix `operator`.

Controller:
- `app/Http/Controllers/Petugas/DashboardController.php`: dashboard operator.
- `app/Http/Controllers/Petugas/UmkmController.php`: input/edit UMKM, upload foto, QR/dokumen.
- `app/Http/Controllers/Petugas/VerifikasiController.php`: kunjungan/verifikasi lapangan.

View:
- `resources/views/layouts/petugas.blade.php`: layout operator.
- `resources/views/petugas/dashboard.blade.php`: dashboard.
- `resources/views/petugas/umkm/*.blade.php`: UMKM operator.
- `resources/views/petugas/verifikasi/*.blade.php`: kunjungan lapangan.

### Pelaku UMKM

Route:
- `routes/web.php`: group prefix `pelaku`.

Controller:
- `app/Http/Controllers/Pelaku/DashboardController.php`: dashboard pelaku.
- `app/Http/Controllers/Pelaku/ProfilController.php`: profil pelaku dan KTP.
- `app/Http/Controllers/Pelaku/UmkmController.php`: daftar/edit UMKM mandiri, langsung terverifikasi.
- `app/Http/Controllers/Pelaku/ProdukController.php`: CRUD produk.
- `app/Http/Controllers/Pelaku/PengajuanController.php`: pengajuan bantuan.

View:
- `resources/views/layouts/pelaku.blade.php`: layout pelaku.
- `resources/views/pelaku/dashboard.blade.php`: dashboard.
- `resources/views/pelaku/profil/edit.blade.php`: profil.
- `resources/views/pelaku/umkm/*.blade.php`: UMKM mandiri.
- `resources/views/pelaku/produk/*.blade.php`: produk.
- `resources/views/pelaku/pengajuan/index.blade.php`: pengajuan bantuan.

### API / Mobile

Route:
- `routes/api.php`: API Sanctum dan endpoint publik.

Controller:
- `app/Http/Controllers/Api/AuthController.php`: login/logout/me token Sanctum.
- `app/Http/Controllers/Api/DashboardController.php`: dashboard API.
- `app/Http/Controllers/Api/PublicStatistikController.php`: statistik publik.
- `app/Http/Controllers/Api/UmkmApiController.php`: CRUD UMKM API.
- `app/Http/Controllers/Api/VerifikasiApiController.php`: verifikasi/penolakan API lama.
- `app/Http/Controllers/Api/WilayahApiController.php`: kelurahan/RW/RT/kategori/sektor.
- `app/Http/Controllers/Api/PemilikApiController.php`: cek NIK.
- `app/Http/Controllers/Api/NotificationController.php`: notifikasi API.
- `app/Http/Controllers/Api/BeritaController.php`: berita API.

## File PHP Berdasarkan Fitur

### Routing

| File | Fungsi |
|---|---|
| `routes/web.php` | Route web utama: welcome, katalog, role admin/operator/pelaku, DSS umum, notification, profile. |
| `routes/superadmin.php` | Route khusus superadmin. |
| `routes/api.php` | REST API publik dan Sanctum. |
| `routes/auth.php` | Route autentikasi Laravel/Breeze. |
| `routes/channels.php` | Channel broadcast. |
| `routes/console.php` | Route/command console. |

### Middleware, Request, Trait

| File | Fungsi |
|---|---|
| `app/Http/Middleware/RoleMiddleware.php` | Guard akses berdasarkan role. |
| `app/Http/Requests/ProfileUpdateRequest.php` | Validasi update profil umum. |
| `app/Http/Requests/Auth/LoginRequest.php` | Validasi dan throttle login web. |
| `app/Http/Requests/Api/LoginRequest.php` | Validasi login API. |
| `app/Http/Requests/Api/StoreUmkmRequest.php` | Validasi store UMKM API. |
| `app/Http/Requests/Api/UploadFotoUmkmRequest.php` | Validasi upload foto UMKM API. |
| `app/Http/Requests/Api/InputGpsUmkmRequest.php` | Validasi input GPS API. |
| `app/Http/Traits/DashboardChartTrait.php` | Helper data chart dashboard. |
| `app/Http/Traits/HandlesPengajuanBerkas.php` | Helper berkas pengajuan. |

### Model Domain

| File | Fungsi |
|---|---|
| `app/Models/User.php` | User login, role, NIK terenkripsi, wilayah. |
| `app/Models/Pemilik.php` | Identitas pemilik UMKM, NIK terenkripsi, wilayah. |
| `app/Models/UMKM.php` | Entitas usaha UMKM. |
| `app/Models/Produk.php` | Produk milik UMKM. |
| `app/Models/KategoriUMKM.php` | Master kategori UMKM. |
| `app/Models/SektorUmkm.php` | Master sektor UMKM. |
| `app/Models/Kelurahan.php` | Master kelurahan. |
| `app/Models/Rw.php` | Master RW. |
| `app/Models/Rt.php` | Master RT. |
| `app/Models/Pengajuan.php` | Pengajuan bantuan/program. |
| `app/Models/Berita.php` | Warta/berita. |
| `app/Models/Aktivitas.php` | Log aktivitas sistem. |
| `app/Models/Setting.php` | Pengaturan sistem. |
| `app/Models/VerifikasiLapangan.php` | Hasil kunjungan lapangan operator. |
| `app/Models/UmkmRating.php` | Rating dan ulasan katalog. |
| `app/Models/UmkmBantuan.php` | Riwayat bantuan UMKM. |
| `app/Models/DssAnalysis.php` | Riwayat analisis DSS. |
| `app/Models/DssKriteria.php` | Kriteria DSS lama/khusus. |
| `app/Models/Kriteria.php` | Kriteria DSS SAW. |
| `app/Models/DssPenilaian.php` | Penilaian DSS SAW. |
| `app/Models/DssBobot.php` | Bobot DSS SAW. |
| `app/Models/DssHasilSaw.php` | Hasil ranking SAW. |

### Service, Import, Export, Notification, Command

| File | Fungsi |
|---|---|
| `app/Services/AktivitasLogger.php` | Logging aktivitas terpusat. |
| `app/Services/DssService.php` | Integrasi DSS/n8n dan analisis. |
| `app/Services/OllamaService.php` | Integrasi Ollama/local LLM. |
| `app/Services/PengajuanService.php` | Logika pengajuan bantuan. |
| `app/Services/ReportGenerator.php` | Generate report. |
| `app/Services/UmkmService.php` | Logika reusable UMKM. |
| `app/Imports/UmkmImport.php` | Import UMKM dari Excel/CSV. |
| `app/Imports/MasterKategoriImport.php` | Import master kategori. |
| `app/Imports/MasterKelurahanImport.php` | Import master kelurahan. |
| `app/Imports/MasterSektorImport.php` | Import master sektor. |
| `app/Exports/UmkmExport.php` | Export data UMKM. |
| `app/Notifications/SystemNotification.php` | Notifikasi umum. |
| `app/Notifications/StatusVerifikasiDiperbarui.php` | Notifikasi status verifikasi. |
| `app/Notifications/TugasLapanganBaru.php` | Notifikasi tugas lapangan. |
| `app/Notifications/UmkmBaruDidaftarkan.php` | Notifikasi UMKM baru. |
| `app/Console/Commands/EncryptNikCommand.php` | Command enkripsi NIK data lama. |

### Provider dan Component Class

| File | Fungsi |
|---|---|
| `app/Providers/AppServiceProvider.php` | Bootstrapping service aplikasi. |
| `app/View/Components/AppLayout.php` | Class component layout app. |
| `app/View/Components/GuestLayout.php` | Class component layout guest. |

### Config

| File | Fungsi |
|---|---|
| `config/app.php` | Identitas aplikasi, env, locale. |
| `config/auth.php` | Guard dan provider auth. |
| `config/backup.php` | Konfigurasi backup. |
| `config/broadcasting.php` | Broadcast driver. |
| `config/cache.php` | Cache store. |
| `config/cors.php` | CORS. |
| `config/database.php` | Database dan Redis. |
| `config/filesystems.php` | Storage disk. |
| `config/log-viewer.php` | Log viewer. |
| `config/logging.php` | Logging channel. |
| `config/mail.php` | Mailer. |
| `config/permission.php` | Spatie permission. |
| `config/queue.php` | Queue driver. |
| `config/sanctum.php` | Sanctum token/API. |
| `config/services.php` | External services: n8n, mail provider, etc. |
| `config/session.php` | Session/cookie. |

## Database PHP Files

Migrations utama:
- `0001_01_01_000000_create_users_table.php`: tabel users.
- `0001_01_01_000001_create_cache_table.php`: cache.
- `0001_01_01_000002_create_jobs_table.php`: jobs/queue.
- `2026_05_16_041541_create_permission_tables.php`: role/permission.
- `2026_05_16_041759_create_kelurahans_table.php`: kelurahan.
- `2026_05_16_041759_create_rws_table.php`: RW.
- `2026_05_16_041800_create_kategori_umkms_table.php`: kategori UMKM.
- `2026_05_16_041800_create_rts_table.php`: RT.
- `2026_05_16_041801_create_sektor_umkms_table.php`: sektor.
- `2026_05_16_041801_create_umkms_table.php`: UMKM.
- `2026_05_16_041802_create_pengajuans_table.php`: pengajuan.
- `2026_05_16_072839_create_berita_table.php`: berita.
- `2026_05_17_100425_create_pemiliks_table.php`: pemilik.
- `2026_05_17_223855_create_sessions_table.php`: sessions.
- `2026_05_17_233302_create_aktivitas_table.php`: aktivitas.
- `2026_05_19_001141_create_notifications_table.php`: notifications.
- `2026_05_19_060000_create_settings_table.php`: settings.
- `2026_05_20_215420_create_personal_access_tokens_table.php`: Sanctum tokens.
- `2026_05_31_151656_create_password_reset_tokens_table.php`: password reset.
- `2026_06_05_000000_create_dss_analyses_table.php`: DSS analysis.
- `2026_06_06_130026_add_id_kelurahan_to_users_table.php`: FK kelurahan user.
- `2026_06_09_123827_make_no_pendaftaran_nullable_on_umkms_table.php`: no pendaftaran nullable.
- `2026_06_09_133645_add_coordinates_to_umkms_table.php`: koordinat UMKM.
- `2026_06_11_163947_add_berkas_to_pengajuans_table.php`: berkas pengajuan.
- `2026_06_11_164500_add_nama_program_to_pengajuans_table.php`: nama program.
- `2026_06_12_000000_backfill_pemiliks_id_kelurahan.php`: backfill kelurahan pemilik.
- `2026_06_12_100000_add_area_columns_to_kelurahans_table.php`: kolom area kelurahan.
- `2026_06_23_201721_create_password_reset_status_columns_to_users_table.php`: status reset password.
- `2026_06_27_080000_create_verifikasi_lapangan_table.php`: kunjungan lapangan.
- `2026_06_27_163428_repair_database_relationship_integrity.php`: perbaikan relasi.
- `2026_07_03_090806_add_dokumen_columns_to_umkms_table.php`: dokumen UMKM.
- `2026_07_03_094402_create_produks_table.php`: produk.
- `2026_07_03_215038_modify_kategori_and_add_bentuk_jualan_to_umkms_table.php`: bentuk jualan.
- `2026_07_03_220120_create_dss_kriterias_table.php`: kriteria DSS.
- `2026_07_04_000001_auto_verify_pelaku_umkm_accounts.php`: auto verifikasi akun pelaku lama.
- `2026_07_04_000002_auto_verify_all_umkms.php`: auto verifikasi semua UMKM lama.
- `2026_07_04_115300_add_perkiraan_omset_to_umkms_table.php`: perkiraan omset.
- `2026_07_04_130800_create_kriterias_table.php`: kriteria SAW.
- `2026_07_04_130806_create_dss_penilaians_table.php`: penilaian SAW.
- `2026_07_04_130811_create_dss_bobots_table.php`: bobot SAW.
- `2026_07_04_130816_create_dss_hasil_saws_table.php`: hasil SAW.
- `2026_07_04_134757_create_umkm_ratings_table.php`: rating katalog.
- `2026_07_04_141212_add_likes_to_umkm_ratings_table.php`: likes rating.
- `2026_07_04_141711_add_dislikes_to_umkm_ratings_table.php`: dislikes rating.
- `2026_07_04_143356_create_umkm_bantuans_table.php`: riwayat bantuan UMKM.

Seeders:
- `DatabaseSeeder.php`: entry point seeding.
- `RoleSeeder.php`, `RolesAndPermissionsSeeder.php`, `UsersSeeder.php`: role, permission, user awal.
- `KelurahansSeeder.php`, `RwRtSeeder.php`: wilayah.
- `KategoriUmkmSeeder.php`, `SektorUmkmSeeder.php`: master UMKM.
- `PemiliksSeeder.php`, `UmkmSeeder.php`, `UmkmFakerSeeder.php`: data UMKM.
- `PengajuanSeeder.php`: data pengajuan.
- `BeritaSeeder.php`: data berita.
- `SettingsSeeder.php`: settings.
- `DssSeeder.php`, `DssKriteriaSeeder.php`: DSS.
- `database/factories/UserFactory.php`: factory user untuk test.

## View Blade Berdasarkan Fitur

Public:
- `welcome.blade.php`, `katalog-umkm.blade.php`, `warta/show.blade.php`.

Layouts:
- `layouts/admin.blade.php`, `layouts/superadmin.blade.php`, `layouts/petugas.blade.php`, `layouts/pelaku.blade.php`, `layouts/guest.blade.php`, `layouts/navigation.blade.php`.

Components/partials:
- `components/*.blade.php`, `partials/*.blade.php`, `vendor/pagination/*.blade.php`, `vendor/log-viewer/index.blade.php`.

Role views:
- `admin/**/*.blade.php`
- `superadmin/**/*.blade.php`
- `petugas/**/*.blade.php`
- `pelaku/**/*.blade.php`

Feature views:
- `dss/*.blade.php`: DSS/AHP/SAW umum.
- `umkm/print-qr.blade.php`, `umkm/print-dokumen.blade.php`: output cetak.
- `errors/*.blade.php`: halaman error.
- `auth/*.blade.php`: autentikasi.
- `profile/**/*.blade.php`: profil user.

## Checklist Publish

Sebelum publish production:
1. Ubah `.env`: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://domain-resmi`.
2. Aktifkan HTTPS dan set `SESSION_SECURE_COOKIE=true`.
3. Konfigurasi mailer production.
4. Jalankan `composer install --no-dev --optimize-autoloader`.
5. Jalankan `npm ci` dan `npm run build` atau pakai artifact `public/build`.
6. Jalankan `php artisan migrate --force`.
7. Jalankan `php artisan storage:link` jika symlink belum ada.
8. Jalankan `php artisan config:cache`, `php artisan route:cache`, dan `php artisan view:cache`.
9. Jalankan queue worker jika memakai database queue.
10. Batasi akses log viewer dan backup hanya untuk superadmin/production-safe network.
