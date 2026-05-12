<?php

namespace Tests\Browser;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CartTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_add_to_cart_shows_success_toast(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $user    = User::factory()->create();

        $this->browse(function (Browser $browser) use ($product, $user) {
            $browser->loginAs($user)
                ->visit(route('products.show', $product->slug))
                ->press('@add-to-cart-btn')
                ->waitFor('@toast-success')
                ->assertVisible('@toast-success');
        });
    }

    public function test_cart_count_updates_after_add(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $user    = User::factory()->create();

        $this->browse(function (Browser $browser) use ($product, $user) {
            $browser->loginAs($user)
                ->visit(route('products.show', $product->slug));

            $countBefore = (int) $browser->text('@cart-count');

            $browser->press('@add-to-cart-btn')
                ->pause(600);

            $countAfter = (int) $browser->text('@cart-count');

            $this->assertGreaterThan($countBefore, $countAfter);
        });
    }
}
