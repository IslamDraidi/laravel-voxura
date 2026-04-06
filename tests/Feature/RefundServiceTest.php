<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\User;
use App\Services\Payment\RefundService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RefundServiceTest extends TestCase
{
    use RefreshDatabase;

    private RefundService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RefundService();
    }

    private function makeOrder(float $grandTotal = 100.00): Order
    {
        $user = User::create([
            'name' => 'Test Customer',
            'email' => 'customer-' . uniqid() . '@test.com',
            'password' => 'password',
        ]);

        return Order::create([
            'user_id' => $user->id,
            'total_amount' => $grandTotal,
            'subtotal' => $grandTotal,
            'grand_total' => $grandTotal,
            'status' => 'paid',
            'currency' => 'USD',
            'channel' => 'web',
        ]);
    }

    private function makePayment(Order $order, string $gateway = 'paypal'): Payment
    {
        return Payment::create([
            'order_id' => $order->id,
            'amount' => $order->grand_total,
            'status' => 'completed',
            'payment_method' => $gateway,
            'gateway' => $gateway,
            'transaction_id' => 'TXN_' . uniqid(),
            'attempts' => 1,
        ]);
    }

    private function makeAdmin(): User
    {
        return User::create([
            'name' => 'Admin',
            'email' => 'admin-' . uniqid() . '@test.com',
            'password' => 'password',
            'role' => 'admin',
        ]);
    }

    public function test_get_refundable_amount_with_no_refunds(): void
    {
        $order = $this->makeOrder(150.00);

        $amount = $this->service->getRefundableAmount($order);

        $this->assertEquals(150.00, $amount);
    }

    public function test_get_refundable_amount_with_existing_refunds(): void
    {
        $order = $this->makeOrder(200.00);
        $payment = $this->makePayment($order);
        $admin = $this->makeAdmin();

        Refund::create([
            'order_id' => $order->id,
            'payment_id' => $payment->id,
            'admin_id' => $admin->id,
            'gateway' => 'paypal',
            'amount' => 50.00,
            'reason' => 'Partial refund test',
            'status' => 'completed',
        ]);

        $amount = $this->service->getRefundableAmount($order);

        $this->assertEquals(150.00, $amount);
    }

    public function test_failed_refunds_do_not_reduce_refundable_amount(): void
    {
        $order = $this->makeOrder(100.00);
        $payment = $this->makePayment($order);
        $admin = $this->makeAdmin();

        Refund::create([
            'order_id' => $order->id,
            'payment_id' => $payment->id,
            'admin_id' => $admin->id,
            'gateway' => 'paypal',
            'amount' => 40.00,
            'reason' => 'Failed refund test',
            'status' => 'failed',
        ]);

        $amount = $this->service->getRefundableAmount($order);

        $this->assertEquals(100.00, $amount);
    }

    public function test_process_refund_succeeds_with_mock_gateway(): void
    {
        Notification::fake();

        $order = $this->makeOrder(100.00);
        $payment = $this->makePayment($order);
        $admin = $this->makeAdmin();

        // Bind a mock gateway
        $this->app->bind('payment.gateway', function () {
            return new class implements \App\Services\Payment\PaymentGatewayInterface {
                public function createPayment(\App\Models\Order $order): array { return []; }
                public function capturePayment(string $id): array { return []; }
                public function refund(string $txnId, float $amount, string $reason): array {
                    return ['success' => true, 'refund_id' => 'REFUND_123', 'error_message' => null];
                }
                public function verifyWebhook(\Illuminate\Http\Request $request): bool { return true; }
                public function getGatewayName(): string { return 'mock'; }
            };
        });

        $refund = $this->service->processRefund($order, 100.00, 'Full refund for test order', $admin);

        $this->assertEquals('completed', $refund->status);
        $this->assertEquals('REFUND_123', $refund->gateway_refund_id);
        $this->assertEquals(100.00, (float) $refund->amount);

        $order->refresh();
        $this->assertEquals('refunded', $order->status);
    }

    public function test_partial_refund_sets_partially_refunded_status(): void
    {
        Notification::fake();

        $order = $this->makeOrder(100.00);
        $payment = $this->makePayment($order);
        $admin = $this->makeAdmin();

        $this->app->bind('payment.gateway', function () {
            return new class implements \App\Services\Payment\PaymentGatewayInterface {
                public function createPayment(\App\Models\Order $order): array { return []; }
                public function capturePayment(string $id): array { return []; }
                public function refund(string $txnId, float $amount, string $reason): array {
                    return ['success' => true, 'refund_id' => 'REFUND_456', 'error_message' => null];
                }
                public function verifyWebhook(\Illuminate\Http\Request $request): bool { return true; }
                public function getGatewayName(): string { return 'mock'; }
            };
        });

        $refund = $this->service->processRefund($order, 30.00, 'Partial refund test reason', $admin);

        $this->assertEquals('completed', $refund->status);

        $order->refresh();
        $this->assertEquals('partially_refunded', $order->status);
    }

    public function test_rejects_refund_exceeding_balance(): void
    {
        $order = $this->makeOrder(50.00);
        $this->makePayment($order);
        $admin = $this->makeAdmin();

        $this->expectException(\InvalidArgumentException::class);

        $this->service->processRefund($order, 60.00, 'Overage refund test reason', $admin);
    }

    public function test_gateway_failure_sets_refund_to_failed(): void
    {
        $order = $this->makeOrder(100.00);
        $this->makePayment($order);
        $admin = $this->makeAdmin();

        $this->app->bind('payment.gateway', function () {
            return new class implements \App\Services\Payment\PaymentGatewayInterface {
                public function createPayment(\App\Models\Order $order): array { return []; }
                public function capturePayment(string $id): array { return []; }
                public function refund(string $txnId, float $amount, string $reason): array {
                    return ['success' => false, 'refund_id' => null, 'error_message' => 'Gateway refused'];
                }
                public function verifyWebhook(\Illuminate\Http\Request $request): bool { return true; }
                public function getGatewayName(): string { return 'mock'; }
            };
        });

        $refund = $this->service->processRefund($order, 50.00, 'Failed gateway test reason', $admin);

        $this->assertEquals('failed', $refund->status);

        $order->refresh();
        $this->assertEquals('paid', $order->status);
    }
}
