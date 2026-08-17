<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UMKM;
use App\Models\Pemilik;
use App\Models\Kelurahan;
use App\Models\KategoriUMKM;
use App\Models\SektorUmkm;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UmkmDropdownTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure roles exist in the database for the test
        $roles = ['super_admin', 'admin_kecamatan', 'operator_lapangan', 'pelaku_umkm'];
        foreach ($roles as $roleName) {
            if (!Role::where('name', $roleName)->exists()) {
                Role::create(['name' => $roleName, 'guard_name' => 'web']);
            }
        }

        // Seed basic lookup tables and standard data
        $this->seed(\Database\Seeders\KelurahansSeeder::class);
        $this->seed(\Database\Seeders\RwRtSeeder::class);
        $this->seed(\Database\Seeders\KategoriUmkmSeeder::class);
        $this->seed(\Database\Seeders\SektorUmkmSeeder::class);
    }

    public function test_dropdown_rt_rw_padding_logic_is_rendered_correctly(): void
    {
        // 1. Create users
        $admin = User::factory()->create();
        $admin->assignRole('admin_kecamatan');

        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        $operator = User::factory()->create();
        $operator->assignRole('operator_lapangan');

        // 2. Create sample Pemilik and UMKM
        $kelurahan = Kelurahan::first();
        $this->assertNotNull($kelurahan, 'Kelurahan must exist after seeding.');

        $kategori = KategoriUMKM::first();
        $sektor = SektorUmkm::first();

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
            'kelurahan' => $kelurahan->nama_kelurahan,
            'rw' => '003', // 3-digit DB format
            'rt' => '005', // 3-digit DB format
            'kecamatan' => 'Mandalajati',
            'kota_kab' => 'Bandung',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '40181',
            'status_verifikasi_ktp' => 'terverifikasi',
        ]);

        $umkm = UMKM::create([
            'id_umkm' => '987f6543-e21b-32d1-b654-526614174111',
            'no_pendaftaran' => 'REG-123456',
            'nama_usaha' => 'Toko Jane',
            'id_pemilik' => $pemilik->id_pemilik,
            'id_kategori' => $kategori->id,
            'id_sektor' => $sektor->id,
            'status_usaha' => 'aktif',
            'alamat_usaha' => 'Jl. Kebangsaan No. 12',
            'foto_utama' => 'umkm/foto_utama/existing.jpg',
            'jumlah_tenaga_kerja' => 2,
            'status_verifikasi' => 'menunggu_verifikasi',
            'tanggal_pendataan' => now(),
            'id_petugas' => $operator->id,
        ]);

        // 3. Test Admin edit route
        $this->withoutExceptionHandling();
        $responseAdmin = $this->actingAs($admin)->get(route('admin.umkm.edit', $umkm->id_umkm));
        $responseAdmin->assertStatus(200);
        
        // Assert that the page normalizes stored RW/RT values before binding them to dropdown options.
        $responseAdmin->assertSee('initialRw:', false);
        $responseAdmin->assertSee('initialRt:', false);
        $responseAdmin->assertSee('this.selectedKel = this.initialKel', false);
        $responseAdmin->assertSee('this.selectedRw = this.normalizeWilayah(this.initialRw)', false);
        $responseAdmin->assertSee('this.selectedRt = this.normalizeWilayah(this.initialRt)', false);
        $responseAdmin->assertSee('value="1990-01-01"', false);
        $responseAdmin->assertDontSee('capture="environment" required', false);

        $pemilik->refresh();
        $this->assertEquals($kelurahan->id, $pemilik->id_kelurahan);
        $this->assertEquals('03', $pemilik->rw);
        $this->assertEquals('05', $pemilik->rt);

        // 4. Test Petugas edit route
        $responsePetugas = $this->actingAs($operator)->get(route('operator.umkm.edit', $umkm->id_umkm));
        $responsePetugas->assertStatus(200);
        $responsePetugas->assertSee('this.selectedKel = this.initialKel', false);
        $responsePetugas->assertSee('this.selectedRw = this.normalizeWilayah(this.initialRw)', false);
        $responsePetugas->assertSee('this.selectedRt = this.normalizeWilayah(this.initialRt)', false);
        $responsePetugas->assertSee('value="1990-01-01"', false);
        $responsePetugas->assertDontSee('capture="environment" required', false);

        // 5. Test Superadmin edit route
        $responseSuperAdmin = $this->actingAs($superAdmin)->get(route('superadmin.umkm.edit', $umkm->id_umkm));
        $responseSuperAdmin->assertStatus(200);
        $responseSuperAdmin->assertSee('this.selectedKel = this.initialKel', false);
        $responseSuperAdmin->assertSee('this.selectedRw = this.normalizeWilayah(this.initialRw)', false);
        $responseSuperAdmin->assertSee('this.selectedRt = this.normalizeWilayah(this.initialRt)', false);
        $responseSuperAdmin->assertSee('value="1990-01-01"', false);
        $responseSuperAdmin->assertDontSee('capture="environment" required', false);
    }
}
