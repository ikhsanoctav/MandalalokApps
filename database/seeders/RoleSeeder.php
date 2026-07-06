<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Gunakan firstOrCreate agar jika role sudah ada, dia tidak error tapi dilewati saja.
        Role::firstOrCreate(['name' => 'super_admin']);
        Role::firstOrCreate(['name' => 'admin_kecamatan']);
        Role::firstOrCreate(['name' => 'operator_lapangan']);

    }
}
