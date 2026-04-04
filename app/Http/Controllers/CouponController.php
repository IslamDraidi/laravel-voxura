<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    // ── Admin: list + create form ──────────────────────────────────
    public function index()
    {
        $coupons = Coupon::latest()->get();

        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:today',
        ]);

        Coupon::create([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'max_uses' => $request->max_uses,
            'expires_at' => $request->expires_at,
            'is_active' => true,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully!');
    }

    public function toggleActive(Coupon $coupon)
    {
        $coupon->update(['is_active' => ! $coupon->is_active]);
        $status = $coupon->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Coupon \"{$coupon->code}\" {$status}.");
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return back()->with('success', "Coupon \"{$coupon->code}\" deleted.");
    }

    // ── Storefront: validate & apply coupon (AJAX/JSON) ──────────
    public function apply(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (! $coupon) {
            return response()->json(['error' => 'Coupon code not found.'], 422);
        }

        $cart = auth()->user()->getOrCreateCart();
        $cart->load('items.product');
        $total = $cart->items->sum(fn ($i) => $i->product->price * $i->quantity);

        if (! $coupon->isValid($total)) {
            if (! $coupon->is_active) {
                return response()->json(['error' => 'This coupon is inactive.'], 422);
            }
            if ($coupon->expires_at && $coupon->expires_at->isPast()) {
                return response()->json(['error' => 'This coupon has expired.'], 422);
            }
            if ($coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses) {
                return response()->json(['error' => 'This coupon has reached its usage limit.'], 422);
            }

            return response()->json(['error' => 'Minimum order amount is $'.number_format($coupon->min_order_amount, 2).'.'], 422);
        }

        $discount = $coupon->calculateDiscount($total);

        return response()->json([
            'success' => true,
            'coupon_id' => $coupon->id,
            'coupon_code' => $coupon->code,
            'discount' => $discount,
            'discount_label' => $coupon->type === 'percentage'
                ? $coupon->value.'% off'
                : '$'.number_format($coupon->value, 2).' off',
            'new_total' => max(0, $total - $discount),
        ]);
    }
}
