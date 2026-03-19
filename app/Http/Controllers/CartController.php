<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = auth()->user()->getOrCreateCart();
        $cart->load('items.product.category');
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'sometimes|integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Stock protection
        if ($product->stock <= 0) {
            return back()->with('error', "\"{$product->name}\" is out of stock.");
        }

        $cart = auth()->user()->getOrCreateCart();
        $qty  = $request->input('quantity', 1);

        $item = $cart->items()->firstOrCreate(
            ['product_id' => $product->id],
            ['quantity'   => 0]
        );

        // Don't exceed available stock
        $newQty = $item->quantity + $qty;
        if ($newQty > $product->stock) {
            return back()->with('error', "Only {$product->stock} units of \"{$product->name}\" available.");
        }

        $item->increment('quantity', $qty);

        return back()->with('success', "\"{$product->name}\" added to your cart!");
    }

    public function update(Request $request, CartItem $item)
    {
        $this->authorizeItem($item);
        $request->validate(['quantity' => 'required|integer|min:1|max:99']);

        // Stock protection on update
        if ($request->quantity > $item->product->stock) {
            return back()->with('error', "Only {$item->product->stock} units available.");
        }

        $item->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Cart updated.');
    }

    public function remove(CartItem $item)
    {
        $this->authorizeItem($item);
        $item->delete();
        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        auth()->user()->getOrCreateCart()->items()->delete();
        return back()->with('success', 'Cart cleared.');
    }

    private function authorizeItem(CartItem $item): void
    {
        abort_unless($item->cart->user_id === auth()->id(), 403);
    }
}