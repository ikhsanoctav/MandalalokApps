<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('umkms', function (Blueprint $table) {
            // Primary Key
            $table->uuid('id_umkm')->primary();

            // Index / Unique
            $table->string('no_pendaftaran', 28)->unique();

            // Data Dasar UMKM
            $table->string('nama_usaha', 158);
            $table->uuid('id_pemilik');
            $table->integer('id_kategori');
            $table->integer('id_sektor');

            // Skala dan Status
            $table->enum('status_usaha', ['aktif', 'non_aktif', 'tutup', 'pindah'])->default('aktif');
            $table->year('tahun_berdiri')->nullable();

            // Perizinan
            $table->string('no_izin_usaha', 58)->nullable();
            $table->enum('jenis_izin', ['NIB', 'SIUP', 'SKU', 'Lainnya'])->nullable();
            $table->string('npwp_usaha', 28)->nullable();

            // Kontak
            $table->string('telp_usaha', 28)->nullable();
            $table->string('email_usaha', 180)->nullable();
            $table->string('website', 280)->nullable();
            $table->json('media_sosial')->nullable();
            $table->text('alamat_usaha')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('foto_utama')->nullable();
            $table->json('foto_gallery')->nullable();

            // Tenaga Kerja
            $table->smallInteger('jumlah_tenaga_kerja')->default(0);
            $table->text('riwayat_bantuan')->nullable();
            $table->smallInteger('tenaga_kerja_laki')->nullable()->default(0);
            $table->smallInteger('tenaga_kerja_perempuan')->nullable()->default(0);

            // Data Pendataan & Verifikasi
            $table->uuid('id_petugas')->nullable();
            $table->date('tanggal_pendataan');
            $table->dateTime('tanggal_verifikasi')->nullable();
            $table->enum('status_verifikasi', ['draft', 'menunggu_verifikasi', 'terverifikasi', 'ditolak'])->default('draft');
            $table->text('catatan_penolakan')->nullable();
            $table->text('catatan')->nullable();

            // Timestamps
            $table->timestamps();

            // Index untuk performa query
            $table->index('no_pendaftaran');
            $table->index('id_pemilik');
            $table->index('id_kategori');
            $table->index('id_sektor');
            $table->index('id_petugas');
            $table->index('status_verifikasi');
            $table->index('tahun_berdiri');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umkms');
    }
};
