<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalPaymentService implements PaymentGatewayInterface
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;
    private string $webhookId;

    public function __construct()
    {
        $config = config('payment.gateways.paypal');
        $this->clientId = $config['client_id'] ?? '';
        $this->clientSecret = $config['client_secret'] ?? '';
        $this->webhookId = $config['webhook_id'] ?? '';
        $this->baseUrl = $config['base_url'];
    }

    public function getGatewayName(): string
    {
        return 'paypal';
    }

    public function createPayment(Order $order): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v2/checkout/orders", [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => (string) $order->id,
                    'amount' => [
                        'currency_code' => $order->currency ?? 'USD',
                        'value' => number_format((float) $order->grand_total, 2, '.', ''),
                    ],
                    'description' => "Voxura Order #{$order->id}",
                ]],
                'application_context' => [
                    'brand_name' => 'Voxura',
                    'return_url' => route('payment.callback', $order),
                    'cancel_url' => route('payment.cancel', $order),
                    'user_action' => 'PAY_NOW',
                ],
            ]);

        if ($response->failed()) {
            Log::error('PayPal createPayment failed', [
                'order_id' => $order->id,
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            $error = $response->json();
            throw new \RuntimeException(
                $error['details'][0]['description'] ?? $error['message'] ?? 'PayPal order creation failed'
            );
        }

        $data = $response->json();
        $approvalUrl = collect($data['links'])->firstWhere('rel', 'approve')['href'] ?? null;

        if (! $approvalUrl) {
            throw new \RuntimeException('No PayPal approval URL returned');
        }

        return [
            'approval_url' => $approvalUrl,
            'gateway_order_id' => $data['id'],
        ];
    }

    public function capturePayment(string $gatewayOrderId): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v2/checkout/orders/{$gatewayOrderId}/capture");

        $data = $response->json();

        if ($response->successful() && ($data['status'] ?? '') === 'COMPLETED') {
            $capture = $data['purchase_units'][0]['payments']['captures'][0] ?? [];

            return [
                'success' => true,
                'transaction_id' => $capture['id'] ?? $gatewayOrderId,
                'error_code' => null,
                'error_message' => null,
            ];
        }

        Log::warning('PayPal capturePayment failed', [
            'gateway_order_id' => $gatewayOrderId,
            'status' => $response->status(),
            'body' => $data,
        ]);

        $errorCode = $data['details'][0]['issue'] ?? $data['name'] ?? 'UNKNOWN';
        $errorMessage = $data['details'][0]['description'] ?? $data['message'] ?? 'Capture failed';

        return [
            'success' => false,
            'transaction_id' => null,
            'error_code' => $errorCode,
            'error_message' => $errorMessage,
        ];
    }

    public function refund(string $transactionId, float $amount, string $reason): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v2/payments/captures/{$transactionId}/refund", [
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => number_format($amount, 2, '.', ''),
                ],
                'note_to_payer' => substr($reason, 0, 255),
            ]);

        $data = $response->json();

        if ($response->successful() && in_array($data['status'] ?? '', ['COMPLETED', 'PENDING'])) {
            return [
                'success' => true,
                'refund_id' => $data['id'] ?? null,
                'error_message' => null,
            ];
        }

        Log::error('PayPal refund failed', [
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'body' => $data,
        ]);

        return [
            'success' => false,
            'refund_id' => null,
            'error_message' => $data['details'][0]['description'] ?? $data['message'] ?? 'Refund failed',
        ];
    }

    public function verifyWebhook(Request $request): bool
    {
        if (empty($this->webhookId)) {
            return false;
        }

        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v1/notifications/verify-webhook-signature", [
                'auth_algo' => $request->header('PAYPAL-AUTH-ALGO'),
                'cert_url' => $request->header('PAYPAL-CERT-URL'),
                'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
                'transmission_sig' => $request->header('PAYPAL-TRANSMISSION-SIG'),
                'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
                'webhook_id' => $this->webhookId,
                'webhook_event' => $request->all(),
            ]);

        return $response->successful()
            && ($response->json('verification_status') === 'SUCCESS');
    }

    private function getAccessToken(): string
    {
        $response = Http::asForm()
            ->withBasicAuth($this->clientId, $this->clientSecret)
            ->post("{$this->baseUrl}/v1/oauth2/token", [
                'grant_type' => 'client_credentials',
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('Failed to obtain PayPal access token');
        }

        return $response->json('access_token');
    }
}
