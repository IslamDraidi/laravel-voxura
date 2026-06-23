<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Mail\TemplateMail;
use App\Models\Coupon;
use App\Models\EmailTemplate;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingMethod;
use App\Models\TaxRate;
use App\Services\GuestCart;
use Illuminate\Support\Facades\DB;
use App\Services\ShippingCalculator;
use App\Services\TaxCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /** POST /checkout/quick — Buy Now: adds one product to cart then goes to checkout */
    public function quickCheckout(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'nullable|integer|min:1|max:100',
        ]);

        $product  = \App\Models\Product::findOrFail($request->product_id);
        $quantity = max(1, (int) ($request->quantity ?? 1));

        if (auth()->check()) {
            $cart = auth()->user()->getOrCreateCart();
            $item = $cart->items()->where('product_id', $product->id)->first();
            if ($item) {
                $item->increment('quantity', $quantity);
            } else {
                $cart->items()->create(['product_id' => $product->id, 'quantity' => $quantity]);
            }
        } else {
            (new \App\Services\GuestCart())->add($product->id, $quantity);
        }

        return redirect()->route('checkout');
    }

    /** GET /checkout */
    public function checkout(ShippingCalculator $shippingCalculator)
    {
        $cart = $this->resolveCart();

        if ($cart->items->isEmpty()) {
            return redirect('/cart')->with('success', 'Add items to your cart before checking out.');
        }

        $subtotal = $cart->total();
        $context = [
            'country'     => 'US',
            'channel'     => 'web',
            'order_total' => $subtotal,
            'total_weight' => 0,
            'item_count'  => $cart->itemCount(),
        ];

        $shippingMethods = $shippingCalculator->getAvailableMethods($context);

        if ($shippingMethods->isEmpty()) {
            $shippingMethods = ShippingMethod::active()->orderBy('sort_order')->orderBy('price')->get();
            foreach ($shippingMethods as $method) {
                $method->setAttribute('calculated_rate', (float) $method->price);
                $method->setAttribute('calculated_label', $method->name);
                $method->setAttribute('calculated_free', (float) $method->price == 0);
                $method->setAttribute('estimated_delivery_text', $method->estimated_delivery);
            }
        }

        $taxRate = TaxRate::effectiveRate();

        session(['checkout_shipping_methods' => $shippingMethods->pluck('id')->toArray()]);

        return view('checkout.index', compact('cart', 'taxRate', 'shippingMethods'));
    }

    /** POST /checkout/calculate — AJAX: recalculate totals on method change */
    public function calculateTotals(Request $request, ShippingCalculator $shippingCalculator, TaxCalculator $taxCalculator)
    {
        $request->validate([
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'country'            => 'nullable|string|max:2',
            'coupon_code'        => 'nullable|string|max:50',
        ]);

        $cart = $this->resolveCart();
        $subtotal = $cart->total();
        $country  = $request->country ?? 'US';

        $method = ShippingMethod::with('zones')->findOrFail($request->shipping_method_id);
        $zone   = $shippingCalculator->resolveZone($country);

        $context = [
            'country'     => $country,
            'channel'     => 'web',
            'order_total' => $subtotal,
            'total_weight' => 0,
            'item_count'  => $cart->itemCount(),
        ];

        $shippingResult = $shippingCalculator->calculate($method, $context, $zone);

        $discountAmount = 0;
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $discountAmount = $coupon->calculateDiscount($subtotal);
            }
        }

        $items = $cart->items->map(fn ($item) => [
            'product_id'  => $item->product_id,
            'category_id' => $item->product?->category_id,
            'price'       => $item->unitPrice(),
            'qty'         => $item->quantity,
        ])->toArray();

        $taxResult = $taxCalculator->calculate([
            'country'         => $country,
            'channel'         => 'web',
            'items'           => $items,
            'shipping_amount' => $shippingResult['rate'],
        ]);

        $grandTotal = max(0, $subtotal - $discountAmount + $shippingResult['rate'] + $taxResult['tax_amount'] + $taxResult['shipping_tax_amount']);

        return response()->json([
            'subtotal'          => round($subtotal, 2),
            'shipping_amount'   => round($shippingResult['rate'], 2),
            'shipping_label'    => $shippingResult['label'],
            'shipping_free'     => $shippingResult['free'],
            'estimated_delivery' => $shippingResult['estimated_delivery'],
            'discount_amount'   => round($discountAmount, 2),
            'tax_amount'        => round($taxResult['tax_amount'], 2),
            'shipping_tax_amount' => round($taxResult['shipping_tax_amount'], 2),
            'tax_breakdown'     => $taxResult['breakdown'],
            'grand_total'       => round($grandTotal, 2),
        ]);
    }

    /** POST /checkout — place the order */
    public function place(Request $request, ShippingCalculator $shippingCalculator, TaxCalculator $taxCalculator)
    {
        $request->validate([
            'full_name'          => 'required|string|max:255',
            'email'              => 'required|email',
            'phone'              => 'required|string|max:30',
            'address'            => 'required|string|max:500',
            'city'               => 'required|string|max:100',
            'postal_code'        => 'required|string|max:20',
            'country'            => 'required|string|max:100',
            'coupon_code'        => 'nullable|string|max:50',
            'shipping_method_id' => 'nullable|exists:shipping_methods,id',
        ]);

        $cart = $this->resolveCart();

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

        $subtotal       = $cart->items->sum(fn ($i) => $i->subtotal());
        $discountAmount = 0;
        $coupon         = null;

        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $discountAmount = $coupon->calculateDiscount($subtotal);
            }
        }

        $shippingMethod  = null;
        $shippingCost    = 0;
        $shippingZoneId  = null;
        if ($request->filled('shipping_method_id')) {
            $shippingMethod = ShippingMethod::active()->with('zones')->find($request->shipping_method_id);
            if ($shippingMethod) {
                $zone = $shippingCalculator->resolveZone($request->country ?? 'US');
                $shippingResult = $shippingCalculator->calculate($shippingMethod, [
                    'country'     => $request->country ?? 'US',
                    'channel'     => 'web',
                    'order_total' => $subtotal,
                    'total_weight' => 0,
                    'item_count'  => $cart->items->sum('quantity'),
                ], $zone);
                $shippingCost   = $shippingResult['rate'];
                $shippingZoneId = $zone?->id;
            }
        }

        $items = $cart->items->map(fn ($item) => [
            'product_id'  => $item->product_id,
            'category_id' => $item->product?->category_id,
            'price'       => $item->unitPrice(),
            'qty'         => $item->quantity,
        ])->toArray();

        $taxResult = $taxCalculator->calculate([
            'country'         => $request->country ?? 'US',
            'channel'         => 'web',
            'items'           => $items,
            'shipping_amount' => $shippingCost,
        ]);

        $taxAmount         = $taxResult['tax_amount'];
        $shippingTaxAmount = $taxResult['shipping_tax_amount'];
        $grandTotal        = max(0, $subtotal - $discountAmount + $shippingCost + $taxAmount + $shippingTaxAmount);

        $isGuest = ! auth()->check();

        $storeId = $cart->items->first()?->product?->store_id ?? null;

        $order = Order::create([
            'user_id'            => auth()->id(),
            'store_id'           => $storeId,
            'guest_email'        => $isGuest ? $request->email : null,
            'guest_name'         => $isGuest ? $request->full_name : null,
            'coupon_id'          => $coupon?->id,
            'shipping_method_id' => $shippingMethod?->id,
            'shipping_zone_id'   => $shippingZoneId,
            'coupon_code'        => $coupon?->code,
            'total_amount'       => $subtotal,
            'subtotal'           => $subtotal,
            'discount_amount'    => $discountAmount,
            'tax_amount'         => $taxAmount,
            'shipping_tax_amount' => $shippingTaxAmount,
            'tax_breakdown'      => $taxResult['breakdown'],
            'shipping_cost'      => $shippingCost,
            'grand_total'        => $grandTotal,
            'currency'           => 'ILS',
            'channel'            => 'web',
            'status'             => 'pending',
            'shipping_address'   => $shippingAddress,
        ]);

        if ($coupon) {
            $coupon->increment('used_count');
        }

        DB::transaction(function () use ($order, $cart) {
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item->product_id,
                    'variant_id'    => $item->variant_id,
                    'variant_label' => $item->variant?->label(),
                    'quantity'      => $item->quantity,
                    'price'         => $item->unitPrice(),
                ]);

                \App\Models\Product::lockForUpdate()->find($item->product_id)
                    ?->decrement('stock', $item->quantity);
            }
        });

        // Clear the cart
        if ($isGuest) {
            (new GuestCart())->clear();
        } else {
            auth()->user()->getOrCreateCart()->items()->delete();
        }

        // Send order confirmation email
        try {
            $order->loadMissing('items');
            Mail::to($request->email)->send(new OrderConfirmationMail($order));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Order confirmation email failed: ' . $e->getMessage());
        }

        // Notify authenticated customer via notification channel
        try {
            $order->loadMissing(['user', 'store', 'items.product']);
            if ($order->user) {
                $order->user->notify(new \App\Notifications\OrderPlacedNotification($order));
            }
            // Notify store owner if order has a store
            if ($order->store && $order->store->owner) {
                $order->store->owner->notify(new \App\Notifications\NewOrderNotification($order));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Order placed notification failed: ' . $e->getMessage());
        }

        // Store order token in session so guest can view it
        if ($isGuest) {
            session(['guest_order_id' => $order->id]);
        }

        return redirect()->route('payment.methods', $order);
    }

    /** GET /orders — order history */
    public function index()
    {
        $orders = auth()->user()
            ->orders()
            ->with(['items.product', 'store'])
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    /** GET /orders/{order} — single order detail */
    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load('items.product.category', 'shippingMethod', 'store');

        return view('orders.show', compact('order'));
    }

    /** GET /orders/{order}/track — visual status timeline */
    public function track(Order $order)
    {
        if (auth()->check()) {
            abort_unless($order->user_id === auth()->id(), 403);
        } else {
            abort_unless(session('guest_order_id') === $order->id, 403);
        }

        $order->load('items.product', 'shippingMethod');

        return view('orders.track', compact('order'));
    }

    // ── Private ─────────────────────────────────────────────────

    private function resolveCart()
    {
        if (auth()->check()) {
            $cart = auth()->user()->getOrCreateCart();
            $cart->load('items.product.category', 'items.variant');
            return $cart;
        }

        return new GuestCart();
    }
}
