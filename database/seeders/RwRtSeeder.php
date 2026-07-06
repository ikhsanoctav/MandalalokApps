<?php

namespace Database\Seeders;

use App\Models\Kelurahan;
use App\Models\Rt;
use App\Models\Rw;
use Illuminate\Database\Seeder;

class RwRtSeeder extends Seeder
{
    public function run(): void
    {
        $kelurahans = Kelurahan::all();

        if ($kelurahans->isEmpty()) {
            $this->command->warn('⚠️  Kelurahan kosong. Jalankan KelurahansSeeder terlebih dahulu.');

            return;
        }

        $rwCount = 0;
        $rtCount = 0;

        foreach ($kelurahans as $kelurahan) {
            for ($rw = 1; $rw <= 6; $rw++) {
                $nomorRw = str_pad((string) $rw, 2, '0', STR_PAD_LEFT);

                $rwModel = Rw::updateOrCreate(
                    [
                        'kelurahan_id' => $kelurahan->id,
                        'nomor_rw' => $nomorRw,
                    ]
                );
                $rwCount++;

                for ($rt = 1; $rt <= 4; $rt++) {
                    $nomorRt = str_pad((string) $rt, 2, '0', STR_PAD_LEFT);

                    Rt::updateOrCreate(
                        [
                            'rw_id' => $rwModel->id,
                            'nomor_rt' => $nomorRt,
                        ]
                    );
                    $rtCount++;
                }
            }
        }

        $this->command->info("✅ RW: {$rwCount} records, RT: {$rtCount} records");
    }
}
