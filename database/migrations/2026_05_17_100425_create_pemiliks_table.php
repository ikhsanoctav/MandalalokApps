<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemiliks', function (Blueprint $table) {
            $table->uuid('id_pemilik')->primary();
            $table->text('nik');
            $table->string('nik_hash')->nullable()->unique();
            $table->string('nama_lengkap', 100);
            $table->string('tempat_lahir', 50)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('no_hp', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('kelurahan', 50);
            $table->unsignedBigInteger('id_kelurahan')->nullable();
            $table->string('kecamatan', 50);
            $table->string('kota_kab', 50);
            $table->string('provinsi', 50);
            $table->string('kode_pos', 10)->nullable();
            $table->enum('status_verifikasi_ktp', ['pending', 'terverifikasi', 'ditolak'])->default('pending');
            $table->string('foto_ktp')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            // $table->index('nik'); // Removed because TEXT column cannot be indexed without length, and nik_hash is already indexed
            $table->index('kelurahan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemiliks');
    }
};
