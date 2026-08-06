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
                'key' => 'kategori_mikro_max_omset',
                'value' => '10000000',
                'group' => 'system',
                'type' => 'number',
                'label' => 'Batas Omset Maksimal Usaha Mikro (Bulanan)',
            ],
            [
                'key' => 'kategori_kecil_max_omset',
                'value' => '50000000',
                'group' => 'system',
                'type' => 'number',
                'label' => 'Batas Omset Maksimal Usaha Kecil (Bulanan)',
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
            [
                'key' => 'kop_nama_instansi',
                'value' => 'Pemerintah Kabupaten/Kota',
                'group' => 'kop_surat',
                'type' => 'text',
                'label' => 'Nama Instansi',
            ],
            [
                'key' => 'kop_nama_unit',
                'value' => 'Dinas Koperasi dan UKM',
                'group' => 'kop_surat',
                'type' => 'text',
                'label' => 'Nama Unit/Dinas',
            ],
            [
                'key' => 'kop_alamat',
                'value' => 'Jl. Contoh Alamat No. 123, Kota Contoh',
                'group' => 'kop_surat',
                'type' => 'text',
                'label' => 'Alamat Instansi',
            ],
            [
                'key' => 'kop_telepon',
                'value' => '(022) 123456',
                'group' => 'kop_surat',
                'type' => 'text',
                'label' => 'Telepon Instansi',
            ],
            [
                'key' => 'kop_email',
                'value' => 'info@contoh.go.id',
                'group' => 'kop_surat',
                'type' => 'text',
                'label' => 'Email Instansi',
            ],
            [
                'key' => 'kop_website',
                'value' => 'www.contoh.go.id',
                'group' => 'kop_surat',
                'type' => 'text',
                'label' => 'Website Instansi',
            ],
            [
                'key' => 'kop_logo_path',
                'value' => '',
                'group' => 'kop_surat',
                'type' => 'text',
                'label' => 'Logo Instansi (Path)',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
