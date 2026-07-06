<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('umkm_bantuans', function (Blueprint $table) {
            $table->id();
            $table->uuid('umkm_id');
            $table->string('nama_bantuan');
            $table->string('sumber_bantuan')->nullable();
            $table->year('tahun_bantuan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('umkm_id')->references('id_umkm')->on('umkms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umkm_bantuans');
    }
};
