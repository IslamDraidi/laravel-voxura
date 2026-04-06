<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    /**
     * Create a payment session with the gateway.
     *
     * @return array{approval_url: string, gateway_order_id: string}
     */
    public function createPayment(Order $order): array;

    /**
     * Capture/confirm a payment after customer approval.
     *
     * @return array{success: bool, transaction_id: ?string, error_code: ?string, error_message: ?string}
     */
    public function capturePayment(string $gatewayOrderId): array;

    /**
     * Issue a refund against a captured payment.
     *
     * @return array{success: bool, refund_id: ?string, error_message: ?string}
     */
    public function refund(string $transactionId, float $amount, string $reason): array;

    /**
     * Verify an incoming webhook request signature.
     */
    public function verifyWebhook(Request $request): bool;

    /**
     * Get the gateway identifier (e.g. 'paypal', 'tap').
     */
    public function getGatewayName(): string;
}
