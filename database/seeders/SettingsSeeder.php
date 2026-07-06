<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'app_name',
                'value' => 'Mandalaloka',
                'group' => 'general',
                'type' => 'text',
                'label' => 'Nama Aplikasi',
            ],
            [
                'key' => 'registration_open',
                'value' => 'true',
                'group' => 'system',
                'type' => 'boolean',
                'label' => 'Status Pendaftaran UMKM',
            ],
            [
                'key' => 'pelaku_submission_active',
                'value' => 'false',
                'group' => 'system',
                'type' => 'boolean',
                'label' => 'Status Pengajuan Bantuan Pelaku',
            ],
            [
                'key' => 'max_financing_limit',
                'value' => '50000000',
                'group' => 'system',
                'type' => 'number',
                'label' => 'Batas Nominal Bantuan (IDR)',
            ],
            [
                'key' => 'support_phone',
                'value' => '08123456789',
                'group' => 'contact',
                'type' => 'text',
                'label' => 'No. Telepon Bantuan',
            ],
            [
                'key' => 'support_email',
                'value' => 'support@mandalaloka.go.id',
                'group' => 'contact',
                'type' => 'text',
                'label' => 'Email Bantuan Dinas',
            ],
            [
                'key' => 'program_bantuan_list',
                'value' => "Bantuan Modal Usaha Mikro (BPUM)\nSubsidi Bunga Pembiayaan Mandiri\nBantuan Hibah Peralatan Kerja\nBantuan Digitalisasi Produk UMKM\nBantuan Sembako Pelaku Usaha\nLainnya",
                'group' => 'system',
                'type' => 'textarea',
                'label' => 'Daftar Program Bantuan Aktif',
            ],
            [
                'key' => 'flyer_popup_active',
                'value' => 'false',
                'group' => 'system',
                'type' => 'boolean',
                'label' => 'Status Pop-up Flyer',
            ],
            [
                'key' => 'flyer_popup_image',
                'value' => '',
                'group' => 'system',
                'type' => 'text',
                'label' => 'Gambar Flyer Program',
            ],
            [
                'key' => 'flyer_popup_link',
                'value' => '',
                'group' => 'system',
                'type' => 'text',
                'label' => 'Link Navigasi Flyer',
            ],
            [
                'key' => 'flyer_popup_target',
                'value' => 'both',
                'group' => 'system',
                'type' => 'text',
                'label' => 'Target Tampilan Flyer',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
