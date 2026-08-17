<?php

namespace Tests\Feature;

use App\Models\KategoriUMKM;
use App\Models\Pemilik;
use App\Models\SektorUmkm;
use App\Models\UMKM;
use App\Models\User;
use App\Models\VerifikasiLapangan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SuperadminVerifikasiLapanganDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_umkm_detail_displays_field_verification_supporting_documents(): void
    {
        $initialOutputBufferLevel = ob_get_level();

        try {
            Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
            Role::create(['name' => 'operator_lapangan', 'guard_name' => 'web']);

            $superAdmin = User::factory()->create();
            $superAdmin->assignRole('super_admin');

            $operator = User::factory()->create(['name' => 'Petugas Lapangan Test']);
            $operator->assignRole('operator_lapangan');

            $kategori = KategoriUMKM::create(['nama_kategori' => 'Mikro']);
            $sektor = SektorUmkm::create(['nama_sektor' => 'Kuliner']);

            $pemilik = Pemilik::create([
                'id_pemilik' => '123e4567-e89b-12d3-a456-426614174000',
                'nik' => '3270000000001234',
                'nama_lengkap' => 'Jane Doe',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'P',
                'no_hp' => '081234567890',
                'email' => 'jane@example.com',
                'alamat' => 'Jl. Kebangsaan No. 12',
                'kelurahan' => 'Pasir Impun',
                'rw' => '003',
                'rt' => '005',
                'kecamatan' => 'Mandalajati',
                'kota_kab' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '40181',
                'status_verifikasi_ktp' => 'terverifikasi',
            ]);

            $umkm = UMKM::create([
                'id_umkm' => '987f6543-e21b-32d1-b654-526614174111',
                'no_pendaftaran' => null,
                'nama_usaha' => 'Toko Jane',
                'id_pemilik' => $pemilik->id_pemilik,
                'id_kategori' => $kategori->id,
                'id_sektor' => $sektor->id,
                'status_usaha' => 'aktif',
                'alamat_usaha' => 'Jl. Kebangsaan No. 12',
                'jumlah_tenaga_kerja' => 2,
                'status_verifikasi' => 'menunggu_verifikasi',
                'tanggal_pendataan' => now(),
                'id_petugas' => $operator->id,
            ]);

            VerifikasiLapangan::create([
                'umkm_id' => $umkm->id_umkm,
                'petugas_id' => $operator->id,
                'tanggal_kunjungan' => now()->toDateString(),
                'status_kunjungan' => 'dikunjungi',
                'kondisi_usaha' => 'sesuai',
                'catatan_kunjungan' => 'Usaha aktif dan sesuai data lapangan.',
                'foto_kunjungan' => ['verifikasi_lapangan/foto-1.jpg'],
                'latitude_kunjungan' => -6.914744,
                'longitude_kunjungan' => 107.609810,
            ]);

            $response = $this->actingAs($superAdmin)->get(route('superadmin.umkm.show', $umkm->id_umkm));

            $response->assertStatus(200)
                ->assertSee('Dokumen Pendukung Verifikasi Lapangan')
                ->assertSee('Usaha aktif dan sesuai data lapangan.')
                ->assertSee('Petugas Lapangan Test')
                ->assertSee('Foto Survei Lapangan');

            $modalResponse = $this->actingAs($superAdmin)->get(route('superadmin.umkm.modal', $umkm->id_umkm));

            $modalResponse->assertStatus(200)
                ->assertJson([
                    'success' => true,
                ])
                ->assertSee('Dokumen Pendukung Verifikasi Lapangan')
                ->assertSee('Usaha aktif dan sesuai data lapangan.');
        } finally {
            while (ob_get_level() > $initialOutputBufferLevel) {
                ob_end_clean();
            }
        }
    }
}
