<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(): User
    {
        return User::create([
            'name' => 'Test User',
            'email' => 'user-' . uniqid() . '@test.com',
            'password' => 'password',
        ]);
    }

    private function makeOrder(User $user, string $status = 'pending'): Order
    {
        return Order::create([
            'user_id' => $user->id,
            'total_amount' => 100.00,
            'subtotal' => 100.00,
            'grand_total' => 100.00,
            'status' => $status,
            'currency' => 'USD',
            'channel' => 'web',
        ]);
    }

    public function test_payment_methods_page_loads_for_owner(): void
    {
        $user = $this->makeUser();
        $order = $this->makeOrder($user);

        $response = $this->actingAs($user)->get(route('payment.methods', $order));

        $response->assertStatus(200);
        $response->assertSee('Choose');
        $response->assertSee('Tap Payments');
        $response->assertDontSee('PayPal'); // PayPal disabled in config
    }

    public function test_payment_methods_page_rejects_non_owner(): void
    {
        $owner = $this->makeUser();
        $other = $this->makeUser();
        $order = $this->makeOrder($owner);

        $response = $this->actingAs($other)->get(route('payment.methods', $order));

        $response->assertStatus(403);
    }

    public function test_payment_methods_page_rejects_non_pending_order(): void
    {
        $user = $this->makeUser();
        $order = $this->makeOrder($user, 'delivered');

        $response = $this->actingAs($user)->get(route('payment.methods', $order));

        $response->assertStatus(404);
    }

    public function test_failed_page_loads(): void
    {
        $user = $this->makeUser();
        $order = $this->makeOrder($user);

        Payment::create([
            'order_id' => $order->id,
            'amount' => 100.00,
            'status' => 'failed',
            'gateway' => 'paypal',
            'failure_reason' => 'Card declined',
            'failure_code' => 'INSTRUMENT_DECLINED',
            'attempts' => 1,
            'last_attempted_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('payment.failed', $order));

        $response->assertStatus(200);
        $response->assertSee('Payment Failed');
        $response->assertSee('Try Again');
    }

    public function test_blocked_after_max_attempts(): void
    {
        $user = $this->makeUser();
        $order = $this->makeOrder($user);

        Payment::create([
            'order_id' => $order->id,
            'amount' => 100.00,
            'status' => 'failed',
            'gateway' => 'paypal',
            'failure_reason' => 'Card declined',
            'failure_code' => 'INSTRUMENT_DECLINED',
            'attempts' => 3,
            'last_attempted_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('payment.retry', $order));

        $order->refresh();
        $this->assertEquals('payment_blocked', $order->status);
    }

    public function test_retry_redirects_to_methods_when_under_limit(): void
    {
        $user = $this->makeUser();
        $order = $this->makeOrder($user);

        Payment::create([
            'order_id' => $order->id,
            'amount' => 100.00,
            'status' => 'failed',
            'gateway' => 'tap',
            'failure_reason' => 'Card expired',
            'failure_code' => 'EXPIRED_CARD',
            'attempts' => 1,
            'last_attempted_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('payment.retry', $order));

        $response->assertRedirect(route('payment.methods', $order));
    }

    public function test_process_payment_validates_gateway(): void
    {
        $user = $this->makeUser();
        $order = $this->makeOrder($user);

        $response = $this->actingAs($user)->post(route('payment.process', $order), [
            'gateway' => 'stripe',
        ]);

        $response->assertSessionHasErrors('gateway');
    }

    public function test_process_payment_rejects_non_pending_order(): void
    {
        $user = $this->makeUser();
        $order = $this->makeOrder($user, 'paid');

        $response = $this->actingAs($user)->post(route('payment.process', $order), [
            'gateway' => 'paypal',
        ]);

        $response->assertStatus(403);
    }
}
