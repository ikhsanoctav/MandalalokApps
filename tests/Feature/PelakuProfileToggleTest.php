<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pemilik;
use App\Models\Kelurahan;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelakuProfileToggleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure role exists in the database
        if (!Role::where('name', 'pelaku_umkm')->exists()) {
            Role::create(['name' => 'pelaku_umkm', 'guard_name' => 'web']);
        }

        // Seed basic lookup tables
        $this->seed(\Database\Seeders\KelurahansSeeder::class);
    }

    public function test_profile_displays_edit_mode_directly_when_no_profile_exists(): void
    {
        $user = User::factory()->create(['nik' => '3270000000001111']);
        $user->assignRole('pelaku_umkm');

        $response = $this->actingAs($user)->get(route('pelaku.profil.edit'));

        $response->assertStatus(200);
        // Should default to true because $pemilik is null
        $response->assertSee('isEditing: true', false);
    }

    public function test_profile_displays_view_mode_when_profile_exists(): void
    {
        $user = User::factory()->create(['nik' => '3270000000002222']);
        $user->assignRole('pelaku_umkm');

        $kelurahan = Kelurahan::first();

        $pemilik = Pemilik::create([
            'id_pemilik' => '223e4567-e89b-12d3-a456-426614174000',
            'nik' => '3270000000002222',
            'nama_lengkap' => 'John Doe',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1985-05-20',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567800',
            'alamat' => 'Jl. Merdeka No. 12',
            'kelurahan' => $kelurahan->nama_kelurahan,
            'rw' => '02',
            'rt' => '01',
            'kecamatan' => 'Mandalajati',
            'kota_kab' => 'Bandung',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '40181',
            'status_verifikasi_ktp' => 'terverifikasi',
        ]);

        $response = $this->actingAs($user)->get(route('pelaku.profil.edit'));

        $response->assertStatus(200);
        // Should default to false since the profile exists
        $response->assertSee('isEditing: false', false);
        
        // Verify we can see the read-only details
        $response->assertSee('John Doe');
        $response->assertSee('Akun Terverifikasi');
        $response->assertSee('Edit Profil');
    }
}
