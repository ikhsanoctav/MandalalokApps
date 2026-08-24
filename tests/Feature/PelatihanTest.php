<?php

namespace Tests\Feature;

use App\Models\Pelatihan;
use App\Models\PesertaPelatihan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PelatihanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'pelaku_umkm']);
        Role::firstOrCreate(['name' => 'admin_kecamatan']);
        Role::firstOrCreate(['name' => 'super_admin']);
    }

    private function createVerifiedPelakuUser(string $nik = '3270010101010001'): User
    {
        $user = User::factory()->create(['nik' => $nik]);
        $user->assignRole('pelaku_umkm');

        \App\Models\Pemilik::create([
            'id_pemilik' => (string) \Illuminate\Support\Str::uuid(),
            'nik' => $nik,
            'nik_hash' => hash('sha256', $nik),
            'nama_lengkap' => $user->name,
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
            'email' => $user->email,
            'alamat' => 'Jl. Ujungberung No. 1',
            'rt' => '001',
            'rw' => '001',
            'kelurahan' => 'Karang Pamulang',
            'kecamatan' => 'Mandalajati',
            'kota_kab' => 'Bandung',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '40195',
            'foto_ktp' => 'uploads/ktp/dummy.jpg',
            'status_verifikasi_ktp' => 'terverifikasi',
        ]);

        return $user;
    }

    public function test_pelaku_can_register_for_available_pelatihan(): void
    {
        $user = $this->createVerifiedPelakuUser('3270010101010001');

        $pelatihan = Pelatihan::create([
            'judul' => 'Pelatihan Digital Marketing',
            'deskripsi' => 'Deskripsi pelatihan',
            'tanggal_mulai' => now()->addDays(1),
            'tanggal_selesai' => now()->addDays(3),
            'lokasi' => 'Aula Kecamatan',
            'kuota' => 5,
            'status' => 'published',
        ]);

        $response = $this->actingAs($user)->post(route('pelaku.pelatihan.daftar', $pelatihan->id));

        $response->assertRedirect(route('pelaku.pelatihan.show', $pelatihan->id));
        $this->assertDatabaseHas('peserta_pelatihans', [
            'pelatihan_id' => $pelatihan->id,
            'user_id' => $user->id,
            'status_kehadiran' => 'terdaftar',
        ]);
    }

    public function test_registration_blocked_when_quota_is_full(): void
    {
        $user1 = $this->createVerifiedPelakuUser('3270010101010001');
        $user2 = $this->createVerifiedPelakuUser('3270010101010002');

        $pelatihan = Pelatihan::create([
            'judul' => 'Pelatihan Kuota Terbatas',
            'tanggal_mulai' => now()->addDays(1),
            'tanggal_selesai' => now()->addDays(2),
            'lokasi' => 'Ruang Rapat',
            'kuota' => 1,
            'status' => 'published',
        ]);

        // First user registers successfully
        $this->actingAs($user1)->post(route('pelaku.pelatihan.daftar', $pelatihan->id));
        $this->assertDatabaseHas('peserta_pelatihans', [
            'pelatihan_id' => $pelatihan->id,
            'user_id' => $user1->id,
        ]);

        // Second user attempt should fail due to full quota
        $response = $this->actingAs($user2)->post(route('pelaku.pelatihan.daftar', $pelatihan->id));
        $response->assertSessionHas('error', 'Mohon maaf, kuota pelatihan ini sudah penuh.');
        $this->assertDatabaseMissing('peserta_pelatihans', [
            'pelatihan_id' => $pelatihan->id,
            'user_id' => $user2->id,
        ]);
    }

    public function test_admin_can_destroy_pelatihan(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin_kecamatan');

        $pelatihan = Pelatihan::create([
            'judul' => 'Pelatihan Hapus',
            'tanggal_mulai' => now()->addDays(1),
            'tanggal_selesai' => now()->addDays(2),
            'lokasi' => 'Ruang Hapus',
            'kuota' => 10,
            'status' => 'published',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.pelatihan.destroy', $pelatihan->id));

        $response->assertRedirect(route('admin.pelatihan.index'));
        $this->assertDatabaseMissing('pelatihans', [
            'id' => $pelatihan->id,
        ]);
    }
}
