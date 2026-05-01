<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\GuestCart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /** GET /cart — show the cart */
    public function index()
    {
        if (auth()->check()) {
            $cart = auth()->user()->getOrCreateCart();
            $cart->load('items.product.category', 'items.variant');
        } else {
            $cart = new GuestCart();
        }

        return view('cart.index', compact('cart'));
    }

    /** POST /cart/add — add a product (or increment qty) */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity'   => 'sometimes|integer|min:1|max:99',
        ]);

        $qty       = $request->input('quantity', 1);
        $product   = Product::findOrFail($request->product_id);
        $variant   = null;
        $variantId = null;

        if ($request->filled('variant_id')) {
            $variant = ProductVariant::where('id', $request->variant_id)
                ->where('product_id', $product->id)
                ->firstOrFail();
            $variantId = $variant->id;
        }

        $maxAllowedQuantity = $this->maxAllowedQuantity($product, $variant);
        if ($maxAllowedQuantity < 1) {
            return back()->withErrors(['quantity' => 'This product is currently out of stock.']);
        }

        if (auth()->check()) {
            $cart = auth()->user()->getOrCreateCart();
            $item = $cart->items()->firstOrCreate(
                ['product_id' => $product->id, 'variant_id' => $variantId],
                ['quantity' => 0]
            );

            if (($item->quantity + $qty) > $maxAllowedQuantity) {
                return back()->withErrors(['quantity' => "You can add up to {$maxAllowedQuantity} of this item."])->withInput();
            }

            $item->increment('quantity', $qty);
            $cartCount = auth()->user()->cartCount();
        } else {
            $guestCart = new GuestCart();

            // Check existing qty in session
            $existing = $guestCart->items->first(
                fn ($i) => $i->product_id === $product->id && $i->variant_id === $variantId
            );
            $existingQty = $existing?->quantity ?? 0;

            if (($existingQty + $qty) > $maxAllowedQuantity) {
                return back()->withErrors(['quantity' => "You can add up to {$maxAllowedQuantity} of this item."])->withInput();
            }

            $guestCart->add($product->id, $variantId, $qty);
            $cartCount = $guestCart->itemCount();
        }

        if ($request->boolean('buy_now')) {
            if ($request->ajax()) {
                return response()->json(['success' => true, 'redirect' => route('checkout')]);
            }
            return redirect()->route('checkout')->with('success', 'Item added to your cart.');
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => "\"{$product->name}\" added to your cart!", 'cartCount' => $cartCount]);
        }

        return back()->with('success', "\"{$product->name}\" added to your cart!");
    }

    /** PATCH /cart/items/{item} — update quantity */
    public function update(Request $request, string $item)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:99']);

        if (auth()->check()) {
            $cartItem = CartItem::findOrFail($item);
            $this->authorizeItem($cartItem);
            $cartItem->loadMissing('product', 'variant');

            $maxAllowedQuantity = $this->maxAllowedQuantity($cartItem->product, $cartItem->variant);
            if ((int) $request->quantity > $maxAllowedQuantity) {
                return back()->withErrors(['quantity' => "You can keep up to {$maxAllowedQuantity} of this item in your cart."]);
            }

            $cartItem->update(['quantity' => $request->quantity]);
        } else {
            (new GuestCart())->updateQuantity($item, (int) $request->quantity);
        }

        return back()->with('success', 'Cart updated.');
    }

    /** DELETE /cart/items/{item} — remove an item */
    public function remove(string $item)
    {
        if (auth()->check()) {
            $cartItem = CartItem::findOrFail($item);
            $this->authorizeItem($cartItem);
            $cartItem->delete();
        } else {
            (new GuestCart())->removeByKey($item);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    /** DELETE /cart/clear — empty the whole cart */
    public function clear()
    {
        if (auth()->check()) {
            auth()->user()->getOrCreateCart()->items()->delete();
        } else {
            (new GuestCart())->clear();
        }

        return back()->with('success', 'Cart cleared.');
    }

    // ── Private ──

    private function authorizeItem(CartItem $item): void
    {
        abort_unless($item->cart->user_id === auth()->id(), 403);
    }

    private function maxAllowedQuantity(Product $product, ?ProductVariant $variant = null): int
    {
        $availableStock = $variant?->effectiveStock($product) ?? (int) $product->stock;
        $productLimit   = (int) ($product->max_order_quantity ?? 99);

        return max(0, min($availableStock, $productLimit));
    }
}
