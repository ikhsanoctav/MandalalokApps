<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            KelurahansSeeder::class,
            UsersSeeder::class,
            RwRtSeeder::class,
            KategoriUmkmSeeder::class,
            SektorUmkmSeeder::class,
            PemiliksSeeder::class,
            UmkmSeeder::class,
            // UmkmFakerSeeder::class, // Dinonaktifkan agar database terisi data riil dari UmkmSeeder saja
            PengajuanSeeder::class,
            SettingsSeeder::class,
        ]);

        $this->command->info('🎉 All seeders completed successfully!');
    }
}
