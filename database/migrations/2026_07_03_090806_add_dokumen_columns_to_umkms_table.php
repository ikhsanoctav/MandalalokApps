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
        Schema::table('umkms', function (Blueprint $table) {
            $table->string('dokumen_ktp')->nullable()->after('foto_utama');
            $table->string('dokumen_nib')->nullable()->after('dokumen_ktp');
            $table->string('dokumen_lainnya')->nullable()->after('dokumen_nib');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('umkms', function (Blueprint $table) {
            $table->dropColumn(['dokumen_ktp', 'dokumen_nib', 'dokumen_lainnya']);
        });
    }
};
