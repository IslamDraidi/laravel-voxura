<?php

namespace Tests\Feature\Integration;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // App::setLocale() also mutates config('app.locale'), which persists across tests.
        // Reset to the original default before each test so fallback logic in SetLocale works.
        config(['app.locale' => 'en']);
        $this->app['translator']->setLocale('en');
    }

    public function test_guest_language_switch_persists_in_session(): void
    {
        $this->post(route('language.switch'), ['locale' => 'ar']);

        $this->assertEquals('ar', session('locale'));

        $response = $this->get('/');
        $response->assertStatus(200);
        $this->assertEquals('ar', app()->getLocale());
    }

    public function test_user_language_switch_persists_in_db(): void
    {
        $user = User::factory()->create(['preferred_locale' => 'en']);

        $this->actingAs($user)
             ->post(route('language.switch'), ['locale' => 'ar']);

        $this->assertDatabaseHas('users', [
            'id'               => $user->id,
            'preferred_locale' => 'ar',
        ]);
    }

    public function test_user_locale_loaded_on_next_request(): void
    {
        $user = User::factory()->create(['preferred_locale' => 'ar']);

        $this->actingAs($user)->get('/');

        $this->assertEquals('ar', app()->getLocale());
    }

    public function test_locale_falls_back_after_logout(): void
    {
        $user = User::factory()->create(['preferred_locale' => 'ar']);

        $this->actingAs($user)->post('/logout');

        // App::setLocale() mutates config('app.locale'), which persists within the same
        // PHP process across test requests. Reset to simulate a fresh process (which is
        // what actually happens in production between separate HTTP requests).
        config(['app.locale' => 'en']);
        $this->app['translator']->setLocale('en');

        $this->get('/');

        $this->assertEquals('en', app()->getLocale());
    }

    public function test_invalid_locale_is_rejected_and_session_unchanged(): void
    {
        $this->withSession(['locale' => 'en'])
             ->post(route('language.switch'), ['locale' => 'zh']);

        $this->assertNotEquals('zh', session('locale'));
    }

    public function test_switching_language_redirects(): void
    {
        $response = $this->post(route('language.switch'), ['locale' => 'ar']);

        $response->assertRedirect();
    }
}
