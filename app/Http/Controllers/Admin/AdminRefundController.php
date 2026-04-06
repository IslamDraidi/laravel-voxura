<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Payment\RefundService;
use Illuminate\Http\Request;

class AdminRefundController extends Controller
{
    public function processRefund(Request $request, Order $order, RefundService $refundService)
    {
        $refundable = $refundService->getRefundableAmount($order);

        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $refundable],
            'reason' => 'required|string|min:10|max:500',
        ]);

        try {
            $refund = $refundService->processRefund(
                $order,
                (float) $request->amount,
                $request->reason,
                auth()->user(),
            );

            if ($refund->status === 'completed') {
                return back()->with('success', "Refund of \${$refund->amount} processed successfully.");
            }

            return back()->with('error', 'Refund failed. Gateway response: ' . ($refund->gateway_response['error_message'] ?? 'Unknown error'));
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }
}
