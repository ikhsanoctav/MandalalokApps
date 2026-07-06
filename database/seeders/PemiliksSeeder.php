<?php

namespace Database\Seeders;

use App\Models\Pemilik;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PemiliksSeeder extends Seeder
{
    public function run(): void
    {
        $pemiliks = [
            [
                'nik' => '3270010101010001',
                'nama_lengkap' => 'Ahmad Saepudin',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1985-05-15',
                'jenis_kelamin' => 'L',
                'no_hp' => '08123456701',
                'email' => 'ahmad@example.com',
                'alamat' => 'Jl. Karangpawitan No. 12',
                'rt' => '001',
                'rw' => '002',
                'kelurahan' => 'Karang Pamulang',
                'kecamatan' => 'Mandalajati',
                'kota_kab' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '40181',
                'status_verifikasi_ktp' => 'terverifikasi',
            ],
            [
                'nik' => '3270010101010002',
                'nama_lengkap' => 'Salsabila Putri',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1990-08-20',
                'jenis_kelamin' => 'P',
                'no_hp' => '08123456702',
                'email' => 'salsabila@example.com',
                'alamat' => 'Jl. Sindangjaya No. 45',
                'rt' => '003',
                'rw' => '004',
                'kelurahan' => 'Sindangjaya',
                'kecamatan' => 'Mandalajati',
                'kota_kab' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '40182',
                'status_verifikasi_ktp' => 'terverifikasi',
            ],
            [
                'nik' => '3270010101010003',
                'nama_lengkap' => 'Dadang Hermawan',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1988-03-10',
                'jenis_kelamin' => 'L',
                'no_hp' => '08123456703',
                'email' => 'dadang@example.com',
                'alamat' => 'Jl. Cigadung Raya No. 78',
                'rt' => '005',
                'rw' => '006',
                'kelurahan' => 'Pasir Impun',
                'kecamatan' => 'Mandalajati',
                'kota_kab' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '40183',
                'status_verifikasi_ktp' => 'terverifikasi',
            ],
            [
                'nik' => '3270010101010004',
                'nama_lengkap' => 'Euis Nurjanah',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1995-12-01',
                'jenis_kelamin' => 'P',
                'no_hp' => '08123456704',
                'email' => 'euis@example.com',
                'alamat' => 'Jl. Padasuk No. 23',
                'rt' => '007',
                'rw' => '008',
                'kelurahan' => 'Cikadut',
                'kecamatan' => 'Mandalajati',
                'kota_kab' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '40184',
                'status_verifikasi_ktp' => 'terverifikasi',
            ],
            [
                'nik' => '3270010101010005',
                'nama_lengkap' => 'Rudi Hartono',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1982-07-25',
                'jenis_kelamin' => 'L',
                'no_hp' => '08123456705',
                'email' => 'rudi@example.com',
                'alamat' => 'Jl. Sekejati No. 56',
                'rt' => '009',
                'rw' => '010',
                'kelurahan' => 'Jati Handap',
                'kecamatan' => 'Mandalajati',
                'kota_kab' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '40185',
                'status_verifikasi_ktp' => 'pending',
            ],
        ];

        foreach ($pemiliks as $data) {
            Pemilik::firstOrCreate(
                ['nik_hash' => hash('sha256', $data['nik'])],
                $data
            );

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['nama_lengkap'],
                    'nik' => $data['nik'],
                    'kelurahan' => $data['kelurahan'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            
            if (!$user->hasRole('pelaku_umkm')) {
                $user->assignRole('pelaku_umkm');
            }
        }

        $this->command->info('✅ Pemiliks created: '.count($pemiliks).' records');
    }
}
