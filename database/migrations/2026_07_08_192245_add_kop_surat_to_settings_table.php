<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            [
                'key'   => 'kop_nama_instansi',
                'value' => 'PEMERINTAH KOTA BANDUNG',
                'group' => 'kop_surat',
                'type'  => 'text',
                'label' => 'Nama Instansi / Pemerintah',
            ],
            [
                'key'   => 'kop_nama_unit',
                'value' => 'KECAMATAN MANDALAJATI',
                'group' => 'kop_surat',
                'type'  => 'text',
                'label' => 'Nama Unit / Dinas / Kecamatan',
            ],
            [
                'key'   => 'kop_alamat',
                'value' => 'Jl. Sindanglaya No.50, Sindangjaya, Kec. Mandalajati, Kota Bandung, Jawa Barat 40195',
                'group' => 'kop_surat',
                'type'  => 'text',
                'label' => 'Alamat Lengkap',
            ],
            [
                'key'   => 'kop_telepon',
                'value' => '(022) 7815252',
                'group' => 'kop_surat',
                'type'  => 'text',
                'label' => 'Nomor Telepon',
            ],
            [
                'key'   => 'kop_email',
                'value' => 'mandalajati@bandung.go.id',
                'group' => 'kop_surat',
                'type'  => 'text',
                'label' => 'Email Resmi',
            ],
            [
                'key'   => 'kop_website',
                'value' => '',
                'group' => 'kop_surat',
                'type'  => 'text',
                'label' => 'Website (opsional)',
            ],
            [
                'key'   => 'kop_logo_path',
                'value' => '',
                'group' => 'kop_surat',
                'type'  => 'text',
                'label' => 'Path Logo Instansi',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insertOrIgnore(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'kop_nama_instansi',
            'kop_nama_unit',
            'kop_alamat',
            'kop_telepon',
            'kop_email',
            'kop_website',
            'kop_logo_path',
        ])->delete();
    }
};
