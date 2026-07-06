<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Setting;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FlyerProgramToggleTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;
    protected $pelakuUser;

    protected function setUp(): void
    {
        parent::setUp();

        $roles = ['super_admin', 'pelaku_umkm'];
        foreach ($roles as $roleName) {
            if (!Role::where('name', $roleName)->exists()) {
                Role::create(['name' => $roleName, 'guard_name' => 'web']);
            }
        }

        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole('super_admin');

        $this->pelakuUser = User::factory()->create();
        $this->pelakuUser->assignRole('pelaku_umkm');
    }

    public function test_superadmin_can_access_flyer_management(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('superadmin.flyer.index'));
        $response->assertStatus(200);
        $response->assertViewIs('superadmin.flyer.index');
    }

    public function test_pelaku_cannot_access_flyer_management(): void
    {
        $response = $this->actingAs($this->pelakuUser)->get(route('superadmin.flyer.index'));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_flyer_management(): void
    {
        $response = $this->get(route('superadmin.flyer.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_superadmin_can_update_flyer_settings_without_image(): void
    {
        $payload = [
            'flyer_popup_active' => 'true',
            'flyer_popup_link' => 'https://example.com/bantuan',
            'flyer_popup_target' => 'dashboard',
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('superadmin.flyer.update'), $payload);
        $response->assertRedirect(route('superadmin.flyer.index'));

        $this->assertTrue(Setting::get('flyer_popup_active'));
        $this->assertEquals('https://example.com/bantuan', Setting::get('flyer_popup_link'));
        $this->assertEquals('dashboard', Setting::get('flyer_popup_target'));
    }

    public function test_superadmin_can_update_flyer_settings_with_image(): void
    {
        Storage::fake('public');

        $image = $this->fakePngUpload('flyer.png');

        $payload = [
            'flyer_popup_active' => 'true',
            'flyer_popup_link' => 'https://example.com/bantuan',
            'flyer_popup_target' => 'both',
            'flyer_popup_image' => $image,
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('superadmin.flyer.update'), $payload);
        $response->assertRedirect(route('superadmin.flyer.index'));

        $this->assertTrue(Setting::get('flyer_popup_active'));
        $this->assertEquals('both', Setting::get('flyer_popup_target'));

        $imagePath = Setting::get('flyer_popup_image');
        $this->assertNotEmpty($imagePath);

        Storage::disk('public')->assertExists($imagePath);
    }

    public function test_validation_rejects_invalid_inputs(): void
    {
        $payload = [
            'flyer_popup_active' => 'true',
            'flyer_popup_link' => 'not-a-valid-url',
            'flyer_popup_target' => 'invalid-target',
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('superadmin.flyer.update'), $payload);
        $response->assertSessionHasErrors(['flyer_popup_link', 'flyer_popup_target']);
    }

    public function test_superadmin_can_disable_flyer_setting(): void
    {
        Setting::set('flyer_popup_active', 'true');
        $this->assertTrue(Setting::get('flyer_popup_active'));

        $payload = [
            'flyer_popup_active' => 'false',
            'flyer_popup_link' => '',
            'flyer_popup_target' => 'both',
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('superadmin.flyer.update'), $payload);
        $response->assertRedirect(route('superadmin.flyer.index'));

        $this->assertFalse(Setting::get('flyer_popup_active'));
    }

    private function fakePngUpload(string $name): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'flyer-test-');

        file_put_contents($path, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII='
        ));

        return new UploadedFile($path, $name, 'image/png', null, true);
    }
}
