<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['shopping_cart_id', 'product_id', 'quantity'];

    public function cart()
    {
        return $this->belongsTo(ShoppingCart::class, 'shopping_cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function subtotal()
    {
        return $this->product->price * $this->quantity;
    }
}