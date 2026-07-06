<?php

use App\Models\UMKM;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        UMKM::query()
            ->where(function ($query) {
                $query->where('status_verifikasi', '!=', 'terverifikasi')
                    ->orWhereNull('no_pendaftaran');
            })
            ->orderBy('created_at')
            ->each(function (UMKM $umkm) {
                $umkm->status_verifikasi = 'terverifikasi';
                $umkm->tanggal_verifikasi ??= now();
                $umkm->catatan_penolakan = null;

                if (empty($umkm->no_pendaftaran)) {
                    $umkm->no_pendaftaran = $umkm->generateNoPendaftaran();
                }

                $umkm->save();
            });
    }

    public function down(): void
    {
        //
    }
};
