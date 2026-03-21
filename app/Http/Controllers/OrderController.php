<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /** GET /checkout */
    public function checkout()
    {
        $cart = auth()->user()->getOrCreateCart();
        $cart->load('items.product.category');

        if ($cart->items->isEmpty()) {
            return redirect('/cart')->with('success', 'Add items to your cart before checking out.');
        }

        return view('checkout.index', compact('cart'));
    }

    /** POST /checkout — place the order */
    public function place(Request $request)
    {
        $request->validate([
            'full_name'    => 'required|string|max:255',
            'email'        => 'required|email',
            'phone'        => 'required|string|max:30',
            'address'      => 'required|string|max:500',
            'city'         => 'required|string|max:100',
            'postal_code'  => 'required|string|max:20',
            'country'      => 'required|string|max:100',
        ]);

        $user = auth()->user();
        $cart = $user->getOrCreateCart();
        $cart->load('items.product');

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

        $total = $cart->items->sum(fn($i) => $i->product->price * $i->quantity);

        // Create order
        $order = Order::create([
            'user_id'          => $user->id,
            'total_amount'     => $total,
            'status'           => 'pending',
            'shipping_address' => $shippingAddress,
        ]);

        // Create order items
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
            ]);

            // Decrement stock
            $item->product->decrement('stock', $item->quantity);
        }

        // Clear the cart
        $cart->items()->delete();

        return redirect('/orders/' . $order->id)
            ->with('success', 'Order placed successfully! Your order #' . $order->id . ' is confirmed.');
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