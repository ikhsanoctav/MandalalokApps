<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('kategori_umkms');

        Schema::create('kategori_umkms', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori'); // Mikro, Kecil, Menengah
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_umkms');
    }
};
