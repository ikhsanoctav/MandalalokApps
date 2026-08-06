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

    public function test_pelaku_can_register_for_available_pelatihan(): void
    {
        $user = User::factory()->create();
        $user->assignRole('pelaku_umkm');

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
        $user1 = User::factory()->create();
        $user1->assignRole('pelaku_umkm');
        $user2 = User::factory()->create();
        $user2->assignRole('pelaku_umkm');

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
