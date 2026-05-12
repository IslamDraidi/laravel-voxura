<?php

namespace Tests\Feature\Components;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageControllerComponentTest extends TestCase
{
    use RefreshDatabase;

    public function test_switch_to_arabic_sets_session(): void
    {
        $this->post(route('language.switch'), ['locale' => 'ar']);

        $this->assertEquals('ar', session('locale'));
    }

    public function test_switch_to_english_sets_session(): void
    {
        $this->withSession(['locale' => 'ar'])
             ->post(route('language.switch'), ['locale' => 'en']);

        $this->assertEquals('en', session('locale'));
    }

    public function test_switch_saves_to_user_db(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post(route('language.switch'), ['locale' => 'ar']);

        $this->assertDatabaseHas('users', [
            'id'               => $user->id,
            'preferred_locale' => 'ar',
        ]);
    }

    public function test_invalid_locale_rejected(): void
    {
        $response = $this->post(route('language.switch'), ['locale' => 'fr']);

        $response->assertRedirect();
        $this->assertNotEquals('fr', session('locale'));
    }

    public function test_switch_redirects_back(): void
    {
        $response = $this->post(route('language.switch'), ['locale' => 'ar']);

        $response->assertRedirect();
    }
}
