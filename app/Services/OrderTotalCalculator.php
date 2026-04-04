<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ShippingMethod;

class OrderTotalCalculator
{
    public function __construct(
        protected ShippingCalculator $shippingCalculator,
        protected TaxCalculator $taxCalculator,
    ) {}

    public function calculate(Order $order, ShippingMethod $method, array $context): array
    {
        // Calculate subtotal from items
        $subtotal = $order->items->sum(fn ($item) => (float) $item->price * $item->quantity);

        // Calculate shipping
        $zone = $this->shippingCalculator->resolveZone(
            $context['country'] ?? '',
            $context['region'] ?? null
        );
        $shipping = $this->shippingCalculator->calculate($method, array_merge($context, [
            'order_total' => $subtotal,
            'item_count' => $order->items->sum('quantity'),
        ]), $zone);

        $shippingAmount = $shipping['rate'];

        // Apply coupon discount
        $discountAmount = 0.0;
        if ($order->coupon && $order->coupon->isValid($subtotal)) {
            $discountAmount = $order->coupon->calculateDiscount($subtotal);
        }

        // Calculate tax
        $items = $order->items->map(fn ($item) => [
            'product_id' => $item->product_id,
            'category_id' => $item->product?->category_id,
            'price' => (float) $item->price,
            'qty' => $item->quantity,
        ])->toArray();

        $taxResult = $this->taxCalculator->calculate([
            'country' => $context['country'] ?? null,
            'region' => $context['region'] ?? null,
            'channel' => $context['channel'] ?? 'web',
            'items' => $items,
            'shipping_amount' => $shippingAmount,
        ]);

        $taxAmount = $taxResult['tax_amount'];
        $shippingTaxAmount = $taxResult['shipping_tax_amount'];

        $grandTotal = max(0, $subtotal - $discountAmount + $shippingAmount + $taxAmount + $shippingTaxAmount);

        return [
            'subtotal' => round($subtotal, 2),
            'shipping_amount' => round($shippingAmount, 2),
            'shipping_label' => $shipping['label'],
            'shipping_free' => $shipping['free'],
            'estimated_delivery' => $shipping['estimated_delivery'],
            'discount_amount' => round($discountAmount, 2),
            'tax_amount' => round($taxAmount, 2),
            'shipping_tax_amount' => round($shippingTaxAmount, 2),
            'tax_breakdown' => $taxResult['breakdown'],
            'grand_total' => round($grandTotal, 2),
            'currency' => $context['currency'] ?? 'USD',
        ];
    }
}
