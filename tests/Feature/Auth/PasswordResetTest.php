<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        $nik = '1234567890123456';
        $user = User::factory()->create(['nik' => $nik]);

        $response = $this->post('/forgot-password', [
            'email' => $user->email,
            'nik' => $nik,
        ]);

        $response->assertSessionHasNoErrors()
            ->assertSessionHas('status', 'Permintaan reset password berhasil dikirim ke Super Admin. Silakan cek status Anda di halaman ini secara berkala.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'password_reset_status' => 'pending',
        ]);
    }

    public function test_reset_password_request_rejects_wrong_nik(): void
    {
        $user = User::factory()->create(['nik' => '1234567890123456']);

        $response = $this->post('/forgot-password', [
            'email' => $user->email,
            'nik' => '9999999999999999',
        ]);

        $response->assertSessionHasErrors('nik');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'password_reset_status' => 'pending',
        ]);
    }

    public function test_pending_reset_request_returns_status_message(): void
    {
        $nik = '1234567890123456';
        $user = User::factory()->create([
            'nik' => $nik,
            'password_reset_status' => 'pending',
            'password_reset_requested_at' => now(),
        ]);

        $response = $this->post('/forgot-password', [
            'email' => $user->email,
            'nik' => $nik,
        ]);

        $response->assertSessionHasNoErrors()
            ->assertSessionHas('status', 'Permintaan Anda masih diproses oleh Super Admin. Silakan cek kembali nanti.');
    }
}
