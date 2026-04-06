<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\AdminPaymentBlockedNotification;
use App\Notifications\PaymentFailedNotification;
use App\Services\Payment\PaymentErrorMapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /** GET /payment/{order} — choose payment method */
    public function showMethods(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless(in_array($order->status, ['pending', 'payment_blocked']), 404);

        $order->load('items.product', 'shippingMethod');
        $payment = $order->payments()->latest()->first();
        $maxAttempts = config('payment.max_retry_attempts', 3);

        $attempts = $payment?->attempts ?? 0;
        $blocked = $attempts >= $maxAttempts;

        // Only show gateways that are enabled in config
        $enabledGateways = collect(config('payment.gateways'))
            ->filter(fn ($cfg) => $cfg['enabled'] ?? true)
            ->keys()
            ->values()
            ->all();

        return view('payment.methods', compact('order', 'payment', 'attempts', 'maxAttempts', 'blocked', 'enabledGateways'));
    }

    /** POST /payment/{order} — initiate payment with chosen gateway */
    public function processPayment(Request $request, Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless($order->status === 'pending', 403);

        $request->validate([
            'gateway' => 'required|in:paypal,tap',
        ]);

        $gatewayName = $request->gateway;

        // Block disabled gateways
        if (! (config("payment.gateways.{$gatewayName}.enabled", true))) {
            return back()->with('payment_error', match ($gatewayName) {
                'paypal' => 'PayPal is not available in your region. Please use Tap Payments.',
                default => 'This payment method is currently unavailable.',
            });
        }
        $maxAttempts = config('payment.max_retry_attempts', 3);

        // Get or create payment record
        $payment = $order->payments()->latest()->first();

        if ($payment && $payment->attempts >= $maxAttempts) {
            $order->update(['status' => 'payment_blocked']);

            return redirect()->route('payment.failed', $order)
                ->with('payment_error', 'Maximum payment attempts reached. Please contact support.');
        }

        $gateway = app('payment.gateway', ['gateway' => $gatewayName]);

        try {
            $result = $gateway->createPayment($order);
        } catch (\Throwable $e) {
            Log::error('Payment creation failed', [
                'order_id' => $order->id,
                'gateway' => $gatewayName,
                'error' => $e->getMessage(),
            ]);

            if (! $payment) {
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'amount' => $order->grand_total,
                    'status' => 'failed',
                    'payment_method' => $gatewayName,
                    'gateway' => $gatewayName,
                    'attempts' => 1,
                    'last_attempted_at' => now(),
                    'failure_reason' => $e->getMessage(),
                    'failure_code' => 'CREATION_FAILED',
                ]);
            } else {
                $payment->update([
                    'status' => 'failed',
                    'gateway' => $gatewayName,
                    'attempts' => $payment->attempts + 1,
                    'last_attempted_at' => now(),
                    'failure_reason' => $e->getMessage(),
                    'failure_code' => 'CREATION_FAILED',
                ]);
            }

            return redirect()->route('payment.failed', $order);
        }

        // Create or update payment record as pending
        if (! $payment) {
            Payment::create([
                'order_id' => $order->id,
                'amount' => $order->grand_total,
                'status' => 'pending',
                'payment_method' => $gatewayName,
                'gateway' => $gatewayName,
                'transaction_id' => $result['gateway_order_id'],
                'attempts' => 1,
                'last_attempted_at' => now(),
            ]);
        } else {
            $payment->update([
                'status' => 'pending',
                'payment_method' => $gatewayName,
                'gateway' => $gatewayName,
                'transaction_id' => $result['gateway_order_id'],
                'attempts' => $payment->attempts + 1,
                'last_attempted_at' => now(),
                'failure_reason' => null,
                'failure_code' => null,
            ]);
        }

        Log::info('Payment initiated', [
            'order_id' => $order->id,
            'gateway' => $gatewayName,
            'amount' => $order->grand_total,
        ]);

        return redirect()->away($result['approval_url']);
    }

    /** GET /payment/{order}/callback — gateway returns customer here */
    public function callback(Request $request, Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $payment = $order->payments()->latest()->firstOrFail();
        $gateway = app('payment.gateway', ['gateway' => $payment->gateway]);

        // Get the gateway order ID from the payment record or request
        $gatewayOrderId = $request->query('token')       // PayPal
            ?? $request->query('tap_id')                  // Tap
            ?? $payment->transaction_id;

        $result = $gateway->capturePayment($gatewayOrderId);

        if ($result['success']) {
            $payment->update([
                'status' => 'completed',
                'transaction_id' => $result['transaction_id'],
                'gateway_response' => $result,
                'failure_reason' => null,
                'failure_code' => null,
            ]);

            $order->update(['status' => 'paid']);

            Log::info('Payment completed', [
                'order_id' => $order->id,
                'gateway' => $payment->gateway,
                'transaction_id' => $result['transaction_id'],
                'amount' => $payment->amount,
            ]);

            return redirect()->route('orders.show', $order)
                ->with('success', 'Payment successful! Your order #' . $order->id . ' is confirmed.');
        }

        // Payment failed
        $friendlyMessage = PaymentErrorMapper::getFriendlyMessage($payment->gateway, $result['error_code']);

        $payment->update([
            'status' => 'failed',
            'failure_code' => $result['error_code'],
            'failure_reason' => $friendlyMessage,
            'gateway_response' => $result,
        ]);

        Log::warning('Payment failed at callback', [
            'order_id' => $order->id,
            'gateway' => $payment->gateway,
            'error_code' => $result['error_code'],
            'attempts' => $payment->attempts,
        ]);

        // Send notification after 2nd failure
        if ($payment->attempts >= 2) {
            $order->user->notify(new PaymentFailedNotification($order, $payment));
        }

        // Block after max attempts
        $maxAttempts = config('payment.max_retry_attempts', 3);
        if ($payment->attempts >= $maxAttempts) {
            $order->update(['status' => 'payment_blocked']);

            User::where('role', 'admin')->each(function ($admin) use ($order, $payment) {
                $admin->notify(new AdminPaymentBlockedNotification($order, $payment));
            });
        }

        return redirect()->route('payment.failed', $order);
    }

    /** GET /payment/{order}/cancel — customer cancelled at gateway */
    public function cancel(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        return redirect()->route('payment.methods', $order)
            ->with('payment_error', 'Payment was cancelled. You can try again or choose a different method.');
    }

    /** GET /payment/{order}/retry — retry a failed payment */
    public function retry(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless(in_array($order->status, ['pending', 'payment_blocked']), 404);

        $payment = $order->payments()->latest()->first();
        $maxAttempts = config('payment.max_retry_attempts', 3);

        if ($payment && $payment->attempts >= $maxAttempts) {
            $order->update(['status' => 'payment_blocked']);

            User::where('role', 'admin')->each(function ($admin) use ($order, $payment) {
                $admin->notify(new AdminPaymentBlockedNotification($order, $payment));
            });

            return redirect()->route('payment.failed', $order)
                ->with('payment_error', 'Maximum payment attempts reached. Please contact support.');
        }

        $errorMessage = $payment?->failure_reason;

        return redirect()->route('payment.methods', $order)
            ->with('payment_error', $errorMessage);
    }

    /** GET /payment/{order}/failed — show failure page */
    public function failed(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items.product', 'shippingMethod');
        $payment = $order->payments()->latest()->first();
        $maxAttempts = config('payment.max_retry_attempts', 3);

        $attempts = $payment?->attempts ?? 0;
        $blocked = $attempts >= $maxAttempts || $order->status === 'payment_blocked';
        $errorMessage = $payment?->failure_reason
            ?? session('payment_error')
            ?? 'Payment failed. Please try again or use a different payment method.';

        return view('payment.failed', compact('order', 'payment', 'attempts', 'maxAttempts', 'blocked', 'errorMessage'));
    }

    /** POST /webhooks/payment/{gateway} — gateway webhook */
    public function webhook(Request $request, string $gateway)
    {
        abort_unless(in_array($gateway, ['paypal', 'tap']), 404);

        $gatewayService = app('payment.gateway', ['gateway' => $gateway]);

        if (! $gatewayService->verifyWebhook($request)) {
            Log::warning('Webhook signature verification failed', ['gateway' => $gateway]);

            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $payload = $request->all();

        Log::info('Webhook received', [
            'gateway' => $gateway,
            'event_type' => $payload['event_type'] ?? $payload['type'] ?? 'unknown',
        ]);

        // Handle based on gateway
        if ($gateway === 'paypal') {
            $this->handlePayPalWebhook($payload);
        } else {
            $this->handleTapWebhook($payload);
        }

        return response()->json(['status' => 'ok']);
    }

    private function handlePayPalWebhook(array $payload): void
    {
        $eventType = $payload['event_type'] ?? '';
        $resource = $payload['resource'] ?? [];

        if ($eventType === 'PAYMENT.CAPTURE.COMPLETED') {
            $orderId = $resource['custom_id'] ?? null;
            $transactionId = $resource['id'] ?? null;

            if ($orderId && $transactionId) {
                $payment = Payment::where('order_id', $orderId)
                    ->where('gateway', 'paypal')
                    ->latest()
                    ->first();

                if ($payment && $payment->status !== 'completed') {
                    $payment->update([
                        'status' => 'completed',
                        'transaction_id' => $transactionId,
                        'gateway_response' => $resource,
                    ]);
                    $payment->order->update(['status' => 'paid']);
                }
            }
        }
    }

    private function handleTapWebhook(array $payload): void
    {
        $chargeId = $payload['id'] ?? null;
        $status = $payload['status'] ?? '';

        if ($chargeId && $status === 'CAPTURED') {
            $payment = Payment::where('transaction_id', $chargeId)
                ->where('gateway', 'tap')
                ->latest()
                ->first();

            if ($payment && $payment->status !== 'completed') {
                $payment->update([
                    'status' => 'completed',
                    'gateway_response' => $payload,
                ]);
                $payment->order->update(['status' => 'paid']);
            }
        }
    }
}
