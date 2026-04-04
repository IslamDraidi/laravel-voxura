<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingMethod;
use App\Models\TaxRate;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /** GET /checkout */
    public function checkout()
    {
        $cart = auth()->user()->getOrCreateCart();
        $cart->load('items.product.category', 'items.variant');

        if ($cart->items->isEmpty()) {
            return redirect('/cart')->with('success', 'Add items to your cart before checking out.');
        }

        $taxRate = TaxRate::effectiveRate();
        $shippingMethods = ShippingMethod::active()->orderBy('price')->get();

        return view('checkout.index', compact('cart', 'taxRate', 'shippingMethods'));
    }

    /** POST /checkout — place the order */
    public function place(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:30',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'coupon_code' => 'nullable|string|max:50',
            'shipping_method_id' => 'nullable|exists:shipping_methods,id',
        ]);

        $user = auth()->user();
        $cart = $user->getOrCreateCart();
        $cart->load('items.product', 'items.variant');

        if ($cart->items->isEmpty()) {
            return redirect('/cart')->with('success', 'Your cart is empty.');
        }

        $shippingAddress = implode(', ', [
            $request->full_name,
            $request->address,
            $request->city,
            $request->postal_code,
            $request->country,
            $request->phone,
        ]);

        $total = $cart->items->sum(fn ($i) => $i->subtotal());
        $discountAmount = 0;
        $coupon = null;

        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();
            if ($coupon && $coupon->isValid($total)) {
                $discountAmount = $coupon->calculateDiscount($total);
            }
        }

        $taxRate = TaxRate::effectiveRate();
        $taxAmount = round(($total - $discountAmount) * $taxRate / 100, 2);

        $shippingMethod = null;
        $shippingCost = 0;
        if ($request->filled('shipping_method_id')) {
            $shippingMethod = ShippingMethod::active()->find($request->shipping_method_id);
            if ($shippingMethod) {
                $shippingCost = (float) $shippingMethod->price;
            }
        }

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'coupon_id' => $coupon?->id,
            'shipping_method_id' => $shippingMethod?->id,
            'coupon_code' => $coupon?->code,
            'total_amount' => $total,
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'shipping_cost' => $shippingCost,
            'status' => 'pending',
            'shipping_address' => $shippingAddress,
        ]);

        if ($coupon) {
            $coupon->increment('used_count');
        }

        // Create order items
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'variant_label' => $item->variant?->label(),
                'quantity' => $item->quantity,
                'price' => $item->unitPrice(),
            ]);

            // Decrement stock
            $item->product->decrement('stock', $item->quantity);
        }

        // Clear the cart
        $cart->items()->delete();

        return redirect('/orders/'.$order->id)
            ->with('success', 'Order placed successfully! Your order #'.$order->id.' is confirmed.');
    }

    /** GET /orders — order history */
    public function index()
    {
        $orders = auth()->user()
            ->orders()
            ->with('items.product')
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    /** GET /orders/{order} — single order detail */
    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load('items.product.category');

        return view('orders.show', compact('order'));
    }
}
