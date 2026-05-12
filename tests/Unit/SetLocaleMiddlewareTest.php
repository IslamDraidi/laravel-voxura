<?php

namespace Tests\Unit;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class SetLocaleMiddlewareTest extends TestCase
{
    private SetLocale $middleware;
    private Request $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new SetLocale();
        $this->request   = Request::create('/');
    }

    private function dispatch(): void
    {
        $this->middleware->handle($this->request, fn() => null);
    }

    public function test_sets_locale_from_authenticated_user(): void
    {
        $user = new class {
            public string $preferred_locale = 'ar';
        };

        Auth::shouldReceive('check')->once()->andReturn(true);
        Auth::shouldReceive('user')->twice()->andReturn($user);

        $this->dispatch();

        $this->assertEquals('ar', App::getLocale());
    }

    public function test_sets_locale_from_session_for_guest(): void
    {
        Auth::shouldReceive('check')->once()->andReturn(false);
        Session::put('locale', 'ar');

        $this->dispatch();

        $this->assertEquals('ar', App::getLocale());
    }

    public function test_falls_back_to_default_locale(): void
    {
        Auth::shouldReceive('check')->once()->andReturn(false);
        Session::forget('locale');

        $this->dispatch();

        $this->assertEquals('en', App::getLocale());
    }

    public function test_rejects_invalid_locale(): void
    {
        Auth::shouldReceive('check')->once()->andReturn(false);
        Session::put('locale', 'fr');

        $this->dispatch();

        $this->assertEquals('en', App::getLocale());
    }

    public function test_user_preference_overrides_session(): void
    {
        $user = new class {
            public string $preferred_locale = 'ar';
        };

        Auth::shouldReceive('check')->once()->andReturn(true);
        Auth::shouldReceive('user')->twice()->andReturn($user);
        Session::put('locale', 'en');

        $this->dispatch();

        $this->assertEquals('ar', App::getLocale());
    }
}
