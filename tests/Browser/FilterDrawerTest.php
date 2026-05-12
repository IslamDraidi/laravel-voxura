<?php

namespace Tests\Browser;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FilterDrawerTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_filter_drawer_opens_on_click(): void
    {
        Product::factory()->count(3)->create();

        $this->browse(function (Browser $browser) {
            $browser->visit(route('products.index'))
                ->assertMissing('@filter-drawer')
                ->click('@filter-btn')
                ->waitFor('@filter-drawer')
                ->assertVisible('@filter-drawer');
        });
    }

    public function test_filter_drawer_closes_on_backdrop_click(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('products.index'))
                ->click('@filter-btn')
                ->waitFor('@filter-drawer')
                ->click('@filter-backdrop')
                ->waitUntilMissing('@filter-drawer')
                ->assertMissing('@filter-drawer');
        });
    }

    public function test_filter_drawer_closes_on_x_button(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('products.index'))
                ->click('@filter-btn')
                ->waitFor('@filter-drawer')
                ->click('@filter-close-btn')
                ->waitUntilMissing('@filter-drawer')
                ->assertMissing('@filter-drawer');
        });
    }

    public function test_active_filter_chip_appears(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('products.index') . '?size[]=M')
                ->assertSee('M')
                ->assertVisible('@filter-chip');
        });
    }

    public function test_removing_chip_clears_filter(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('products.index') . '?size[]=M')
                ->click('@filter-chip-remove')
                ->waitForReload()
                ->assertQueryStringMissing('size');
        });
    }

    public function test_product_grid_does_not_shift_when_drawer_opens(): void
    {
        Product::factory()->count(3)->create();

        $this->browse(function (Browser $browser) {
            $browser->visit(route('products.index'));

            $gridWidth = $browser->script('return document.querySelector(".product-grid") ? document.querySelector(".product-grid").offsetWidth : document.querySelector("[class*=grid]").offsetWidth')[0];

            $browser->click('@filter-btn')
                ->waitFor('@filter-drawer');

            $gridWidthAfter = $browser->script('return document.querySelector(".product-grid") ? document.querySelector(".product-grid").offsetWidth : document.querySelector("[class*=grid]").offsetWidth')[0];

            $this->assertEquals($gridWidth, $gridWidthAfter);
        });
    }
}
