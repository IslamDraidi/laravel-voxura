<?php

namespace App\Services;

use App\Events\TaxRateResolved;
use App\Models\Order;
use App\Models\TaxRate;

class TaxCalculator
{
    public function calculate(array $context): array
    {
        $country = $context['country'] ?? null;
        $channel = $context['channel'] ?? 'web';
        $items = $context['items'] ?? [];
        $shippingAmount = $context['shipping_amount'] ?? 0.0;

        // Load applicable tax rates
        $taxRates = TaxRate::active()
            ->forCountry($country)
            ->forChannel($channel)
            ->currentlyValid()
            ->orderBy('priority')
            ->get();

        // Fire event to allow modification
        $event = new TaxRateResolved($taxRates->toArray(), $context);
        event($event);

        $breakdown = [];
        $totalTax = 0.0;
        $shippingTax = 0.0;

        // Calculate subtotal
        $subtotal = collect($items)->sum(fn ($item) => ($item['price'] ?? 0) * ($item['qty'] ?? 1));

        foreach ($taxRates as $taxRate) {
            $taxAmount = $this->calculateForRate($taxRate, $items, $subtotal, $shippingAmount);

            if ($taxAmount['item_tax'] > 0 || $taxAmount['shipping_tax'] > 0) {
                $totalAmount = $taxAmount['item_tax'] + $taxAmount['shipping_tax'];

                $breakdown[] = [
                    'name' => $taxRate->translated_name,
                    'rate' => (float) $taxRate->rate,
                    'amount' => round($totalAmount, 2),
                    'scope' => $taxRate->scope,
                    'inclusive' => $taxRate->is_inclusive,
                ];

                $totalTax += $taxAmount['item_tax'];
                $shippingTax += $taxAmount['shipping_tax'];

                // For compound taxes, add to the running subtotal
                if ($taxRate->type === 'compound') {
                    $subtotal += $taxAmount['item_tax'];
                }
            }
        }

        return [
            'tax_amount' => round($totalTax, 2),
            'shipping_tax_amount' => round($shippingTax, 2),
            'breakdown' => $breakdown,
            'inclusive' => $taxRates->contains('is_inclusive', true),
        ];
    }

    public function applyToOrder(Order $order): Order
    {
        $items = $order->items->map(fn ($item) => [
            'product_id' => $item->product_id,
            'category_id' => $item->product?->category_id,
            'price' => (float) $item->price,
            'qty' => $item->quantity,
        ])->toArray();

        $context = [
            'country' => $order->currency === 'JOD' ? 'JO' : null,
            'channel' => $order->channel ?? 'web',
            'items' => $items,
            'shipping_amount' => (float) $order->shipping_cost,
        ];

        $result = $this->calculate($context);

        $order->tax_amount = $result['tax_amount'];
        $order->shipping_tax_amount = $result['shipping_tax_amount'];
        $order->tax_breakdown = $result['breakdown'];

        // Recalculate grand total
        $order->grand_total = max(0,
            (float) $order->subtotal
            - (float) $order->discount_amount
            + (float) $order->shipping_cost
            + $result['tax_amount']
            + $result['shipping_tax_amount']
        );

        $order->save();

        return $order;
    }

    protected function calculateForRate(TaxRate $taxRate, array $items, float $subtotal, float $shippingAmount): array
    {
        $itemTax = 0.0;
        $shippingTaxAmount = 0.0;

        $rate = (float) $taxRate->rate;

        // Calculate item tax based on scope
        $taxableAmount = match ($taxRate->scope) {
            'product' => $subtotal,
            'category' => collect($items)
                ->filter(fn ($item) => ($item['category_id'] ?? null) == $taxRate->category_id)
                ->sum(fn ($item) => ($item['price'] ?? 0) * ($item['qty'] ?? 1)),
            'order' => $subtotal,
            'shipping' => 0.0,
            default => $subtotal,
        };

        if ($taxRate->type === 'fixed') {
            $itemTax = $taxRate->scope !== 'shipping' ? $rate : 0.0;
        } else {
            // Percentage or compound
            if ($taxRate->is_inclusive) {
                // Back-calculate: tax = price - (price / (1 + rate/100))
                $itemTax = $taxableAmount - ($taxableAmount / (1 + $rate / 100));
            } else {
                $itemTax = $taxableAmount * ($rate / 100);
            }
        }

        // Apply to shipping if scope is 'shipping' or apply_to_shipping is true
        if ($taxRate->scope === 'shipping' || $taxRate->apply_to_shipping) {
            if ($taxRate->type === 'fixed') {
                $shippingTaxAmount = $taxRate->scope === 'shipping' ? $rate : $rate;
            } else {
                if ($taxRate->is_inclusive) {
                    $shippingTaxAmount = $shippingAmount - ($shippingAmount / (1 + $rate / 100));
                } else {
                    $shippingTaxAmount = $shippingAmount * ($rate / 100);
                }
            }
        }

        return [
            'item_tax' => round($itemTax, 2),
            'shipping_tax' => round($shippingTaxAmount, 2),
        ];
    }
}
