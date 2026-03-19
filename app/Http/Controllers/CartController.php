<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
  

    /** GET /cart — show the cart */
    public function index()
    {
        $cart = auth()->user()->getOrCreateCart();
        $cart->load('items.product.category');

        return view('cart.index', compact('cart'));
    }

    /** POST /cart/add — add a product (or increment qty) */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'sometimes|integer|min:1|max:99',
        ]);

        $cart    = auth()->user()->getOrCreateCart();
        $qty     = $request->input('quantity', 1);
        $product = Product::findOrFail($request->product_id);

        $item = $cart->items()->firstOrCreate(
            ['product_id' => $product->id],
            ['quantity'   => 0]
        );

        $item->increment('quantity', $qty);

        return back()->with('success', "\"{$product->name}\" added to your cart!");
    }

    /** PATCH /cart/items/{item} — update quantity */
    public function update(Request $request, CartItem $item)
    {
        $this->authorizeItem($item);

        $request->validate(['quantity' => 'required|integer|min:1|max:99']);
        $item->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated.');
    }

    /** DELETE /cart/items/{item} — remove an item */
    public function remove(CartItem $item)
    {
        $this->authorizeItem($item);
        $item->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    /** DELETE /cart/clear — empty the whole cart */
    public function clear()
    {
        auth()->user()->getOrCreateCart()->items()->delete();
        return back()->with('success', 'Cart cleared.');
    }

    // ── Private ──

    private function authorizeItem(CartItem $item): void
    {
        abort_unless($item->cart->user_id === auth()->id(), 403);
    }
}