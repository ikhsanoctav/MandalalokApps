<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $pelakuNikHashes = DB::table('users')
            ->join('model_has_roles', function ($join) {
                $join->on('model_has_roles.model_id', '=', 'users.id')
                    ->where('model_has_roles.model_type', '=', \App\Models\User::class);
            })
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', 'pelaku_umkm')
            ->whereNotNull('users.nik_hash')
            ->pluck('users.nik_hash');

        if ($pelakuNikHashes->isEmpty()) {
            return;
        }

        DB::table('pemiliks')
            ->whereIn('nik_hash', $pelakuNikHashes)
            ->update([
                'status_verifikasi_ktp' => 'terverifikasi',
                'catatan' => null,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        //
    }
};
