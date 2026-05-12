<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_register_via_ui(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->assertSee('Register')
                ->type('name', 'Islam Draidi')
                ->type('email', 'islam@voxura.test')
                ->type('password', 'Password123!')
                ->type('password_confirmation', 'Password123!')
                ->press('Register')
                ->assertPathIsNot('/register')
                ->assertAuthenticated();
        });
    }

    public function test_user_can_login_via_ui(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('Password123!'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'Password123!')
                ->press('Login')
                ->assertPathIsNot('/login');
        });
    }

    public function test_login_shows_error_for_wrong_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('correct'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'wrong')
                ->press('Login')
                ->assertPathIs('/login')
                ->assertSee('credentials');
        });
    }

    public function test_user_can_logout_via_ui(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/')
                ->click('@logout-button')
                ->assertGuest();
        });
    }
}
