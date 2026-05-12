<?php

namespace Tests\Feature\Integration;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartProductFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_product_to_cart(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'price' => 100]);

        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity'   => 2,
        ]);

        $cart = $user->fresh()->getOrCreateCart();

        $this->assertDatabaseHas('cart_items', [
            'shopping_cart_id' => $cart->id,
            'product_id'       => $product->id,
            'quantity'         => 2,
        ]);
    }

    public function test_update_cart_quantity(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        $cart = $user->fresh()->getOrCreateCart();
        $item = $cart->items()->where('product_id', $product->id)->first();

        $this->actingAs($user)->patch(route('cart.update', $item->id), ['quantity' => 3]);

        $this->assertDatabaseHas('cart_items', [
            'id'       => $item->id,
            'quantity' => 3,
        ]);
    }

    public function test_remove_item_from_cart(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        $cart = $user->fresh()->getOrCreateCart();
        $item = $cart->items()->where('product_id', $product->id)->first();

        $this->actingAs($user)->delete(route('cart.remove', $item->id));

        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    public function test_cannot_add_out_of_stock_product(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->outOfStock()->create();

        $response = $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        $response->assertSessionHasErrors('quantity');

        $cart = $user->fresh()->getOrCreateCart();
        $this->assertEquals(0, $cart->items()->count());
    }

    public function test_guest_can_add_to_cart_without_login(): void
    {
        $product = Product::factory()->create(['stock' => 5]);

        $response = $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        // Guests use session-based cart — no redirect to login
        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_cart_count_increments_correctly(): void
    {
        $user     = User::factory()->create();
        $product1 = Product::factory()->create(['stock' => 10]);
        $product2 = Product::factory()->create(['stock' => 10]);

        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product1->id,
            'quantity'   => 1,
        ]);

        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product2->id,
            'quantity'   => 2,
        ]);

        $this->assertEquals(3, $user->fresh()->cartCount());
    }
}
