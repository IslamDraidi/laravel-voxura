<?php

namespace Tests\Feature\Integration;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_registration_flow(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Islam Draidi',
            'email'                 => 'islam@test.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'islam@test.com']);
        $this->assertAuthenticated();
    }

    public function test_complete_login_flow(): void
    {
        $user = User::factory()->create(['password' => bcrypt('Password123!')]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'Password123!',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('correct')]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'wrong',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    public function test_logout_clears_session(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout');

        $this->assertGuest();
    }

    public function test_password_reset_notification_sent(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    public function test_rate_limiting_on_login(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email'    => $user->email,
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(429);
    }

    public function test_blocked_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'password'   => bcrypt('Password123!'),
            'is_blocked' => true,
        ]);

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'Password123!',
        ]);

        $this->assertGuest();
    }
}
