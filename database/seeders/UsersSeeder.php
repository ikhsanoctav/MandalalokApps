<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'nik' => '3270000000001',
                'no_hp' => '08123456789',
                'alamat' => 'Kantor Kecamatan Mandalajati',
                'jabatan' => 'Kepala Sistem',
                'kelurahan' => 'Karang Pamulang',
                'rw' => '001',
                'rt' => '001',
                'role' => 'super_admin',
            ],
            [
                'name' => 'Admin Kecamatan',
                'email' => 'kecamatan@mandalajati.com',
                'password' => Hash::make('password'),
                'nik' => '3270000000002',
                'no_hp' => '08123456780',
                'alamat' => 'Kantor Kecamatan Mandalajati',
                'jabatan' => 'Admin Kecamatan',
                'kelurahan' => 'Sindangjaya',
                'rw' => '002',
                'rt' => '002',
                'role' => 'admin_kecamatan',
            ],
            [
                'name' => 'Operator Lapangan',
                'email' => 'operator@mandalajati.com',
                'password' => Hash::make('password'),
                'nik' => '3270000000003',
                'no_hp' => '08123456781',
                'alamat' => 'Kantor Kecamatan Mandalajati',
                'jabatan' => 'Operator Lapangan',
                'kelurahan' => 'Jati Handap',
                'rw' => '003',
                'rt' => '003',
                'role' => 'operator_lapangan',
            ],
        ];

        foreach ($users as $data) {
            $role = $data['role'];
            unset($data['role']);

            $data['email_verified_at'] = now();

            $user = User::firstOrCreate(['email' => $data['email']], $data);
            $user->assignRole($role);
        }

        $this->command->info('✅ Users created: '.count($users).' users');
    }
}
