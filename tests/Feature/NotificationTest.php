<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Role::where('name', 'super_admin')->exists()) {
            Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        }
    }

    public function test_user_can_mark_notification_as_read(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        // Create a fake database notification
        $notification = DatabaseNotification::create([
            'id' => 'a5d66296-4fbb-4cd3-94ee-32f433aeb908',
            'type' => 'App\Notifications\SystemNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->id,
            'data' => [
                'title' => 'Test Notification',
                'message' => 'This is a test',
                'type' => 'info',
                'url' => '#',
            ],
            'read_at' => null,
        ]);

        $this->assertNull($notification->fresh()->read_at);

        $response = $this->actingAs($user)
            ->postJson(route('notifications.read', ['id' => $notification->id]));

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_user_can_delete_notification(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        // Create a fake database notification
        $notification = DatabaseNotification::create([
            'id' => 'a5d66296-4fbb-4cd3-94ee-32f433aeb908',
            'type' => 'App\Notifications\SystemNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->id,
            'data' => [
                'title' => 'Test Notification',
                'message' => 'This is a test',
                'type' => 'info',
                'url' => '#',
            ],
            'read_at' => null,
        ]);

        $this->assertDatabaseHas('notifications', ['id' => $notification->id]);

        $response = $this->actingAs($user)
            ->deleteJson(route('notifications.destroy', ['id' => $notification->id]));

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }
}
