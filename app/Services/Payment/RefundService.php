<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Refund;
use App\Models\User;
use App\Notifications\RefundConfirmationNotification;
use Illuminate\Support\Facades\Log;

class RefundService
{
    public function processRefund(Order $order, float $amount, string $reason, User $admin): Refund
    {
        $refundable = $this->getRefundableAmount($order);

        if ($amount > $refundable + 0.01) {
            throw new \InvalidArgumentException(
                "Refund amount (\${$amount}) exceeds the refundable balance (\${$refundable})."
            );
        }

        $payment = $order->payments()->where('status', 'completed')->latest()->firstOrFail();

        $gateway = app('payment.gateway', ['gateway' => $payment->gateway]);

        $refund = Refund::create([
            'order_id' => $order->id,
            'payment_id' => $payment->id,
            'admin_id' => $admin->id,
            'gateway' => $payment->gateway,
            'amount' => $amount,
            'reason' => $reason,
            'status' => 'pending',
        ]);

        try {
            $result = $gateway->refund($payment->transaction_id, $amount, $reason);
        } catch (\Throwable $e) {
            Log::error('Refund gateway exception', [
                'refund_id' => $refund->id,
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            $refund->update([
                'status' => 'failed',
                'gateway_response' => ['error' => $e->getMessage()],
            ]);

            return $refund;
        }

        if ($result['success']) {
            $refund->update([
                'status' => 'completed',
                'gateway_refund_id' => $result['refund_id'],
                'gateway_response' => $result,
                'refunded_at' => now(),
            ]);

            // Refresh to recalculate totals
            $order->refresh();

            if ($order->isFullyRefunded()) {
                $order->update(['status' => 'refunded']);
            } else {
                $order->update(['status' => 'partially_refunded']);
            }

            $order->user->notify(new RefundConfirmationNotification($order, $refund));
        } else {
            $refund->update([
                'status' => 'failed',
                'gateway_response' => $result,
            ]);
        }

        Log::info('Refund processed', [
            'refund_id' => $refund->id,
            'order_id' => $order->id,
            'amount' => $amount,
            'gateway' => $payment->gateway,
            'status' => $refund->status,
        ]);

        return $refund;
    }

    public function getRefundableAmount(Order $order): float
    {
        $totalRefunded = $order->refunds()->where('status', 'completed')->sum('amount');

        return max(0, (float) $order->grand_total - (float) $totalRefunded);
    }
}
