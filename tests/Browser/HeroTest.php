<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HeroTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_hero_scroll_indicator_visible(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertVisible('@scroll-indicator');
        });
    }

    public function test_scroll_indicator_scrolls_page_down(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');

            $scrollBefore = $browser->script('return window.scrollY')[0];

            $browser->click('@scroll-indicator')
                ->pause(800);

            $scrollAfter = $browser->script('return window.scrollY')[0];

            $this->assertGreaterThan($scrollBefore, $scrollAfter);
        });
    }

    public function test_new_arrivals_badge_visible(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('New arrivals every week');
        });
    }
}
