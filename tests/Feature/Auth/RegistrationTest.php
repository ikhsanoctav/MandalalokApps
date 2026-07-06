<?php

namespace Tests\Feature\Auth;

use App\Models\Kelurahan;
use App\Models\Rt;
use App\Models\Rw;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        \Spatie\Permission\Models\Role::create(['name' => 'pelaku_umkm']);
        $kelurahan = Kelurahan::create([
            'kode_kelurahan' => '3273010001',
            'nama_kelurahan' => 'Pasir Impun',
            'kecamatan' => 'Mandalajati',
            'kota_kab' => 'Bandung',
            'provinsi' => 'Jawa Barat',
        ]);
        $rw = Rw::create([
            'kelurahan_id' => $kelurahan->id,
            'nomor_rw' => '003',
        ]);
        Rt::create([
            'rw_id' => $rw->id,
            'nomor_rt' => '005',
        ]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'nik' => '1234567890123456',
            'password' => 'password',
            'password_confirmation' => 'password',
            'no_hp' => '081234567890',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Contoh No. 1',
            'id_kelurahan' => $kelurahan->id,
            'rw' => '003',
            'rt' => '005',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('register.success', absolute: false));

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'no_hp' => '081234567890',
            'kelurahan' => 'Pasir Impun',
            'rw' => '003',
            'rt' => '005',
        ]);
        $this->assertDatabaseHas('pemiliks', [
            'email' => 'test@example.com',
            'nama_lengkap' => 'Test User',
            'kelurahan' => 'Pasir Impun',
            'rw' => '003',
            'rt' => '005',
        ]);
    }
}
