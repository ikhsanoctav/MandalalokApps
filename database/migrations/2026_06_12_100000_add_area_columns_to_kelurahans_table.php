<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelurahans', function (Blueprint $table) {
            $table->string('kecamatan')->default('Mandalajati')->after('nama_kelurahan');
            $table->string('kota_kab')->default('Bandung')->after('kecamatan');
            $table->string('provinsi')->default('Jawa Barat')->after('kota_kab');
        });
    }

    public function down(): void
    {
        Schema::table('kelurahans', function (Blueprint $table) {
            $table->dropColumn(['kecamatan', 'kota_kab', 'provinsi']);
        });
    }
};
