<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UMKM;
use App\Models\Pemilik;
use App\Models\Kelurahan;
use App\Models\KategoriUMKM;
use App\Models\SektorUmkm;
use App\Models\Setting;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengajuanBantuanToggleTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $superAdmin;
    protected $pelakuUser;
    protected $pemilik;
    protected $umkm;

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

        // Seed basic lookup tables
        $this->seed(\Database\Seeders\KelurahansSeeder::class);
        $this->seed(\Database\Seeders\RwRtSeeder::class);
        $this->seed(\Database\Seeders\KategoriUmkmSeeder::class);
        $this->seed(\Database\Seeders\SektorUmkmSeeder::class);
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        // Create Users
        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole('super_admin');

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin_kecamatan');

        $this->pelakuUser = User::factory()->create(['nik' => '3270010101010001']);
        $this->pelakuUser->assignRole('pelaku_umkm');

        // Create Pemilik and UMKM
        $kelurahan = Kelurahan::first();
        $kategori = KategoriUMKM::first();
        $sektor = SektorUmkm::first();

        $this->pemilik = Pemilik::create([
            'id_pemilik' => '123e4567-e89b-12d3-a456-426614174001',
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
            'kelurahan' => $kelurahan->nama_kelurahan,
            'kecamatan' => 'Mandalajati',
            'kota_kab' => 'Bandung',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '40181',
            'status_verifikasi_ktp' => 'terverifikasi',
        ]);

        $this->umkm = UMKM::create([
            'id_umkm' => '987f6543-e21b-32d1-b654-526614174001',
            'no_pendaftaran' => 'REG-111111',
            'nama_usaha' => 'Toko Ahmad',
            'id_pemilik' => $this->pemilik->id_pemilik,
            'id_kategori' => $kategori->id,
            'id_sektor' => $sektor->id,
            'status_usaha' => 'aktif',
            'alamat_usaha' => 'Jl. Karangpawitan No. 12',
            'jumlah_tenaga_kerja' => 3,
            'status_verifikasi' => 'terverifikasi',
            'tanggal_pendataan' => now(),
        ]);
    }

    public function test_superadmin_and_admin_can_store_pengajuan(): void
    {
        // Test Super Admin
        $payload = [
            'umkm_id' => $this->umkm->id_umkm,
            'jenis_pengajuan' => 'bantuan',
            'nama_program' => 'Bantuan Modal Usaha Mikro (BPUM)',
            'tanggal_pengajuan' => now()->format('Y-m-d'),
            'keterangan' => 'Bantuan alat produksi dari Super Admin',
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('superadmin.pengajuan.store'), $payload);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('pengajuans', [
            'umkm_id' => $this->umkm->id_umkm,
            'jenis_pengajuan' => 'bantuan',
            'nama_program' => 'Bantuan Modal Usaha Mikro (BPUM)',
            'keterangan' => 'Bantuan alat produksi dari Super Admin',
        ]);

        // Test Admin Kecamatan
        $payload['keterangan'] = 'Bantuan alat produksi dari Admin';
        $payload['nama_program'] = 'Bantuan Digitalisasi Produk UMKM';
        $response = $this->actingAs($this->admin)->post(route('admin.pengajuan.store'), $payload);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('pengajuans', [
            'umkm_id' => $this->umkm->id_umkm,
            'jenis_pengajuan' => 'bantuan',
            'nama_program' => 'Bantuan Digitalisasi Produk UMKM',
            'keterangan' => 'Bantuan alat produksi dari Admin',
        ]);
    }

    public function test_pelaku_cannot_submit_when_setting_is_disabled(): void
    {
        // Explicitly set to false (disabled)
        Setting::set('pelaku_submission_active', 'false');

        // Verify GET returns redirect/warning
        $response = $this->actingAs($this->pelakuUser)->get(route('pelaku.pengajuan.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('pelaku.dashboard'));

        // Verify POST returns 403
        $payload = [
            'umkm_id' => $this->umkm->id_umkm,
            'jenis_pengajuan' => 'bantuan',
            'nama_program' => 'Bantuan Modal Usaha Mikro (BPUM)',
            'tanggal_pengajuan' => now()->format('Y-m-d'),
            'keterangan' => 'Permohonan bantuan mandiri',
        ];
        $response = $this->actingAs($this->pelakuUser)->post(route('pelaku.pengajuan.store'), $payload);
        $response->assertStatus(403);
    }

    public function test_pelaku_can_submit_when_setting_is_enabled(): void
    {
        // Explicitly set to true (enabled)
        Setting::set('pelaku_submission_active', 'true');

        // Verify GET is accessible (200 OK)
        $response = $this->actingAs($this->pelakuUser)->get(route('pelaku.pengajuan.index'));
        $response->assertStatus(200);

        // Verify POST succeeds for their own UMKM
        $payload = [
            'umkm_id' => $this->umkm->id_umkm,
            'jenis_pengajuan' => 'bantuan',
            'nama_program' => 'Bantuan Hibah Peralatan Kerja',
            'tanggal_pengajuan' => now()->format('Y-m-d'),
            'keterangan' => 'Permohonan bantuan mandiri pelaku',
        ];
        $response = $this->actingAs($this->pelakuUser)->post(route('pelaku.pengajuan.store'), $payload);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('pengajuans', [
            'umkm_id' => $this->umkm->id_umkm,
            'jenis_pengajuan' => 'bantuan',
            'nama_program' => 'Bantuan Hibah Peralatan Kerja',
            'keterangan' => 'Permohonan bantuan mandiri pelaku',
        ]);
    }

    public function test_pelaku_cannot_submit_for_other_users_umkm(): void
    {
        Setting::set('pelaku_submission_active', 'true');

        // Create another user's UMKM
        $otherPemilik = Pemilik::create([
            'id_pemilik' => '123e4567-e89b-12d3-a456-426614174099',
            'nik' => '3270010101010099',
            'nama_lengkap' => 'Other User',
            'kelurahan' => Kelurahan::first()->nama_kelurahan,
            'kecamatan' => 'Mandalajati',
            'kota_kab' => 'Bandung',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '40181',
            'status_verifikasi_ktp' => 'terverifikasi',
        ]);
        $otherUmkm = UMKM::create([
            'id_umkm' => '987f6543-e21b-32d1-b654-526614174099',
            'no_pendaftaran' => 'REG-999999',
            'nama_usaha' => 'Toko Orang Lain',
            'id_pemilik' => $otherPemilik->id_pemilik,
            'id_kategori' => KategoriUMKM::first()->id,
            'id_sektor' => SektorUmkm::first()->id,
            'status_verifikasi' => 'terverifikasi',
            'tanggal_pendataan' => now(),
        ]);

        $payload = [
            'umkm_id' => $otherUmkm->id_umkm,
            'jenis_pengajuan' => 'bantuan',
            'tanggal_pengajuan' => now()->format('Y-m-d'),
            'keterangan' => 'Mencoba submit untuk UMKM orang lain',
        ];

        // Should fail with 403 Forbidden
        $response = $this->actingAs($this->pelakuUser)->post(route('pelaku.pengajuan.store'), $payload);
        $response->assertStatus(403);
    }
}
