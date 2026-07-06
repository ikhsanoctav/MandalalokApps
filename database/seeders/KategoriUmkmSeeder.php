<?php

namespace Database\Seeders;

use App\Models\KategoriUMKM;
use Illuminate\Database\Seeder;

class KategoriUmkmSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama_kategori' => 'Mikro'],
            ['nama_kategori' => 'Kecil'],
            ['nama_kategori' => 'Menengah'],
        ];

        foreach ($kategoris as $kat) {
            KategoriUMKM::updateOrCreate(
                ['nama_kategori' => $kat['nama_kategori']]
            );
        }

        $this->command->info('✅ '.count($kategoris).' kategori (tipe UMKM) berhasil dibuat');
    }
}
