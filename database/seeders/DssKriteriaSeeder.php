<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DssKriteria;

class DssKriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // Kriteria Bantuan Modal
        DssKriteria::create([
            'kode_kriteria' => 'B1',
            'nama_kriteria' => 'Kelengkapan Legalitas (NIB & NPWP)',
            'tipe_dss' => 'bantuan',
            'jenis' => 'benefit',
            'bobot' => 30.00,
        ]);
        
        DssKriteria::create([
            'kode_kriteria' => 'B2',
            'nama_kriteria' => 'Lama Usaha Berdiri',
            'tipe_dss' => 'bantuan',
            'jenis' => 'benefit',
            'bobot' => 20.00,
        ]);
        
        DssKriteria::create([
            'kode_kriteria' => 'B3',
            'nama_kriteria' => 'Jumlah Tenaga Kerja Lokal',
            'tipe_dss' => 'bantuan',
            'jenis' => 'benefit',
            'bobot' => 30.00,
        ]);
        
        DssKriteria::create([
            'kode_kriteria' => 'B4',
            'nama_kriteria' => 'Riwayat Bantuan Sebelumnya',
            'tipe_dss' => 'bantuan',
            'jenis' => 'cost',
            'bobot' => 20.00,
        ]);

        // Kriteria Pelatihan UMKM
        DssKriteria::create([
            'kode_kriteria' => 'P1',
            'nama_kriteria' => 'Legalitas Usaha (Ketiadaan NIB)',
            'tipe_dss' => 'pelatihan',
            'jenis' => 'cost', // Semakin tidak lengkap, semakin butuh dilatih
            'bobot' => 40.00,
        ]);
        
        DssKriteria::create([
            'kode_kriteria' => 'P2',
            'nama_kriteria' => 'Lama Usaha Berdiri',
            'tipe_dss' => 'pelatihan',
            'jenis' => 'cost', // Semakin baru, semakin butuh didampingi
            'bobot' => 30.00,
        ]);
        
        DssKriteria::create([
            'kode_kriteria' => 'P3',
            'nama_kriteria' => 'Jumlah Tenaga Kerja',
            'tipe_dss' => 'pelatihan',
            'jenis' => 'cost', // Semakin sedikit/perorangan, prioritas naik kelas
            'bobot' => 15.00,
        ]);
        
        DssKriteria::create([
            'kode_kriteria' => 'P4',
            'nama_kriteria' => 'Kategori Bentuk Jualan',
            'tipe_dss' => 'pelatihan',
            'jenis' => 'cost', // Konvensional butuh dilatih digitalisasi
            'bobot' => 15.00,
        ]);
    }
}
