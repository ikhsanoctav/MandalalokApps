<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UMKM;
use App\Models\Pemilik;
use App\Models\Kelurahan;
use App\Models\KategoriUMKM;
use App\Models\SektorUmkm;
use App\Models\Setting;
use App\Models\Pengajuan;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserAcceptanceTest extends TestCase
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

        $this->seed(\Database\Seeders\KelurahansSeeder::class);
        $this->seed(\Database\Seeders\RwRtSeeder::class);
        $this->seed(\Database\Seeders\KategoriUmkmSeeder::class);
        $this->seed(\Database\Seeders\SektorUmkmSeeder::class);
        $this->seed(\Database\Seeders\SettingsSeeder::class);
    }

    public function test_dynamic_bantuan_submission_uat_flow(): void
    {
        Storage::fake('public');

        // 1. Create Super Admin & Pelaku Users
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        $pelakuUser = User::factory()->create([
            'nik' => '3270000000003001',
        ]);
        $pelakuUser->assignRole('pelaku_umkm');

        $kelurahan = Kelurahan::first();
        $kategori = KategoriUMKM::first();
        $sektor = SektorUmkm::first();

        // 2. Create Pemilik linked to Pelaku and verified UMKM
        $pemilik = Pemilik::create([
            'id_pemilik' => '123e4567-e89b-12d3-a456-426614174001',
            'nik' => '3270000000003001',
            'nik_hash' => hash('sha256', '3270000000003001'),
            'nama_lengkap' => 'Jane Doe',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'P',
            'no_hp' => '081234567890',
            'email' => $pelakuUser->email,
            'alamat' => 'Jl. Kebangsaan No. 12',
            'kelurahan' => $kelurahan->nama_kelurahan,
            'rw' => '03',
            'rt' => '05',
            'kecamatan' => 'Mandalajati',
            'kota_kab' => 'Bandung',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '40181',
            'status_verifikasi_ktp' => 'terverifikasi',
            'foto_ktp' => 'ktp_dummy.jpg',
        ]);

        $umkm = UMKM::create([
            'id_umkm' => '987f6543-e21b-32d1-b654-526614174112',
            'no_pendaftaran' => 'REG-123457',
            'nama_usaha' => 'Toko UAT',
            'id_pemilik' => $pemilik->id_pemilik,
            'id_kategori' => $kategori->id,
            'id_sektor' => $sektor->id,
            'status_usaha' => 'aktif',
            'alamat_usaha' => 'Jl. Kebangsaan No. 12',
            'jumlah_tenaga_kerja' => 2,
            'status_verifikasi' => 'terverifikasi',
            'tanggal_pendataan' => now(),
        ]);

        // 3. STEP 1 (Super Admin Configures Settings): Update help program list
        $responseSettings = $this->actingAs($superAdmin)->post(route('superadmin.settings.update'), [
            'program_bantuan_list' => "Bantuan A\nBantuan UAT Peralatan Mandiri\nBantuan C",
            'pelaku_submission_active' => 'true',
        ]);
        $responseSettings->assertRedirect(route('superadmin.settings'));

        $this->assertEquals("Bantuan A\nBantuan UAT Peralatan Mandiri\nBantuan C", Setting::get('program_bantuan_list'));
        $this->assertEquals('true', Setting::get('pelaku_submission_active'));

        // 4. STEP 2 (Pelaku Submits Request): Post new submission with file attachments
        $ktpMockFile = UploadedFile::fake()->create('ktp.pdf', 100);
        $kkMockFile = UploadedFile::fake()->create('kk.jpg', 200);

        $responseSubmit = $this->actingAs($pelakuUser)->postJson(route('pelaku.pengajuan.store'), [
            'umkm_id' => $umkm->id_umkm,
            'jenis_pengajuan' => 'bantuan',
            'nama_program' => 'Bantuan UAT Peralatan Mandiri',
            'keterangan' => 'Mengajukan bantuan peralatan UAT',
            'tanggal_pengajuan' => '2026-06-11',
            'berkas_ktp' => $ktpMockFile,
            'berkas_kk' => $kkMockFile,
        ]);

        $responseSubmit->assertStatus(200)
            ->assertJson(['success' => true]);

        // Verify database state
        $this->assertDatabaseHas('pengajuans', [
            'umkm_id' => $umkm->id_umkm,
            'jenis_pengajuan' => 'bantuan',
            'nama_program' => 'Bantuan UAT Peralatan Mandiri',
            'status' => 'menunggu',
        ]);

        $pengajuan = Pengajuan::where('umkm_id', $umkm->id_umkm)->first();
        $this->assertNotNull($pengajuan);
        $this->assertCount(2, $pengajuan->berkas);

        // Verify file storage
        $ktpPath = $pengajuan->berkas[0]['file_path'];
        $kkPath = $pengajuan->berkas[1]['file_path'];
        Storage::disk('public')->assertExists($ktpPath);
        Storage::disk('public')->assertExists($kkPath);

        // 5. STEP 3 (Super Admin Verifies Submission): Check details modal HTML contains expected program name and icon download/view controls
        $responseDetail = $this->actingAs($superAdmin)->get(route('superadmin.pengajuan.modal', $pengajuan->id_pengajuan));
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee('Bantuan UAT Peralatan Mandiri');
        
        // Assert that the file action buttons are present via their title attributes
        $responseDetail->assertSee('title="Buka Berkas"', false);
        $responseDetail->assertSee('title="Unduh Berkas"', false);
    }
}
