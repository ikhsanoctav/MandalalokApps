<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Backfill pemiliks.id_kelurahan from kelurahan name string matches.
     * Uses a subquery compatible with both MySQL and SQLite.
     */
    public function up(): void
    {
        $updated = DB::update("
            UPDATE pemiliks
            SET id_kelurahan = (
                SELECT kelurahans.id
                FROM kelurahans
                WHERE kelurahans.nama_kelurahan = pemiliks.kelurahan
                LIMIT 1
            )
            WHERE pemiliks.id_kelurahan IS NULL
              AND pemiliks.kelurahan IS NOT NULL
              AND pemiliks.kelurahan != ''
              AND EXISTS (
                SELECT 1 FROM kelurahans
                WHERE kelurahans.nama_kelurahan = pemiliks.kelurahan
              )
        ");
    }

    public function down(): void
    {
        // No destructive operation – backfill is idempotent
    }
};
