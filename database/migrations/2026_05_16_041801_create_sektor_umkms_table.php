<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('sektor_umkms');

        Schema::create('sektor_umkms', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sektor'); // Kuliner, Fashion, Jasa, dll
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sektor_umkms');
    }
};
