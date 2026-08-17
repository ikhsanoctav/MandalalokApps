<?php

namespace Database\Seeders;

use App\Models\KategoriUMKM;
use App\Models\Pemilik;
use App\Models\SektorUmkm;
use App\Models\UMKM;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class UmkmFakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Ambil data referensi yang sudah ada
        $kategoriList = KategoriUMKM::pluck('id')->toArray();
        $sektorList = SektorUmkm::pluck('id')->toArray();
        $pemilikList = Pemilik::pluck('id_pemilik')->toArray();
        
        // Coba cari petugas (operator lapangan), kalau tidak ada ambil sembarang user
        $petugasList = User::whereHas('roles', function($q) {
            $q->where('name', 'operator_lapangan');
        })->pluck('id')->toArray();
        
        if (empty($petugasList)) {
            $petugasList = User::pluck('id')->toArray();
        }

        if (empty($kategoriList) || empty($sektorList) || empty($pemilikList)) {
            $this->command->error('Pastikan data Pemilik, Kategori, dan Sektor minimal ada 1 di database!');
            return;
        }

        $this->command->info('Mulai membuat 50 data dummy UMKM dari tahun 2025 s.d Sekarang...');

        for ($i = 0; $i < 50; $i++) {
            $tkLaki = $faker->numberBetween(1, 10);
            $tkPerempuan = $faker->numberBetween(1, 10);
            
            // Generate tanggal dari awal tahun kemarin (2025) sampai sekarang
            $start = strtotime('2025-01-01');
            $end = time();
            $randomTime = mt_rand($start, $end);
            $tanggalPendataan = date('Y-m-d', $randomTime);
            
            // Random status (buat dominan terverifikasi)
            $statusVerifikasi = $faker->randomElement(['draft', 'menunggu_verifikasi', 'terverifikasi', 'terverifikasi', 'terverifikasi', 'ditolak']);
            
            // Tanggal verifikasi (jika sudah terverifikasi atau ditolak)
            $tanggalVerifikasi = in_array($statusVerifikasi, ['terverifikasi', 'ditolak']) 
                ? date('Y-m-d H:i:s', $randomTime + mt_rand(86400, 604800)) // 1-7 hari setelah pendataan
                : null;
                
            $catatanPenolakan = ($statusVerifikasi === 'ditolak') ? 'Data kurang lengkap.' : null;

            UMKM::create([
                'nama_usaha' => $faker->company,
                'id_pemilik' => $faker->randomElement($pemilikList),
                'id_kategori' => $faker->randomElement($kategoriList),
                'id_sektor' => $faker->randomElement($sektorList),
                'status_usaha' => $faker->randomElement(['aktif', 'aktif', 'aktif', 'non_aktif']),
                'tahun_berdiri' => $faker->numberBetween(2010, 2025),
                'no_izin_usaha' => $faker->numerify('NIB-##########'),
                'jenis_izin' => $faker->randomElement(['NIB', 'SIUP', 'SKU']),
                'npwp_usaha' => $faker->numerify('##.###.###.#-###.###'),
                'telp_usaha' => $faker->phoneNumber,
                'email_usaha' => $faker->unique()->companyEmail,
                'website' => 'www.' . $faker->domainName,
                'alamat_usaha' => $faker->address,
                'deskripsi' => $faker->paragraph,
                'jumlah_tenaga_kerja' => $tkLaki + $tkPerempuan,
                'tenaga_kerja_laki' => $tkLaki,
                'tenaga_kerja_perempuan' => $tkPerempuan,
                'id_petugas' => $faker->randomElement($petugasList),
                'tanggal_pendataan' => $tanggalPendataan,
                'status_verifikasi' => $statusVerifikasi,
                'tanggal_verifikasi' => $tanggalVerifikasi,
                'catatan_penolakan' => $catatanPenolakan,
                'created_at' => date('Y-m-d H:i:s', $randomTime),
                'updated_at' => date('Y-m-d H:i:s', $randomTime),
            ]);
        }
        
        $this->command->info('Selesai membuat 50 data dummy UMKM!');
    }
}
