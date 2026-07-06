<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;
use App\Models\UMKM;
use App\Models\DssPenilaian;

class DssSeeder extends Seeder
{
    public function run(): void
    {
        $kriterias = [
            ['kode' => 'C1', 'nama_kriteria' => 'Omset per Bulan (Juta)', 'tipe' => 'cost'], // Cost: Semakin kecil omset, semakin prioritas dibantu
            ['kode' => 'C2', 'nama_kriteria' => 'Jumlah Tenaga Kerja', 'tipe' => 'cost'], // Cost: Usaha super mikro prioritas
            ['kode' => 'C3', 'nama_kriteria' => 'Lama Usaha Berjalan (Tahun)', 'tipe' => 'benefit'], // Benefit: Sudah konsisten bertahan
            ['kode' => 'C4', 'nama_kriteria' => 'Kepemilikan Legalitas', 'tipe' => 'cost'], // Cost: Belum punya legalitas, prioritas diurus
            ['kode' => 'C5', 'nama_kriteria' => 'Tunggakan Pinjaman', 'tipe' => 'cost'], // Cost: Semakin kecil tunggakan, semakin aman diberi modal
        ];

        foreach ($kriterias as $k) {
            Kriteria::updateOrCreate(['kode' => $k['kode']], $k);
        }

        $allKriteria = Kriteria::all();
        $umkms = UMKM::where('status_verifikasi', 'terverifikasi')->get();

        foreach ($umkms as $umkm) {
            foreach ($allKriteria as $kriteria) {
                $nilai = 0;
                switch ($kriteria->kode) {
                    case 'C1': $nilai = rand(5, 100); break; // 5-100 Juta
                    case 'C2': $nilai = rand(1, 20); break; // 1-20 orang
                    case 'C3': $nilai = rand(1, 15); break; // 1-15 tahun
                    case 'C4': $nilai = rand(0, 100); break; // Skor legalitas 0-100
                    case 'C5': $nilai = rand(0, 50); break; // Tunggakan 0-50 Juta
                }

                DssPenilaian::updateOrCreate(
                    [
                        'umkm_id' => $umkm->id_umkm,
                        'kriteria_id' => $kriteria->id,
                        'periode' => 'Q1-2026'
                    ],
                    ['nilai' => $nilai]
                );
            }
        }
    }
}
