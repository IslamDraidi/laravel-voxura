<?php

namespace Tests\Feature\Components;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class SetLocaleMiddlewareComponentTest extends TestCase
{
    use RefreshDatabase;

    public function test_middleware_sets_locale_from_user_db(): void
    {
        $user = User::factory()->create(['preferred_locale' => 'ar']);

        $this->actingAs($user)->get('/');

        $this->assertEquals('ar', App::getLocale());
    }

    public function test_middleware_sets_locale_from_session(): void
    {
        $this->withSession(['locale' => 'ar'])->get('/');

        $this->assertEquals('ar', App::getLocale());
    }

    public function test_middleware_defaults_to_english(): void
    {
        $this->get('/');

        $this->assertEquals('en', App::getLocale());
    }

    public function test_middleware_ignores_invalid_locale(): void
    {
        $this->withSession(['locale' => 'zh'])->get('/');

        $this->assertEquals('en', App::getLocale());
    }
}
