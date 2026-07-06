<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifikasi_lapangan', function (Blueprint $table) {
            $table->id();
            $table->uuid('umkm_id');
            $table->unsignedBigInteger('petugas_id');
            $table->date('tanggal_kunjungan');
            $table->enum('status_kunjungan', [
                'belum_dikunjungi',
                'dikunjungi',
                'butuh_tindak_lanjut',
            ])->default('dikunjungi');
            $table->enum('kondisi_usaha', [
                'sesuai',
                'tidak_sesuai',
                'tutup_sementara',
                'tidak_ditemukan',
            ])->default('sesuai');
            $table->text('catatan_kunjungan')->nullable();
            $table->json('foto_kunjungan')->nullable();
            $table->decimal('latitude_kunjungan', 10, 7)->nullable();
            $table->decimal('longitude_kunjungan', 10, 7)->nullable();
            $table->timestamps();

            $table->index('umkm_id');
            $table->index('petugas_id');
            $table->index('status_kunjungan');
            $table->index('tanggal_kunjungan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifikasi_lapangan');
    }
};
