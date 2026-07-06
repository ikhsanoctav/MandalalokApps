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
            $table->string('bentuk_jualan')->nullable()->after('id_sektor');
            
            // Drop foreign key first before changing the column
            $table->dropForeign(['id_kategori']);
            $table->unsignedBigInteger('id_kategori')->nullable()->change();
            
            // Re-add foreign key constraint with SET NULL on delete
            $table->foreign('id_kategori')->references('id')->on('kategori_umkms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('umkms', function (Blueprint $table) {
            $table->dropColumn('bentuk_jualan');
            
            $table->dropForeign(['id_kategori']);
            $table->unsignedBigInteger('id_kategori')->nullable(false)->change();
            $table->foreign('id_kategori')->references('id')->on('kategori_umkms')->onDelete('restrict');
        });
    }
};
