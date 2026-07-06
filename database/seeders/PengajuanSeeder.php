<?php

namespace Database\Seeders;

use App\Models\Pengajuan;
use App\Models\UMKM;
use Illuminate\Database\Seeder;

class PengajuanSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil UMKM yang sudah terverifikasi
        $umkm = UMKM::where('status_verifikasi', 'terverifikasi')->first();

        if (! $umkm) {
            $this->command->warn('⚠️ No UMKM found, skip PengajuanSeeder');

            return;
        }

        $pengajuanData = [
            [
                'umkm_id' => $umkm->id_umkm,
                'jenis_pengajuan' => 'pembiayaan',
                'nominal' => 25000000,
                'keterangan' => 'Pengajuan modal usaha untuk ekspansi cabang',
                'status' => 'disetujui',
                'tanggal_pengajuan' => '2026-04-01',
                'tanggal_disetujui' => '2026-04-05',
                'catatan' => 'Layak diberikan pembiayaan',
            ],
            [
                'umkm_id' => $umkm->id_umkm,
                'jenis_pengajuan' => 'bantuan',
                'nominal' => 10000000,
                'keterangan' => 'Bantuan peralatan produksi',
                'status' => 'proses',
                'tanggal_pengajuan' => '2026-04-10',
                'tanggal_disetujui' => null,
                'catatan' => 'Sedang dalam verifikasi',
            ],
            [
                'umkm_id' => $umkm->id_umkm,
                'jenis_pengajuan' => 'perizinan',
                'nominal' => null,
                'keterangan' => 'Bantuan pengurusan NIB',
                'status' => 'menunggu',
                'tanggal_pengajuan' => '2026-04-15',
                'tanggal_disetujui' => null,
                'catatan' => 'Menunggu dokumen lengkap',
            ],
        ];

        foreach ($pengajuanData as $data) {
            Pengajuan::create($data);
        }

        $this->command->info('✅ Pengajuan created: '.count($pengajuanData).' records');
    }
}
