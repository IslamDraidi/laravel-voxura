<?php

namespace Tests\Feature\Integration;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_cannot_access_profile(): void
    {
        $response = $this->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_unauthenticated_cannot_access_order_history(): void
    {
        $response = $this->get(route('orders.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_redirected_from_admin(): void
    {
        $user = User::factory()->create(['role' => 'buyer']);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertRedirect('/');
    }

    public function test_guest_redirected_from_admin(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_admin_panel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
    }

    public function test_api_tokens_not_in_homepage_source(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/');
        $content  = $response->getContent();

        $this->assertStringNotContainsString('HF_API_TOKEN', $content);
        $this->assertStringNotContainsString('FAL_KEY', $content);
        $this->assertStringNotContainsString('REPLICATE_API_TOKEN', $content);
    }

    public function test_csrf_protection_active_on_language_switch(): void
    {
        // Without middleware bypass, the test client includes CSRF → should succeed
        $response = $this->post(route('language.switch'), ['locale' => 'ar']);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_order_ownership_enforced(): void
    {
        $owner     = User::factory()->create();
        $intruder  = User::factory()->create();
        $order     = \App\Models\Order::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($intruder)->get(route('orders.show', $order->id));

        $response->assertStatus(403);
    }

    public function test_cart_item_ownership_enforced(): void
    {
        $user1   = User::factory()->create();
        $user2   = User::factory()->create();
        $product = \App\Models\Product::factory()->create(['stock' => 5]);

        $this->actingAs($user1)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        $cart = $user1->fresh()->getOrCreateCart();
        $item = $cart->items()->first();

        $response = $this->actingAs($user2)->delete(route('cart.remove', $item->id));

        $response->assertStatus(403);
    }
}
