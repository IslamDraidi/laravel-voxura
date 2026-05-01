<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;

/**
 * Value object mimicking CartItem for use in cart/checkout views.
 * The $id is a string key like "3_1" (productId_variantId).
 */
class GuestCartItem
{
    public readonly int $product_id;
    public readonly ?int $variant_id;

    public function __construct(
        public readonly string $id,
        public readonly Product $product,
        public readonly ?ProductVariant $variant,
        public int $quantity,
    ) {
        $this->product_id = $product->id;
        $this->variant_id = $variant?->id;
    }

    public function unitPrice(): float
    {
        $price = (float) $this->product->price;
        if ($this->variant) {
            $price += (float) $this->variant->price_modifier;
        }
        return $price;
    }

    public function subtotal(): float
    {
        return $this->unitPrice() * $this->quantity;
    }
}
