<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LanguageSwitcherTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_switching_to_arabic_changes_direction(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertAttribute('html', 'dir', 'ltr')
                ->click('@lang-ar-btn')
                ->waitForReload()
                ->assertAttribute('html', 'dir', 'rtl')
                ->assertAttribute('html', 'lang', 'ar');
        });
    }

    public function test_switching_back_to_english(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->click('@lang-ar-btn')
                ->waitForReload()
                ->click('@lang-en-btn')
                ->waitForReload()
                ->assertAttribute('html', 'dir', 'ltr');
        });
    }

    public function test_arabic_shows_tajawal_font(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->click('@lang-ar-btn')
                ->waitForReload()
                ->assertSourceHas('Tajawal');
        });
    }

    public function test_language_persists_across_pages(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->click('@lang-ar-btn')
                ->waitForReload()
                ->visit('/products')
                ->assertAttribute('html', 'dir', 'rtl');
        });
    }
}
