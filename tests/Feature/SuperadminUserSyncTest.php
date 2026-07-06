<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pemilik;
use App\Models\Kelurahan;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperadminUserSyncTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure roles exist
        foreach (['super_admin', 'pelaku_umkm'] as $roleName) {
            if (!Role::where('name', $roleName)->exists()) {
                Role::create(['name' => $roleName, 'guard_name' => 'web']);
            }
        }

        $this->seed(\Database\Seeders\KelurahansSeeder::class);
    }

    public function test_superadmin_user_edit_page_falls_back_to_pemilik_details(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('super_admin');

        $user = User::factory()->create([
            'name' => 'Jajang Nurjaman',
            'nik' => '3283301091234123',
            'no_hp' => null, // empty in user table
            'alamat' => null, // empty in user table
        ]);
        $user->assignRole('pelaku_umkm');

        $kelurahan = Kelurahan::first();

        // Create Pemilik record representing profile
        $pemilik = Pemilik::create([
            'id_pemilik' => '323e4567-e89b-12d3-a456-426614174000',
            'nik' => '3283301091234123',
            'nama_lengkap' => 'Jajang Nurjaman',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1995-01-01',
            'jenis_kelamin' => 'L',
            'no_hp' => '081395079762',
            'alamat' => 'Jl. Sukaasih No. 58',
            'kelurahan' => $kelurahan->nama_kelurahan,
            'rw' => '05',
            'rt' => '01',
            'kecamatan' => 'Mandalajati',
            'kota_kab' => 'Bandung',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '40285',
            'status_verifikasi_ktp' => 'terverifikasi',
        ]);

        $response = $this->actingAs($superadmin)->get(route('superadmin.users.edit', $user->id));

        $response->assertStatus(200);

        // NIK and names should be present
        $response->assertSee('Jajang Nurjaman');
        $response->assertSee('3283301091234123');

        // Phone and address from Pemilik should be populated in the user details passed to the edit page
        $response->assertSee('081395079762');
        $response->assertSee('Jl. Sukaasih No. 58');
    }

    public function test_superadmin_user_update_syncs_to_pemilik_record(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('super_admin');

        $user = User::factory()->create([
            'name' => 'Original Name',
            'nik' => '3283301091234123',
        ]);
        $user->assignRole('pelaku_umkm');

        $kelurahan = Kelurahan::first();

        $pemilik = Pemilik::create([
            'id_pemilik' => '323e4567-e89b-12d3-a456-426614174000',
            'nik' => '3283301091234123',
            'nama_lengkap' => 'Original Name',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1995-01-01',
            'jenis_kelamin' => 'L',
            'no_hp' => '081111111111',
            'alamat' => 'Alamat Asli',
            'kelurahan' => $kelurahan->nama_kelurahan,
            'rw' => '01',
            'rt' => '01',
            'kecamatan' => 'Mandalajati',
            'kota_kab' => 'Bandung',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '40285',
            'status_verifikasi_ktp' => 'terverifikasi',
        ]);

        $response = $this->actingAs($superadmin)->put(route('superadmin.users.update', $user->id), [
            'name' => 'Updated Name',
            'email' => $user->email,
            'role' => 'pelaku_umkm',
            'nik' => '3283301091234123',
            'no_hp' => '082222222222',
            'alamat' => 'Alamat Baru',
            'id_kelurahan' => $kelurahan->id,
            'rw' => '05',
        ]);

        $response->assertRedirect(route('superadmin.users'));

        // Refresh models
        $user->refresh();
        $pemilik->refresh();

        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('082222222222', $user->no_hp);
        $this->assertEquals('Alamat Baru', $user->alamat);
        $this->assertEquals($kelurahan->id, $user->id_kelurahan);
        $this->assertEquals($kelurahan->nama_kelurahan, $user->kelurahan);
        $this->assertEquals('05', $user->rw);

        // Check if Pemilik record updated/synced correctly
        $this->assertEquals('Updated Name', $pemilik->nama_lengkap);
        $this->assertEquals('082222222222', $pemilik->no_hp);
        $this->assertEquals('Alamat Baru', $pemilik->alamat);
        $this->assertEquals($kelurahan->nama_kelurahan, $pemilik->kelurahan);
        $this->assertEquals('05', $pemilik->rw);
    }
}
