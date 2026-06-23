<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use App\Notifications\StoreApprovedNotification;
use App\Notifications\StoreRejectedNotification;
use App\Services\Store3DCreditService;
use Illuminate\Http\Request;

class AdminStoreController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'pending');

        $query = Store::with('owner')->withCount('products');

        $query = match($tab) {
            'approved'  => $query->approved(),
            'rejected'  => $query->rejected(),
            'suspended' => $query->suspended(),
            'all'       => $query,
            default     => $query->pending(),
        };

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $stores = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'pending'   => Store::pending()->count(),
            'approved'  => Store::approved()->count(),
            'rejected'  => Store::rejected()->count(),
            'suspended' => Store::suspended()->count(),
            'all'       => Store::count(),
        ];

        $stats = [
            'total'           => Store::count(),
            'pending'         => Store::pending()->count(),
            'active'          => Store::approved()->count(),
            'suspended'       => Store::suspended()->count(),
            'most_visited'    => Store::approved()->mostVisited()->first(),
            'expiring_soon'   => Store::approved()
                ->where('subscription_expires_at', '<=', now()->addDays(7))
                ->where('subscription_expires_at', '>', now())
                ->count(),
            'new_this_week'   => Store::approved()
                ->where('approved_at', '>=', now()->startOfWeek())
                ->count(),
        ];

        return view('admin.stores.index', compact('stores', 'counts', 'stats', 'tab'));
    }

    public function show(Store $store)
    {
        $store->load(['owner', 'products']);

        $storeStats = [
            'total_products'    => $store->products()->count(),
            'approved_products' => $store->products()->where('status', 'approved')->count(),
            'pending_products'  => $store->products()->where('status', 'pending')->count(),
            'visit_count'       => $store->visit_count,
            'last_visited'      => $store->last_visited_at,
            'days_until_expiry' => $store->days_until_expiry,
            'is_expired'        => $store->is_expired,
        ];

        $recentProducts = $store->products()->with('images')->latest()->take(6)->get();

        return view('admin.stores.show', compact('store', 'storeStats', 'recentProducts'));
    }

    public function edit(Store $store)
    {
        return view('admin.stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:100',
            'slug'            => 'required|string|max:100|unique:stores,slug,'.$store->id,
            'tagline'         => 'nullable|string|max:200',
            'description'     => 'nullable|string|max:2000',
            'accent_color'    => 'nullable|string|max:7',
            'category_tags'   => 'nullable|string',
            'logo'            => 'nullable|image|max:2048',
            'banner'          => 'nullable|image|max:4096',
            'admin_notes'     => 'nullable|string|max:1000',
            'social_instagram' => 'nullable|url|max:255',
            'social_facebook'  => 'nullable|url|max:255',
            'social_whatsapp'  => 'nullable|string|max:20',
            'social_website'   => 'nullable|url|max:255',
        ]);

        $data = [
            'name'         => $validated['name'],
            'slug'         => $validated['slug'],
            'tagline'      => $validated['tagline'] ?? null,
            'description'  => $validated['description'] ?? null,
            'accent_color' => $validated['accent_color'] ?? null,
            'admin_notes'  => $validated['admin_notes'] ?? null,
            'category_tags' => $validated['category_tags']
                ? array_filter(array_map('trim', explode(',', $validated['category_tags'])))
                : null,
            'social_links' => array_filter([
                'instagram' => $validated['social_instagram'] ?? null,
                'facebook'  => $validated['social_facebook'] ?? null,
                'whatsapp'  => $validated['social_whatsapp'] ?? null,
                'website'   => $validated['social_website'] ?? null,
            ]),
        ];

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_' . $store->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/stores'), $filename);
            $data['logo_path'] = 'images/stores/' . $filename;
        }

        if ($request->hasFile('banner')) {
            $file = $request->file('banner');
            $filename = 'banner_' . $store->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/stores'), $filename);
            $data['banner_path'] = 'images/stores/' . $filename;
        }

        $store->update($data);

        return redirect()->route('admin.stores.show', $store)->with('success', 'Store updated successfully.');
    }

    public function approve(Request $request, Store $store)
    {
        $request->validate(['admin_notes' => 'nullable|string|max:500']);

        $store->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        $store->owner?->notify(new StoreApprovedNotification($store));

        return back()->with('success', "{$store->name} has been approved.");
    }

    public function reject(Request $request, Store $store)
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);

        $store->update([
            'status'           => 'rejected',
            'rejected_at'      => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        $store->owner?->notify(new StoreRejectedNotification($store, $request->rejection_reason));

        return back()->with('success', "{$store->name} has been rejected.");
    }

    public function suspend(Request $request, Store $store)
    {
        $request->validate(['suspension_reason' => 'required|string|max:500']);

        $store->update([
            'status'            => 'suspended',
            'suspended_at'      => now(),
            'suspension_reason' => $request->suspension_reason,
        ]);

        return back()->with('success', "{$store->name} has been suspended.");
    }

    public function reactivate(Store $store)
    {
        $store->update([
            'status'            => 'approved',
            'suspended_at'      => null,
            'suspension_reason' => null,
        ]);

        return back()->with('success', "{$store->name} has been reactivated.");
    }

    public function setFeatured(Request $request, Store $store)
    {
        $request->validate(['featured_label' => 'nullable|string|max:100']);

        Store::where('is_featured', true)->where('id', '!=', $store->id)
            ->update(['is_featured' => false, 'featured_label' => null]);

        $store->update([
            'is_featured'    => true,
            'featured_label' => $request->featured_label ?? 'Store of the Week',
        ]);

        return back()->with('success', "{$store->name} is now the featured store.");
    }

    public function autoFeature()
    {
        $store = Store::approved()->where('is_featured', false)->mostVisited()->first();

        if ($store) {
            Store::where('is_featured', true)->update(['is_featured' => false, 'featured_label' => null]);
            $store->update(['is_featured' => true, 'featured_label' => 'Store of the Week']);
            return back()->with('success', "Auto-featured: {$store->name}");
        }

        return back()->with('error', 'No eligible store found for auto-feature.');
    }

    public function updateSubscription(Request $request, Store $store)
    {
        $request->validate([
            'plan_type'               => 'required|in:basic,pro,premium',
            'subscription_fee'        => 'required|numeric|min:0',
            'subscription_expires_at' => 'required|date|after:today',
            'commission_rate'         => 'required|numeric|min:0|max:100',
            'subscription_active'     => 'boolean',
        ]);

        $store->update([
            'plan_type'               => $request->plan_type,
            'subscription_fee'        => $request->subscription_fee,
            'subscription_expires_at' => $request->subscription_expires_at,
            'commission_rate'         => $request->commission_rate,
            'subscription_active'     => $request->boolean('subscription_active'),
            'expiry_reminder_sent'    => false,
        ]);

        return back()->with('success', 'Subscription updated successfully.');
    }

    public function storeProducts(Store $store)
    {
        $tab = request('tab', 'pending');

        $products = $store->products()
            ->with('images')
            ->when($tab === 'pending',  fn ($q) => $q->where('status', 'pending'))
            ->when($tab === 'approved', fn ($q) => $q->where('status', 'approved'))
            ->when($tab === 'rejected', fn ($q) => $q->where('status', 'rejected'))
            ->latest()
            ->paginate(12);

        $productCounts = [
            'pending'  => $store->products()->where('status', 'pending')->count(),
            'approved' => $store->products()->where('status', 'approved')->count(),
            'rejected' => $store->products()->where('status', 'rejected')->count(),
        ];

        return view('admin.stores.products', compact('store', 'products', 'productCounts', 'tab'));
    }

    public function approveProduct(Store $store, Product $product)
    {
        abort_if($product->store_id !== $store->id, 403);
        $product->update(['status' => 'approved']);
        $store->increment('products_approved');
        if ($store->products_pending > 0) $store->decrement('products_pending');
        return back()->with('success', 'Product approved.');
    }

    public function rejectProduct(Request $request, Store $store, Product $product)
    {
        abort_if($product->store_id !== $store->id, 403);
        $product->update(['status' => 'rejected', 'rejection_reason' => $request->reason ?? null]);
        if ($store->products_pending > 0) $store->decrement('products_pending');
        return back()->with('success', 'Product rejected.');
    }

    public function removeProduct(Store $store, Product $product)
    {
        abort_if($product->store_id !== $store->id, 403);
        $product->update(['status' => 'rejected']);
        return back()->with('success', 'Product removed from store.');
    }

    public function analytics()
    {
        $analytics = [
            'most_visited' => Store::approved()->withCount('products')->orderByDesc('visit_count')->take(10)->get(),
            'least_visited' => Store::approved()->where('visit_count', '>', 0)->withCount('products')->orderBy('visit_count')->take(10)->get(),
            'never_visited' => Store::approved()->where('visit_count', 0)->count(),
            'new_this_month' => Store::approved()->whereMonth('approved_at', now()->month)->count(),
            'new_this_week' => Store::approved()->where('approved_at', '>=', now()->startOfWeek())->count(),
            'stores_no_products' => Store::approved()->whereDoesntHave('products')->count(),
            'avg_products_per_store' => round(
                Store::approved()->withCount('products')->get()->avg('products_count') ?? 0, 1
            ),
            'active_subscriptions' => Store::approved()->where('subscription_active', true)->count(),
            'expiring_soon' => Store::approved()
                ->where('subscription_expires_at', '<=', now()->addDays(7))
                ->where('subscription_expires_at', '>', now())
                ->get(),
            'expired' => Store::approved()
                ->where('subscription_expires_at', '<', now())
                ->where('subscription_active', true)
                ->count(),
            'plan_breakdown' => Store::approved()
                ->selectRaw('plan_type, COUNT(*) as count')
                ->groupBy('plan_type')
                ->get(),
            'recently_active' => Store::approved()
                ->whereNotNull('last_visited_at')
                ->orderByDesc('last_visited_at')
                ->take(5)->get(),
            'top_categories' => Store::approved()->get()
                ->flatMap(fn ($s) => $s->category_tags ?? [])
                ->countBy()
                ->sortDesc()
                ->take(10),
        ];

        return view('admin.stores.analytics', compact('analytics'));
    }

    public function grantCredits(Request $request, Store $store, Store3DCreditService $credits)
    {
        $request->validate([
            'amount' => ['required', 'integer', 'min:1', 'max:500'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $credits->grantCredits($store, (int) $request->amount, $request->reason ?? '');

        return back()->with('success', "Granted {$request->amount} credits to {$store->name}.");
    }

    public function resetCredits(Store $store, Store3DCreditService $credits)
    {
        $credits->resetUsageCounter($store);

        return back()->with('success', "Usage counter reset for {$store->name}.");
    }
}
