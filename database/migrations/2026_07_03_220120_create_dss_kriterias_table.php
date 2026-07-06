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
        Schema::create('dss_kriterias', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kriteria', 10);
            $table->string('nama_kriteria', 100);
            $table->enum('tipe_dss', ['bantuan', 'pelatihan']);
            $table->enum('jenis', ['benefit', 'cost']);
            $table->decimal('bobot', 5, 2); // Persentase, misal 20.00
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dss_kriterias');
    }
};
