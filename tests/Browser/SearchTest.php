<?php

namespace Tests\Browser;

use App\Models\Product;
use Facebook\WebDriver\WebDriverKeys;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SearchTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_search_input_accepts_text(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->type('@search-input', 'hoodie')
                ->assertInputValue('@search-input', 'hoodie');
        });
    }

    public function test_search_clear_button_appears_on_input(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertMissing('@search-clear')
                ->type('@search-input', 'hoodie')
                ->assertVisible('@search-clear');
        });
    }

    public function test_search_clear_button_clears_input(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->type('@search-input', 'hoodie')
                ->click('@search-clear')
                ->assertInputValue('@search-input', '');
        });
    }

    public function test_search_submits_on_enter(): void
    {
        Product::factory()->create(['name' => 'Black Hoodie']);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->type('@search-input', 'hoodie')
                ->keys('@search-input', WebDriverKeys::ENTER)
                ->waitForLocation('/products')
                ->assertQueryStringHas('q', 'hoodie');
        });
    }
}
