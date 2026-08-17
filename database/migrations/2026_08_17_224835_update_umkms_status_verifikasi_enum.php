<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah enum baru tanpa hapus yang lama
        DB::statement("ALTER TABLE umkms MODIFY COLUMN status_verifikasi ENUM('draft', 'menunggu_verifikasi', 'terkirim', 'terverifikasi', 'ditolak') DEFAULT 'draft'");
        
        // 2. Update data lama
        DB::table('umkms')->where('status_verifikasi', 'terkirim')->update(['status_verifikasi' => 'menunggu_verifikasi']);
        
        // 3. Hapus enum lama
        DB::statement("ALTER TABLE umkms MODIFY COLUMN status_verifikasi ENUM('draft', 'menunggu_verifikasi', 'terverifikasi', 'ditolak') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Tambah enum lama tanpa hapus yang baru
        DB::statement("ALTER TABLE umkms MODIFY COLUMN status_verifikasi ENUM('draft', 'menunggu_verifikasi', 'terkirim', 'terverifikasi', 'ditolak') DEFAULT 'draft'");
        
        // 2. Update data baru menjadi lama
        DB::table('umkms')->where('status_verifikasi', 'menunggu_verifikasi')->update(['status_verifikasi' => 'terkirim']);
        
        // 3. Hapus enum baru
        DB::statement("ALTER TABLE umkms MODIFY COLUMN status_verifikasi ENUM('draft', 'terkirim', 'terverifikasi', 'ditolak') DEFAULT 'draft'");
    }
};
