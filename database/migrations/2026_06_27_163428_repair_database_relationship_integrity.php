<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        $this->repairKelurahanMaster();
        $this->repairKelurahanReferences();
        $this->repairUmkmRelationTypes();
        $this->addMissingForeignKeys();
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        $this->dropForeignKeyIfExists('verifikasi_lapangan', 'verifikasi_lapangan_petugas_id_foreign');
        $this->dropForeignKeyIfExists('verifikasi_lapangan', 'verifikasi_lapangan_umkm_id_foreign');
        $this->dropForeignKeyIfExists('umkms', 'umkms_id_petugas_foreign');
        $this->dropForeignKeyIfExists('umkms', 'umkms_id_sektor_foreign');
        $this->dropForeignKeyIfExists('umkms', 'umkms_id_kategori_foreign');
        $this->dropForeignKeyIfExists('umkms', 'umkms_id_pemilik_foreign');
        $this->dropForeignKeyIfExists('users', 'users_id_kelurahan_foreign');
        $this->dropForeignKeyIfExists('pemiliks', 'pemiliks_id_kelurahan_foreign');
    }

    private function repairKelurahanMaster(): void
    {
        if (! Schema::hasTable('kelurahans')) {
            return;
        }

        DB::table('kelurahans')
            ->whereIn('kode_kelurahan', ['KEL-003', 'KEL-004', 'KEL-005'])
            ->update(['kode_kelurahan' => DB::raw("CONCAT('TMP-', id)")]);

        $kelurahans = [
            1 => ['kode_kelurahan' => 'KEL-001', 'nama_kelurahan' => 'Karang Pamulang'],
            2 => ['kode_kelurahan' => 'KEL-002', 'nama_kelurahan' => 'Sindangjaya'],
            3 => ['kode_kelurahan' => 'KEL-003', 'nama_kelurahan' => 'Cikadut'],
            4 => ['kode_kelurahan' => 'KEL-004', 'nama_kelurahan' => 'Pasir Impun'],
            5 => ['kode_kelurahan' => 'KEL-005', 'nama_kelurahan' => 'Jati Handap'],
        ];

        foreach ($kelurahans as $id => $data) {
            DB::table('kelurahans')->updateOrInsert(
                ['id' => $id],
                [
                    'kode_kelurahan' => $data['kode_kelurahan'],
                    'nama_kelurahan' => $data['nama_kelurahan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }

        $maxId = DB::table('kelurahans')->max('id') ?: 1;
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE kelurahans AUTO_INCREMENT = '.($maxId + 1));
        }

        $this->ensureRwRtForKelurahans();
    }

    private function ensureRwRtForKelurahans(): void
    {
        if (! Schema::hasTable('rws') || ! Schema::hasTable('rts')) {
            return;
        }

        $kelurahanIds = DB::table('kelurahans')->pluck('id');

        foreach ($kelurahanIds as $kelurahanId) {
            for ($rw = 1; $rw <= 6; $rw++) {
                $nomorRw = str_pad((string) $rw, 2, '0', STR_PAD_LEFT);

                DB::table('rws')->updateOrInsert(
                    [
                        'kelurahan_id' => $kelurahanId,
                        'nomor_rw' => $nomorRw,
                    ],
                    [
                        'updated_at' => now(),
                        'created_at' => now(),
                    ],
                );

                $rwId = DB::table('rws')
                    ->where('kelurahan_id', $kelurahanId)
                    ->where('nomor_rw', $nomorRw)
                    ->value('id');

                for ($rt = 1; $rt <= 4; $rt++) {
                    DB::table('rts')->updateOrInsert(
                        [
                            'rw_id' => $rwId,
                            'nomor_rt' => str_pad((string) $rt, 2, '0', STR_PAD_LEFT),
                        ],
                        [
                            'updated_at' => now(),
                            'created_at' => now(),
                        ],
                    );
                }
            }
        }
    }

    private function repairKelurahanReferences(): void
    {
        foreach (['pemiliks', 'users'] as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'id_kelurahan') || ! Schema::hasColumn($table, 'kelurahan')) {
                continue;
            }

            DB::statement("
                UPDATE {$table}
                JOIN kelurahans ON kelurahans.nama_kelurahan = {$table}.kelurahan
                SET {$table}.id_kelurahan = kelurahans.id
                WHERE {$table}.kelurahan IS NOT NULL
                  AND {$table}.kelurahan != ''
            ");

            DB::statement("
                UPDATE {$table}
                LEFT JOIN kelurahans ON kelurahans.id = {$table}.id_kelurahan
                SET {$table}.id_kelurahan = NULL
                WHERE {$table}.id_kelurahan IS NOT NULL
                  AND kelurahans.id IS NULL
            ");
        }
    }

    private function repairUmkmRelationTypes(): void
    {
        if (! Schema::hasTable('umkms')) {
            return;
        }

        DB::statement("
            UPDATE umkms
            LEFT JOIN pemiliks ON pemiliks.id_pemilik = umkms.id_pemilik
            SET umkms.id_pemilik = NULL
            WHERE pemiliks.id_pemilik IS NULL
        ");

        DB::statement("
            UPDATE umkms
            LEFT JOIN users ON users.id = CAST(umkms.id_petugas AS UNSIGNED)
            SET umkms.id_petugas = NULL
            WHERE umkms.id_petugas IS NOT NULL
              AND (umkms.id_petugas NOT REGEXP '^[0-9]+$' OR users.id IS NULL)
        ");

        DB::statement('ALTER TABLE umkms MODIFY id_kategori BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE umkms MODIFY id_sektor BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE umkms MODIFY id_petugas BIGINT UNSIGNED NULL');
    }

    private function addMissingForeignKeys(): void
    {
        $this->addForeignKeyIfMissing('pemiliks', 'pemiliks_id_kelurahan_foreign', 'id_kelurahan', 'kelurahans', 'id', 'SET NULL');
        $this->addForeignKeyIfMissing('users', 'users_id_kelurahan_foreign', 'id_kelurahan', 'kelurahans', 'id', 'SET NULL');

        $this->addForeignKeyIfMissing('umkms', 'umkms_id_pemilik_foreign', 'id_pemilik', 'pemiliks', 'id_pemilik', 'RESTRICT');
        $this->addForeignKeyIfMissing('umkms', 'umkms_id_kategori_foreign', 'id_kategori', 'kategori_umkms', 'id', 'RESTRICT');
        $this->addForeignKeyIfMissing('umkms', 'umkms_id_sektor_foreign', 'id_sektor', 'sektor_umkms', 'id', 'RESTRICT');
        $this->addForeignKeyIfMissing('umkms', 'umkms_id_petugas_foreign', 'id_petugas', 'users', 'id', 'SET NULL');

        $this->addForeignKeyIfMissing('verifikasi_lapangan', 'verifikasi_lapangan_umkm_id_foreign', 'umkm_id', 'umkms', 'id_umkm', 'CASCADE');
        $this->addForeignKeyIfMissing('verifikasi_lapangan', 'verifikasi_lapangan_petugas_id_foreign', 'petugas_id', 'users', 'id', 'RESTRICT');
    }

    private function addForeignKeyIfMissing(
        string $table,
        string $constraint,
        string $column,
        string $referencedTable,
        string $referencedColumn,
        string $onDelete
    ): void {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column) || $this->foreignKeyExists($table, $constraint)) {
            return;
        }

        DB::statement("
            ALTER TABLE {$table}
            ADD CONSTRAINT {$constraint}
            FOREIGN KEY ({$column}) REFERENCES {$referencedTable} ({$referencedColumn})
            ON DELETE {$onDelete}
        ");
    }

    private function dropForeignKeyIfExists(string $table, string $constraint): void
    {
        if (! $this->foreignKeyExists($table, $constraint)) {
            return;
        }

        DB::statement("ALTER TABLE {$table} DROP FOREIGN KEY {$constraint}");
    }

    private function foreignKeyExists(string $table, string $constraint): bool
    {
        return DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', DB::raw('DATABASE()'))
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', $constraint)
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->exists();
    }
};
