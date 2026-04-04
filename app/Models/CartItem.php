<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['shopping_cart_id', 'product_id', 'variant_id', 'quantity'];

    public function cart()
    {
        return $this->belongsTo(ShoppingCart::class, 'shopping_cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
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
