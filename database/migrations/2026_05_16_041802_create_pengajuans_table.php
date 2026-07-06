<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->uuid('id_pengajuan')->primary();
            $table->uuid('umkm_id');
            $table->enum('jenis_pengajuan', ['pembiayaan', 'bantuan', 'perizinan', 'lainnya']);
            $table->decimal('nominal', 15, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['menunggu', 'proses', 'disetujui', 'ditolak', 'dicairkan'])->default('menunggu');
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_disetujui')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('umkm_id')->references('id_umkm')->on('umkms')->onDelete('cascade');

            // Index
            $table->index('umkm_id');
            $table->index('status');
            $table->index('tanggal_pengajuan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};
