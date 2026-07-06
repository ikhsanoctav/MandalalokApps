<?php

namespace Database\Seeders;

use App\Models\Kelurahan;
use Illuminate\Database\Seeder;

class KelurahansSeeder extends Seeder
{
    public function run(): void
    {
        $kelurahans = [
            ['kode_kelurahan' => 'KEL-001', 'nama_kelurahan' => 'Karang Pamulang'],
            ['kode_kelurahan' => 'KEL-002', 'nama_kelurahan' => 'Sindangjaya'],
            ['kode_kelurahan' => 'KEL-003', 'nama_kelurahan' => 'Cikadut'],
            ['kode_kelurahan' => 'KEL-004', 'nama_kelurahan' => 'Pasir Impun'],
            ['kode_kelurahan' => 'KEL-005', 'nama_kelurahan' => 'Jati Handap'],
        ];

        foreach ($kelurahans as $kel) {
            Kelurahan::firstOrCreate(
                ['kode_kelurahan' => $kel['kode_kelurahan']],
                ['nama_kelurahan' => $kel['nama_kelurahan']]
            );
        }

        $this->command->info('✅ Kelurahan created: '.count($kelurahans).' records');
    }
}
