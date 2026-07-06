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
        Schema::create('dss_hasil_saws', function (Blueprint $table) {
            $table->id();
            $table->uuid('umkm_id');
            $table->foreign('umkm_id')->references('id_umkm')->on('umkms')->onDelete('cascade');
            $table->foreignId('kriteria_id')->nullable()->constrained('kriterias')->onDelete('cascade'); // nullable for total score row
            $table->decimal('nilai_normalisasi', 8, 4)->nullable();
            $table->decimal('bobot', 8, 4)->nullable();
            $table->decimal('kontribusi_skor', 8, 4)->nullable(); // normalisasi * bobot
            $table->decimal('total_skor', 8, 4)->nullable();
            $table->integer('ranking')->nullable();
            $table->string('periode')->nullable();
            $table->string('profil')->default('default');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dss_hasil_saws');
    }
};
