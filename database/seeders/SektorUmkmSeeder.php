<?php

namespace Database\Seeders;

use App\Models\SektorUmkm;
use Illuminate\Database\Seeder;

class SektorUmkmSeeder extends Seeder
{
    public function run(): void
    {
        $sektors = [
            ['nama_sektor' => 'Kuliner', 'deskripsi' => 'Makanan dan minuman'],
            ['nama_sektor' => 'Fashion', 'deskripsi' => 'Pakaian dan aksesoris'],
            ['nama_sektor' => 'Kerajinan', 'deskripsi' => 'Kerajinan tangan'],
            ['nama_sektor' => 'Jasa', 'deskripsi' => 'Pelayanan jasa'],
            ['nama_sektor' => 'Pertanian', 'deskripsi' => 'Pertanian dan perkebunan'],
            ['nama_sektor' => 'Peternakan', 'deskripsi' => 'Peternakan'],
            ['nama_sektor' => 'Teknologi', 'deskripsi' => 'Teknologi informasi'],
            ['nama_sektor' => 'Perdagangan', 'deskripsi' => 'Perdagangan barang'],
            ['nama_sektor' => 'Otomotif', 'deskripsi' => 'Bengkel dan sparepart'],
            ['nama_sektor' => 'Property', 'deskripsi' => 'Properti dan konstruksi'],
        ];

        foreach ($sektors as $sek) {
            SektorUmkm::updateOrCreate(
                ['nama_sektor' => $sek['nama_sektor']],
                ['deskripsi' => $sek['deskripsi']]
            );
        }

        $this->command->info('✅ '.count($sektors).' sektor (bidang usaha) berhasil dibuat');
    }
}
