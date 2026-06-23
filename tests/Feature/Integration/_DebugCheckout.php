<?php
// Quick test to see what response checkout.place gives
namespace Tests\Feature\Integration;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DebugCheckout extends TestCase
{
    use RefreshDatabase;
    
    public function test_debug(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'price' => 100]);
        
        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        
        // Check cart state
        $cart = $user->getOrCreateCart();
        $itemCount = $cart->items()->count();
        dump("Cart items count: " . $itemCount);
        
        $response = $this->actingAs($user)->post(route('checkout.place'), [
            'full_name' => 'Test User',
            'email' => 'test@test.com',
            'phone' => '+1234567890',
            'address' => '123 Test St',
            'city' => 'Ramallah',
            'postal_code' => '00000',
            'country' => 'PS',
        ]);
        
        dump("Response status: " . $response->status());
        dump("Response headers: " . json_encode($response->headers->all()['location'] ?? []));
        if ($response->status() === 302) {
            dump("Redirect to: " . $response->headers->get('location'));
        }
        dump("Session errors: " . json_encode(session()->get('errors')?->all() ?? []));
    }
}
