<?php

namespace Tests\Feature\Integration;

use App\Mail\OrderShippedMail;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    private array $checkoutPayload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->checkoutPayload = [
            'full_name'   => 'Islam Draidi',
            'email'       => 'islam@test.com',
            'phone'       => '+970599000000',
            'address'     => '123 Test Street',
            'city'        => 'Ramallah',
            'postal_code' => '00000',
            'country'     => 'PS',
        ];
    }

    public function test_order_created_after_checkout(): void
    {
        Queue::fake();
        Mail::fake();

        $user    = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'price' => 100]);

        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        $this->actingAs($user)->post(route('checkout.place'), $this->checkoutPayload);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'status'  => 'pending',
        ]);
    }

    public function test_stock_decremented_after_order(): void
    {
        Mail::fake();

        $user    = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'price' => 50]);

        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity'   => 2,
        ]);

        $this->actingAs($user)->post(route('checkout.place'), $this->checkoutPayload);

        $this->assertDatabaseHas('products', [
            'id'    => $product->id,
            'stock' => 8,
        ]);
    }

    public function test_order_confirmation_mail_sent_after_checkout(): void
    {
        Mail::fake();

        $user    = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5, 'price' => 75]);

        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        $this->actingAs($user)->post(route('checkout.place'), $this->checkoutPayload);

        Mail::assertSent(\App\Mail\OrderConfirmationMail::class);
    }

    public function test_admin_can_update_order_status(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $user  = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'paid']);

        $this->actingAs($admin)->patch(
            route('admin.orders.status', $order->id),
            ['status' => 'shipped']
        );

        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => 'shipped',
        ]);
    }

    public function test_shipped_status_sends_mail_to_customer(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $user  = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'paid']);

        $this->actingAs($admin)->patch(
            route('admin.orders.status', $order->id),
            ['status' => 'shipped']
        );

        Mail::assertSent(OrderShippedMail::class);
    }

    public function test_customer_cannot_see_other_users_orders(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->get(route('orders.show', $order->id));

        $response->assertStatus(403);
    }

    public function test_customer_can_see_own_orders(): void
    {
        $user  = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('orders.show', $order->id));

        $response->assertStatus(200);
    }
}
