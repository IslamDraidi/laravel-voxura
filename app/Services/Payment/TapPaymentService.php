<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TapPaymentService implements PaymentGatewayInterface
{
    private string $baseUrl;
    private string $secretKey;
    private string $webhookSecret;

    public function __construct()
    {
        $config = config('payment.gateways.tap');
        $this->secretKey = $config['secret_key'] ?? '';
        $this->webhookSecret = $config['webhook_secret'] ?? '';
        $this->baseUrl = $config['base_url'];
    }

    public function getGatewayName(): string
    {
        return 'tap';
    }

    public function createPayment(Order $order): array
    {
        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/charges", [
                'amount' => (float) $order->grand_total,
                'currency' => $order->currency ?? 'USD',
                'customer_initiated' => true,
                'threeDSecure' => true,
                'save_card' => false,
                'description' => "Voxura Order #{$order->id}",
                'metadata' => ['order_id' => $order->id],
                'reference' => ['order' => (string) $order->id],
                'receipt' => ['email' => true, 'sms' => false],
                'customer' => [
                    'first_name' => $order->user->name ?? 'Customer',
                    'email' => $order->user->email ?? '',
                ],
                'source' => ['id' => 'src_all'],
                'redirect' => [
                    'url' => route('payment.callback', ['order' => $order, 'gateway' => 'tap']),
                ],
                'post' => [
                    'url' => route('payment.webhook', ['gateway' => 'tap']),
                ],
            ]);

        if ($response->failed()) {
            Log::error('Tap createPayment failed', [
                'order_id' => $order->id,
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            $error = $response->json();
            throw new \RuntimeException(
                $error['errors'][0]['description'] ?? $error['message'] ?? 'Tap charge creation failed'
            );
        }

        $data = $response->json();

        return [
            'approval_url' => $data['transaction']['url'] ?? $data['url'] ?? '',
            'gateway_order_id' => $data['id'],
        ];
    }

    public function capturePayment(string $gatewayOrderId): array
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/charges/{$gatewayOrderId}");

        $data = $response->json();

        if ($response->successful() && ($data['status'] ?? '') === 'CAPTURED') {
            return [
                'success' => true,
                'transaction_id' => $data['id'],
                'error_code' => null,
                'error_message' => null,
            ];
        }

        Log::warning('Tap capturePayment failed', [
            'gateway_order_id' => $gatewayOrderId,
            'status' => $response->status(),
            'body' => $data,
        ]);

        $errorCode = $data['response']['code'] ?? $data['status'] ?? 'UNKNOWN';
        $errorMessage = $data['response']['message'] ?? 'Payment was not captured';

        return [
            'success' => false,
            'transaction_id' => null,
            'error_code' => $errorCode,
            'error_message' => $errorMessage,
        ];
    }

    public function refund(string $transactionId, float $amount, string $reason): array
    {
        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/refunds", [
                'charge_id' => $transactionId,
                'amount' => $amount,
                'currency' => 'USD',
                'reason' => $reason,
            ]);

        $data = $response->json();

        if ($response->successful() && in_array($data['status'] ?? '', ['REFUNDED', 'PENDING'])) {
            return [
                'success' => true,
                'refund_id' => $data['id'] ?? null,
                'error_message' => null,
            ];
        }

        Log::error('Tap refund failed', [
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'body' => $data,
        ]);

        return [
            'success' => false,
            'refund_id' => null,
            'error_message' => $data['response']['message'] ?? $data['message'] ?? 'Refund failed',
        ];
    }

    public function verifyWebhook(Request $request): bool
    {
        if (empty($this->webhookSecret)) {
            return false;
        }

        $signature = $request->header('Hashstring') ?? $request->header('hashstring') ?? '';
        $payload = $request->getContent();
        $computed = hash_hmac('sha256', $payload, $this->webhookSecret);

        return hash_equals($computed, $signature);
    }
}
