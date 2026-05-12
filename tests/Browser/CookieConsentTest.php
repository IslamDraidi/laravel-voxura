<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CookieConsentTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_cookie_banner_appears_on_first_visit(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertVisible('@cookie-banner')
                ->assertSee('cookies');
        });
    }

    public function test_accepting_cookies_hides_banner(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitFor('@cookie-banner')
                ->press('@cookie-accept-btn')
                ->waitUntilMissing('@cookie-banner')
                ->assertMissing('@cookie-banner');
        });
    }

    public function test_banner_does_not_reappear_after_accept(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->press('@cookie-accept-btn')
                ->waitUntilMissing('@cookie-banner')
                ->visit('/products')
                ->assertMissing('@cookie-banner');
        });
    }

    public function test_declining_cookies_hides_banner(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->press('@cookie-decline-btn')
                ->waitUntilMissing('@cookie-banner')
                ->assertMissing('@cookie-banner');
        });
    }
}
