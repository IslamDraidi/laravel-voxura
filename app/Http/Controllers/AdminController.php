<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\ShippingMethodRequest;
use App\Http\Requests\Admin\ShippingZoneRequest;
use App\Http\Requests\Admin\TaxRateRequest;
use App\Http\Requests\ProductRequest;
use App\Jobs\Generate3DModelJob;
use App\Models\Banner;
use App\Models\Category;
use App\Models\EmailTemplate;
use App\Models\Feedback;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ShippingMethod;
use App\Models\ShippingZone;
use App\Models\TaxRate;
use App\Models\Refund;
use App\Models\User;
use App\Services\Payment\RefundService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // ── Overall Details ──────────────────────────────────────────────
        $totalSales = Order::whereNotIn('status', ['cancelled'])->sum('total_amount');
        $totalOrders = Order::count();
        $totalCustomers = User::where('is_admin', false)->count();
        $avgOrderSale = Order::whereNotIn('status', ['cancelled'])->avg('total_amount') ?? 0;
        $unpaidInvoices = Order::where('status', 'pending')->count();

        // ── Today's Details ───────────────────────────────────────────────
        $todaySales = Order::whereDate('created_at', $today)
            ->whereNotIn('status', ['cancelled'])->sum('total_amount');
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $todayCustomers = User::where('is_admin', false)->whereDate('created_at', $today)->count();

        // ── Top Selling Products (by units sold) ─────────────────────────
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNotIn('orders.status', ['cancelled'])
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.quantity * order_items.price) as revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // ── Customers with Most Sales ─────────────────────────────────────
        $topCustomers = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->whereNotIn('orders.status', ['cancelled'])
            ->select(
                'users.id',
                'users.name',
                DB::raw('COUNT(orders.id) as order_count'),
                DB::raw('SUM(orders.total_amount) as total_spent')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSales', 'totalOrders', 'totalCustomers', 'avgOrderSale', 'unpaidInvoices',
            'todaySales', 'todayOrders', 'todayCustomers',
            'topProducts', 'topCustomers'
        ));
    }

    public function products(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('stock', '>', 0);
            } elseif ($request->status === 'out') {
                $query->where('stock', 0);
            }
        }

        $products   = $query->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $validated = $request->validated();

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = $this->saveUploadedImage($request->file('image'));
        }

        unset($validated['image'], $validated['gallery'], $validated['gallery.*'], $validated['size_guide_rows'], $validated['color_swatches_rows'], $validated['model3d'], $validated['remove_3d_model']);

        $product = Product::create(array_merge($validated, [
            'slug' => $this->uniqueSlug($request->name),
            'image' => $imageName,
        ]));

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $i => $file) {
                $name = $this->saveUploadedImage($file, 'g'.$i);
                ProductImage::create(['product_id' => $product->id, 'image' => $name, 'sort_order' => $i]);
            }
        }

        if ($request->hasFile('model3d')) {
            $modelFile = $request->file('model3d');
            $modelName = 'model.' . $modelFile->getClientOriginalExtension();
            $modelFile->storeAs("public/models/{$product->id}", $modelName);
            $product->update(['model3d_path' => $modelName, 'has_3d_model' => true, 'model3d_status' => 'ready', 'model3d_generated_at' => now()]);
        } else {
            $this->maybeDispatch3DGeneration($product);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Product created successfully!', 'redirect' => '/admin/products']);
        }

        return redirect('/admin')->with('success', 'Product added successfully!');
    }

    public function edit($id)
    {
        $product = Product::withTrashed()->with('images', 'variants')->findOrFail($id);
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, $id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $product->image = $this->saveUploadedImage($request->file('image'));
        }

        unset($validated['image'], $validated['gallery'], $validated['gallery.*'], $validated['remove_images'], $validated['size_guide_rows'], $validated['color_swatches_rows'], $validated['model3d'], $validated['remove_3d_model']);

        $product->update(array_merge($validated, [
            'slug' => $this->uniqueSlug($request->name, $product->id),
            'image' => $product->image,
        ]));

        // Handle 3D model removal
        if ($request->boolean('remove_3d_model') && $product->model3d_path) {
            Storage::delete("public/models/{$product->id}/{$product->model3d_path}");
            $product->update(['model3d_path' => null, 'has_3d_model' => false]);
        }

        // Handle 3D model upload
        if ($request->hasFile('model3d')) {
            if ($product->model3d_path) {
                Storage::delete("public/models/{$product->id}/{$product->model3d_path}");
            }
            $modelFile = $request->file('model3d');
            $modelName = 'model.' . $modelFile->getClientOriginalExtension();
            $modelFile->storeAs("public/models/{$product->id}", $modelName);
            $product->update(['model3d_path' => $modelName, 'has_3d_model' => true, 'model3d_status' => 'ready', 'model3d_generated_at' => now()]);
        }

        $galleryUploaded = false;
        if ($request->hasFile('gallery')) {
            $sortStart = $product->images()->max('sort_order') + 1;
            foreach ($request->file('gallery') as $i => $file) {
                $name = $this->saveUploadedImage($file, 'g'.$i);
                ProductImage::create(['product_id' => $product->id, 'image' => $name, 'sort_order' => $sortStart + $i]);
                $galleryUploaded = true;
            }
        }

        if ($galleryUploaded && ! $request->hasFile('model3d')) {
            $product->update([
                'has_3d_model'  => false,
                'model3d_error' => null,
            ]);
            $this->maybeDispatch3DGeneration($product);
        }

        // Remove individual gallery images if requested
        if ($request->filled('remove_images')) {
            foreach ($request->remove_images as $imgId) {
                $img = ProductImage::where('id', $imgId)->where('product_id', $product->id)->first();
                if ($img) {
                    $path = public_path('images/'.$img->image);
                    if (file_exists($path)) {
                        unlink($path);
                    }
                    $img->delete();
                }
            }
        }

        $redirectTo = $request->input('redirect_to') === 'archive' ? '/admin/archive' : '/admin';

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Product updated successfully!']);
        }

        return redirect($redirectTo)->with('success', 'Product updated successfully!');
    }

    public function destroy(Request $request, Product $product)
    {
        $product->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Product moved to archive.']);
        }

        return redirect('/admin')->with('success', 'Product moved to archive.');
    }

    public function archive()
    {
        $products = Product::onlyTrashed()->with('category')->get();

        return view('admin.archive', compact('products'));
    }

    public function restore($id)
    {
        Product::withTrashed()->findOrFail($id)->restore();

        return redirect('/admin/archive')->with('success', 'Product restored successfully!');
    }

    public function forceDelete($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $imagePath = public_path('images/'.$product->image);
        if ($product->image && file_exists($imagePath)) {
            unlink($imagePath);
        }
        $product->forceDelete();

        return redirect('/admin/archive')->with('success', 'Product permanently deleted.');
    }

    public function orders(Request $request)
    {
        $query = Order::with('user', 'items.product')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $orders = $query->get();

        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'paid' => Order::where('status', 'paid')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'payment_blocked' => Order::where('status', 'payment_blocked')->count(),
            'refunded' => Order::where('status', 'refunded')->count(),
            'partially_refunded' => Order::where('status', 'partially_refunded')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,delivered,cancelled,payment_blocked,refunded,partially_refunded',
        ]);

        $order->update(['status' => $request->status]);

        $this->sendOrderStatusEmail($order, $request->status);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => "Order #{$order->id} status updated.", 'status' => $request->status]);
        }

        return back()->with('success', "Order #{$order->id} status updated to ".ucfirst($request->status).'.');
    }

    private function sendOrderStatusEmail(Order $order, string $status): void
    {
        $keyMap = [
            'shipped'   => 'shipping_notification',
            'cancelled' => 'order_cancelled',
        ];

        if (! isset($keyMap[$status])) {
            return;
        }

        $template = \App\Models\EmailTemplate::where('key', $keyMap[$status])->first();
        if (! $template) {
            return;
        }

        $order->loadMissing('user');
        $recipientEmail = $order->recipientEmail();
        if (! $recipientEmail) {
            return;
        }

        $vars = [
            'order_id'      => $order->id,
            'customer_name' => $order->recipientName(),
            'order_total'   => number_format((float) $order->grand_total, 2),
        ];

        \Illuminate\Support\Facades\Mail::to($recipientEmail)
            ->send(new \App\Mail\TemplateMail($template, $vars));
    }

    public function showOrder(Order $order)
    {
        $order->load(['user', 'items.product', 'payments', 'refunds.admin', 'shippingMethod', 'coupon']);

        $refundableAmount = (new RefundService)->getRefundableAmount($order);

        return view('admin.orders.show', compact('order', 'refundableAmount'));
    }

    // ── Product Variants ─────────────────────────────────────────

    public function addVariant(Request $request, Product $product)
    {
        $request->validate([
            'type' => 'required|string|max:50',
            'value' => 'required|string|max:100',
            'price_modifier' => 'required|numeric',
            'stock' => 'nullable|integer|min:0',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'type' => ucfirst(trim($request->type)),
            'value' => trim($request->value),
            'price_modifier' => $request->price_modifier,
            'stock' => $request->filled('stock') ? $request->stock : null,
        ]);

        return back()->with('success', 'Variant added.');
    }

    public function removeVariant(Product $product, ProductVariant $variant)
    {
        abort_unless($variant->product_id === $product->id, 404);
        $variant->delete();

        return back()->with('success', 'Variant removed.');
    }

    // ── Customer Management ──────────────────────────────────────

    public function customers(Request $request)
    {
        $base = User::withCount('orders')->withSum('orders', 'total_amount');

        if ($request->filled('search')) {
            $base->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'blocked') {
                $base->where('is_blocked', true);
            }
            if ($request->status === 'active') {
                $base->where('is_blocked', false);
            }
        }

        $all       = $base->latest()->get();
        $admins    = $all->filter(fn($u) => $u->isAdmin())->values();
        $customers = $all->reject(fn($u) => $u->isAdmin())->values();

        return view('admin.customers.index', compact('admins', 'customers'));
    }

    public function blockCustomer(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'You cannot block yourself.'], 422);
            }

            return back()->with('error', 'You cannot block yourself.');
        }
        $user->update(['is_blocked' => true]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => "{$user->name} has been blocked."]);
        }

        return back()->with('success', "{$user->name} has been blocked.");
    }

    public function unblockCustomer(Request $request, User $user)
    {
        $user->update(['is_blocked' => false]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => "{$user->name} has been unblocked."]);
        }

        return back()->with('success', "{$user->name} has been unblocked.");
    }

    // ── Inventory Management ─────────────────────────────────────

    public function inventory(Request $request)
    {
        $query = Product::with('category')->whereNull('deleted_at');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('stock')) {
            if ($request->stock === 'low') {
                $query->where('stock', '<=', 10)->where('stock', '>', 0);
            }
            if ($request->stock === 'out') {
                $query->where('stock', 0);
            }
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->orderBy('stock')->get();
        $categories = Category::all();
        $lowCount = Product::whereNull('deleted_at')->where('stock', '<=', 10)->where('stock', '>', 0)->count();
        $outCount = Product::whereNull('deleted_at')->where('stock', 0)->count();

        return view('admin.inventory.index', compact('products', 'categories', 'lowCount', 'outCount'));
    }

    public function bulkStockUpdate(Request $request)
    {
        $request->validate(['stock' => 'required|array', 'stock.*' => 'integer|min:0']);

        foreach ($request->stock as $productId => $qty) {
            Product::whereNull('deleted_at')->where('id', $productId)->update(['stock' => $qty]);
        }

        return back()->with('success', 'Stock updated for '.count($request->stock).' product(s).');
    }

    // ── Banner / Slider Management ───────────────────────────────

    public function banners()
    {
        $banners = Banner::orderBy('sort_order')->get();

        return view('admin.banners.index', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = $this->saveUploadedImage($request->file('image'), 'banner');
        }

        Banner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'image' => $imageName,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'Banner added successfully.');
    }

    public function toggleBanner(Banner $banner)
    {
        $banner->update(['is_active' => ! $banner->is_active]);

        return back()->with('success', 'Banner '.($banner->is_active ? 'activated' : 'deactivated').'.');
    }

    public function destroyBanner(Banner $banner)
    {
        if ($banner->image) {
            $path = public_path('images/'.$banner->image);
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $banner->delete();

        return back()->with('success', 'Banner deleted.');
    }

    public function reorderBanners(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer']);
        foreach ($request->order as $sort => $id) {
            Banner::where('id', $id)->update(['sort_order' => $sort]);
        }

        return response()->json(['ok' => true]);
    }

    // ── Tax Rate Management ──────────────────────────────────────

    public function taxRates()
    {
        $rates = TaxRate::with('category')->orderBy('priority')->orderBy('name')->get();

        return view('admin.tax.index', compact('rates'));
    }

    public function createTaxRate()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.tax.create', compact('categories'));
    }

    public function storeTaxRate(TaxRateRequest $request)
    {

        $translations = $this->buildTranslations($request);

        TaxRate::create([
            'name' => $request->name,
            'name_translations' => $translations,
            'rate' => $request->rate,
            'type' => $request->type,
            'scope' => $request->scope,
            'category_id' => $request->scope === 'category' ? $request->category_id : null,
            'country' => $request->country ? strtoupper($request->country) : null,
            'region' => $request->region,
            'postal_code_pattern' => $request->postal_code_pattern,
            'channel' => $request->channel ?: null,
            'priority' => $request->priority ?? 0,
            'is_inclusive' => $request->boolean('is_inclusive'),
            'apply_to_shipping' => $request->boolean('apply_to_shipping'),
            'valid_from' => $request->valid_from,
            'valid_to' => $request->valid_to,
            'is_active' => true,
        ]);

        return redirect('/admin/tax')->with('success', 'Tax rate added.');
    }

    public function editTaxRate(TaxRate $rate)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.tax.edit', compact('rate', 'categories'));
    }

    public function updateTaxRate(TaxRateRequest $request, TaxRate $rate)
    {

        $translations = $this->buildTranslations($request);

        $rate->update([
            'name' => $request->name,
            'name_translations' => $translations,
            'rate' => $request->rate,
            'type' => $request->type,
            'scope' => $request->scope,
            'category_id' => $request->scope === 'category' ? $request->category_id : null,
            'country' => $request->country ? strtoupper($request->country) : null,
            'region' => $request->region,
            'postal_code_pattern' => $request->postal_code_pattern,
            'channel' => $request->channel ?: null,
            'priority' => $request->priority ?? 0,
            'is_inclusive' => $request->boolean('is_inclusive'),
            'apply_to_shipping' => $request->boolean('apply_to_shipping'),
            'valid_from' => $request->valid_from,
            'valid_to' => $request->valid_to,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Tax rate updated.']);
        }

        return redirect('/admin/tax')->with('success', 'Tax rate updated.');
    }

    public function toggleTaxRate(TaxRate $rate)
    {
        $rate->update(['is_active' => ! $rate->is_active]);

        return back()->with('success', 'Tax rate '.($rate->is_active ? 'activated' : 'deactivated').'.');
    }

    public function destroyTaxRate(TaxRate $rate)
    {
        $rate->delete();

        return back()->with('success', 'Tax rate deleted.');
    }

    // ── Shipping Method Management ───────────────────────────────

    public function shippingMethods()
    {
        $methods = ShippingMethod::withCount('zones')->orderBy('sort_order')->get();

        return view('admin.shipping.index', compact('methods'));
    }

    public function createShippingMethod()
    {
        return view('admin.shipping.create');
    }

    public function storeShippingMethod(ShippingMethodRequest $request)
    {

        $translations = $this->buildTranslations($request);

        ShippingMethod::create([
            'name' => $request->name,
            'name_translations' => $translations,
            'type' => $request->type,
            'price' => $request->base_rate,
            'base_rate' => $request->base_rate,
            'per_unit_rate' => $request->per_unit_rate,
            'weight_rate' => $request->weight_rate,
            'weight_unit' => $request->weight_unit ?? 'kg',
            'free_above' => $request->free_above,
            'min_order_amount' => $request->min_order_amount,
            'max_order_amount' => $request->max_order_amount,
            'max_weight' => $request->max_weight,
            'channel' => $request->channel ?: null,
            'estimated_days_min' => $request->estimated_days_min,
            'estimated_days_max' => $request->estimated_days_max,
            'sort_order' => $request->sort_order ?? 0,
            'metadata' => $request->metadata ? json_decode($request->metadata, true) : null,
            'is_active' => true,
        ]);

        return redirect('/admin/shipping/methods')->with('success', 'Shipping method added.');
    }

    public function editShippingMethod(ShippingMethod $method)
    {
        return view('admin.shipping.edit', compact('method'));
    }

    public function updateShippingMethod(ShippingMethodRequest $request, ShippingMethod $method)
    {

        $translations = $this->buildTranslations($request);

        $method->update([
            'name' => $request->name,
            'name_translations' => $translations,
            'type' => $request->type,
            'price' => $request->base_rate,
            'base_rate' => $request->base_rate,
            'per_unit_rate' => $request->per_unit_rate,
            'weight_rate' => $request->weight_rate,
            'weight_unit' => $request->weight_unit ?? 'kg',
            'free_above' => $request->free_above,
            'min_order_amount' => $request->min_order_amount,
            'max_order_amount' => $request->max_order_amount,
            'max_weight' => $request->max_weight,
            'channel' => $request->channel ?: null,
            'estimated_days_min' => $request->estimated_days_min,
            'estimated_days_max' => $request->estimated_days_max,
            'sort_order' => $request->sort_order ?? 0,
            'metadata' => $request->metadata ? json_decode($request->metadata, true) : null,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Shipping method updated.']);
        }

        return redirect('/admin/shipping/methods')->with('success', 'Shipping method updated.');
    }

    public function toggleShippingMethod(ShippingMethod $method)
    {
        $method->update(['is_active' => ! $method->is_active]);

        return back()->with('success', 'Shipping method '.($method->is_active ? 'activated' : 'deactivated').'.');
    }

    public function destroyShippingMethod(ShippingMethod $method)
    {
        $method->delete();

        return redirect('/admin/shipping/methods')->with('success', 'Shipping method deleted.');
    }

    // ── Shipping Zone Management ─────────────────────────────────

    public function shippingZones()
    {
        $zones = ShippingZone::withCount('methods')->orderBy('name')->get();

        return view('admin.shipping.zones.index', compact('zones'));
    }

    public function createShippingZone()
    {
        $methods = ShippingMethod::active()->ordered()->get();

        return view('admin.shipping.zones.create', compact('methods'));
    }

    public function storeShippingZone(ShippingZoneRequest $request)
    {

        $zone = ShippingZone::create([
            'name' => $request->name,
            'countries' => array_map('strtoupper', $request->countries),
            'regions' => $request->regions ? array_map('trim', explode(',', $request->regions)) : null,
            'is_active' => true,
        ]);

        if ($request->methods) {
            $zone->methods()->attach($this->buildZoneMethodPivot($request));
        }

        return redirect('/admin/shipping/zones')->with('success', 'Shipping zone created.');
    }

    public function editShippingZone(ShippingZone $zone)
    {
        $zone->load('methods');
        $methods = ShippingMethod::active()->ordered()->get();

        return view('admin.shipping.zones.edit', compact('zone', 'methods'));
    }

    public function updateShippingZone(ShippingZoneRequest $request, ShippingZone $zone)
    {

        $zone->update([
            'name' => $request->name,
            'countries' => array_map('strtoupper', $request->countries),
            'regions' => $request->regions ? array_map('trim', explode(',', $request->regions)) : null,
        ]);

        $zone->methods()->sync($this->buildZoneMethodPivot($request));

        return redirect('/admin/shipping/zones')->with('success', 'Shipping zone updated.');
    }

    public function toggleShippingZone(ShippingZone $zone)
    {
        $zone->update(['is_active' => ! $zone->is_active]);

        return back()->with('success', 'Shipping zone '.($zone->is_active ? 'activated' : 'deactivated').'.');
    }

    public function destroyShippingZone(ShippingZone $zone)
    {
        $zone->methods()->detach();
        $zone->delete();

        return redirect('/admin/shipping/zones')->with('success', 'Shipping zone deleted.');
    }

    // ── Email Templates ──────────────────────────────────────────

    public function emailTemplates()
    {
        $templates = EmailTemplate::orderBy('name')->get();

        return view('admin.email-templates.index', compact('templates'));
    }

    public function updateEmailTemplate(Request $request, EmailTemplate $template)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'variables' => 'nullable|string|max:500',
        ]);

        $template->update([
            'subject' => $request->subject,
            'body' => $request->body,
            'variables' => $request->variables,
        ]);

        return back()->with('success', '"'.$template->name.'" template updated successfully.');
    }

    public function previewEmailTemplate(EmailTemplate $template)
    {
        // Render preview with placeholder values for each variable
        $vars = [];
        foreach ($template->variableList() as $var) {
            $vars[$var] = '<span style="background:#fff3cd;padding:0 3px;border-radius:3px;">{{'.$var.'}}</span>';
        }
        $html = $template->renderBody($vars);

        return response($html);
    }

    // ── Sales Reports ─────────────────────────────────────────────

    public function reports(Request $request)
    {
        $period = $request->get('period', '30');

        $days = match ($period) {
            '7' => 7,
            '90' => 90,
            '365' => 365,
            default => 30,
        };

        $from = now()->subDays($days)->startOfDay();

        $revenue = Order::where('created_at', '>=', $from)->where('status', '!=', 'cancelled')->sum('total_amount');
        $ordersCount = Order::where('created_at', '>=', $from)->count();
        $newCustomers = User::where('is_admin', false)->where('created_at', '>=', $from)->count();
        $avgOrder = $ordersCount > 0 ? $revenue / $ordersCount : 0;

        // Daily revenue for chart (last N days)
        $dailyData = Order::where('created_at', '>=', $from)
            ->where('status', '!=', 'cancelled')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartLabels = [];
        $chartRevenue = [];
        $chartOrders = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('M d');
            $chartRevenue[] = $dailyData->get($date)?->total ?? 0;
            $chartOrders[] = $dailyData->get($date)?->count ?? 0;
        }

        $topProducts = \DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', $from)
            ->where('orders.status', '!=', 'cancelled')
            ->selectRaw('products.name, SUM(order_items.quantity) as total_qty, SUM(order_items.quantity * order_items.price) as total_revenue')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        $statusBreakdown = Order::where('created_at', '>=', $from)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Refund & payment failure stats
        $refundStats = [
            'count' => Refund::where('created_at', '>=', $from)->where('status', 'completed')->count(),
            'amount' => Refund::where('created_at', '>=', $from)->where('status', 'completed')->sum('amount'),
            'rate' => $ordersCount > 0
                ? round(Refund::where('created_at', '>=', $from)->where('status', 'completed')->count() / $ordersCount * 100, 1)
                : 0,
            'failed_payments' => DB::table('payments')->where('created_at', '>=', $from)->where('status', 'failed')->count(),
            'total_payments' => DB::table('payments')->where('created_at', '>=', $from)->count(),
            'top_reasons' => Refund::where('created_at', '>=', $from)->where('status', 'completed')
                ->selectRaw('reason, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('reason')->orderByDesc('count')->limit(5)->get(),
        ];
        $refundStats['failed_rate'] = $refundStats['total_payments'] > 0
            ? round($refundStats['failed_payments'] / $refundStats['total_payments'] * 100, 1)
            : 0;

        return view('admin.reports.index', compact(
            'period', 'revenue', 'ordersCount', 'newCustomers', 'avgOrder',
            'chartLabels', 'chartRevenue', 'chartOrders',
            'topProducts', 'statusBreakdown', 'refundStats'
        ));
    }

    // ── Shipments ─────────────────────────────────────────────────

    public function shipments(Request $request)
    {
        $query = Order::with(['user', 'shippingMethod'])
            ->whereIn('status', ['processing', 'shipped', 'delivered']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%'));
        }

        $shipments = $query->latest()->get();

        $counts = [
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
        ];

        return view('admin.sales.shipments', compact('shipments', 'counts'));
    }

    // ── Invoices ──────────────────────────────────────────────────

    public function invoices(Request $request)
    {
        $query = Order::with(['user', 'items'])
            ->whereIn('status', ['pending', 'processing', 'shipped', 'delivered']);

        if ($request->filled('search')) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%'));
        }

        $invoices = $query->latest()->get();

        return view('admin.sales.invoices', compact('invoices'));
    }

    // ── Refunds ───────────────────────────────────────────────────

    public function refunds()
    {
        $refunds = Refund::with(['order.user', 'admin', 'payment'])->latest()->get();

        $stats = [
            'total_count' => $refunds->count(),
            'completed_count' => $refunds->where('status', 'completed')->count(),
            'failed_count' => $refunds->where('status', 'failed')->count(),
            'total_amount' => $refunds->where('status', 'completed')->sum('amount'),
        ];

        return view('admin.sales.refunds', compact('refunds', 'stats'));
    }

    // ── Transactions ──────────────────────────────────────────────

    public function transactions(Request $request)
    {
        $query = DB::table('payments')
            ->join('orders', 'payments.order_id', '=', 'orders.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('payments.*', 'users.name as customer_name', 'users.email as customer_email');

        if ($request->filled('status')) {
            $query->where('payments.status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(fn ($q) => $q->where('users.name', 'like', '%'.$request->search.'%')
                ->orWhere('payments.transaction_id', 'like', '%'.$request->search.'%'));
        }

        $transactions = $query->orderByDesc('payments.created_at')->get();

        $stats = [
            'total' => DB::table('payments')->sum('amount'),
            'completed' => DB::table('payments')->where('status', 'completed')->sum('amount'),
            'refunded' => DB::table('payments')->where('status', 'refunded')->sum('amount'),
            'pending' => DB::table('payments')->where('status', 'pending')->count(),
        ];

        return view('admin.sales.transactions', compact('transactions', 'stats'));
    }

    // ── RMA ───────────────────────────────────────────────────────

    public function rma()
    {
        return view('admin.sales.rma');
    }

    // ── Catalog — Attributes ──────────────────────────────────────

    public function attributes()
    {
        return view('admin.catalog.attributes');
    }

    public function attributeFamilies()
    {
        return view('admin.catalog.attribute-families');
    }

    // ── Customer — Reviews ────────────────────────────────────────

    public function reviews(Request $request)
    {
        $query = Feedback::with(['user', 'product'])->latest();

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        if ($request->filled('search')) {
            $query->where(fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', '%'.$request->search.'%'))
                ->orWhereHas('product', fn ($p) => $p->where('name', 'like', '%'.$request->search.'%')));
        }

        $reviews = $query->get();

        $stats = [
            'total' => Feedback::count(),
            'avg' => round(Feedback::avg('rating'), 1),
            'stars5' => Feedback::where('rating', 5)->count(),
            'stars1' => Feedback::where('rating', 1)->count(),
        ];

        return view('admin.customers.reviews', compact('reviews', 'stats'));
    }

    public function deleteReview(Feedback $review)
    {
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }

    // ── Customer Groups ───────────────────────────────────────────

    public function customerGroups()
    {
        return view('admin.customers.groups');
    }

    // ── GDPR ─────────────────────────────────────────────────────

    public function gdpr()
    {
        $users = User::where('is_admin', false)
            ->withCount('orders')
            ->latest()
            ->get();

        return view('admin.customers.gdpr', compact('users'));
    }

    // ── Marketing — Communications ────────────────────────────────

    public function communications()
    {
        return view('admin.marketing.communications');
    }

    // ── Marketing — SEO ───────────────────────────────────────────

    public function seo(Request $request)
    {
        $query = Product::with('category')->whereNull('deleted_at');

        if ($request->filled('search')) {
            $query->where(fn ($q) => $q->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('slug', 'like', '%'.$request->search.'%'));
        }

        $products = $query->orderBy('name')->get();
        $categories = Category::all();

        return view('admin.marketing.seo', compact('products', 'categories'));
    }

    // ── Reporting — Customer Reports ──────────────────────────────

    public function customerReports(Request $request)
    {
        $period = $request->get('period', '30');
        $days = match ($period) {
            '7' => 7, '90' => 90, '365' => 365, default => 30
        };
        $from = now()->subDays($days)->startOfDay();

        $newCustomers = User::where('is_admin', false)->where('created_at', '>=', $from)->count();
        $totalCustomers = User::where('is_admin', false)->count();
        $activeCustomers = User::where('is_admin', false)
            ->whereHas('orders', fn ($q) => $q->where('created_at', '>=', $from))->count();

        $topCustomers = User::where('is_admin', false)
            ->withCount('orders')
            ->withSum('orders', 'total_amount')
            ->having('orders_count', '>', 0)
            ->orderByDesc('orders_sum_total_amount')
            ->limit(10)
            ->get();

        $signupData = User::where('is_admin', false)
            ->where('created_at', '>=', $from)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartLabels = [];
        $chartSignups = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('M d');
            $chartSignups[] = $signupData->get($date)?->count ?? 0;
        }

        return view('admin.reports.customers', compact(
            'period', 'newCustomers', 'totalCustomers', 'activeCustomers',
            'topCustomers', 'chartLabels', 'chartSignups'
        ));
    }

    // ── Reporting — Product Reports ───────────────────────────────

    public function productReports(Request $request)
    {
        $period = $request->get('period', '30');
        $days = match ($period) {
            '7' => 7, '90' => 90, '365' => 365, default => 30
        };
        $from = now()->subDays($days)->startOfDay();

        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.created_at', '>=', $from)
            ->where('orders.status', '!=', 'cancelled')
            ->selectRaw('products.id, products.name, products.image, categories.name as category,
                SUM(order_items.quantity) as total_qty,
                SUM(order_items.quantity * order_items.price) as total_revenue')
            ->groupBy('products.id', 'products.name', 'products.image', 'categories.name')
            ->orderByDesc('total_revenue')
            ->limit(20)
            ->get();

        $revenueByCategory = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.created_at', '>=', $from)
            ->where('orders.status', '!=', 'cancelled')
            ->selectRaw('categories.name, SUM(order_items.quantity * order_items.price) as revenue')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->get();

        $totalRevenue = $topProducts->sum('total_revenue');
        $totalUnitsSold = $topProducts->sum('total_qty');

        $catLabels = $revenueByCategory->pluck('name');
        $catRevenues = $revenueByCategory->pluck('revenue');

        return view('admin.reports.products', compact(
            'period', 'topProducts', 'revenueByCategory',
            'totalRevenue', 'totalUnitsSold', 'catLabels', 'catRevenues'
        ));
    }

    // ── Private Helpers ──────────────────────────────────────────

    private function buildTranslations(Request $request): array
    {
        return array_filter([
            'en' => $request->name_en ?: $request->name,
            'ar' => $request->name_ar,
        ]);
    }

    // ── 3D Model Generation ──────────────────────────────────────

    public function get3DStatus(Product $product): JsonResponse
    {
        return response()->json([
            'status'                 => $product->model3d_status,
            'has_3d_model'           => (bool) $product->has_3d_model,
            'model3d_queued_at'      => optional($product->model3d_queued_at)->toDateTimeString(),
            'model3d_generated_at'   => optional($product->model3d_generated_at)->toDateTimeString(),
            'model3d_error'          => $product->model3d_error,
            'model3d_selected_image' => $product->model3d_selected_image
                ? asset(str_replace(public_path().'/', '', $product->model3d_selected_image))
                : null,
            'model_url' => $product->is3DReady() ? $product->get3DModelUrl() : null,
        ]);
    }

    public function regenerate3D(Product $product): JsonResponse
    {
        if (! config('model3d.enabled')) {
            return response()->json(['success' => false, 'message' => '3D generation is disabled.'], 400);
        }

        if ($product->model3d_status === 'processing') {
            return response()->json(['success' => false, 'message' => 'Generation already in progress.'], 409);
        }

        if (! $product->images()->exists()) {
            return response()->json(['success' => false, 'message' => 'Product has no images.'], 400);
        }

        $product->update([
            'model3d_status'    => 'queued',
            'model3d_queued_at' => now(),
            'model3d_error'     => null,
            'has_3d_model'      => false,
        ]);

        Generate3DModelJob::dispatch($product)->delay(now()->addSeconds(3));

        return response()->json(['success' => true, 'message' => 'Regeneration queued']);
    }

    private function saveUploadedImage(\Illuminate\Http\UploadedFile $file, string $prefix = ''): string
    {
        if (! $file->isValid()) {
            throw new \RuntimeException('Uploaded file is invalid: '.$file->getErrorMessage());
        }

        $dir = public_path('images');
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        if (! is_writable($dir)) {
            throw new \RuntimeException("Upload directory not writable: {$dir}");
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $name = ($prefix ? $prefix.'_' : '').time().'_'.bin2hex(random_bytes(4)).'.'.$ext;

        $file->move($dir, $name);

        return $name;
    }

    private function uniqueSlug(string $name, ?int $excludeId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;
        while (Product::withTrashed()->where('slug', $slug)->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }

    private function maybeDispatch3DGeneration(Product $product): void
    {
        try {
            if (! config('model3d.enabled')) {
                return;
            }
            if (! config('model3d.hf_token')) {
                Log::critical('Skipping 3D generation: HF_API_TOKEN not configured.');
                return;
            }
            if (! $product->images()->exists()) {
                Log::info("Skipping 3D generation for product {$product->id}: no images.");
                return;
            }
            if (in_array($product->model3d_status, ['queued', 'processing'], true)) {
                return;
            }

            $product->update([
                'model3d_status'    => 'queued',
                'model3d_queued_at' => now(),
                'model3d_error'     => null,
            ]);

            Generate3DModelJob::dispatch($product)->delay(now()->addSeconds(3));
        } catch (\Throwable $e) {
            Log::error('Failed to dispatch 3D generation job', [
                'product_id' => $product->id,
                'error'      => $e->getMessage(),
            ]);
        }
    }

    private function buildZoneMethodPivot(Request $request): array
    {
        $pivot = [];
        foreach (($request->methods ?? []) as $methodId) {
            $pivot[$methodId] = [
                'rate_override' => $request->rate_overrides[$methodId] ?? null,
                'is_active' => true,
            ];
        }

        return $pivot;
    }

}