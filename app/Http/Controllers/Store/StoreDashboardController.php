<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreDashboardController extends Controller
{
    private function getStore(): Store
    {
        return view()->shared('currentStore') ?? auth()->user()->store;
    }

    private function getDateRange(string $range): array
    {
        return match ($range) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'week'  => [now()->startOfWeek(), now()->endOfWeek()],
            'year'  => [now()->startOfYear(), now()->endOfYear()],
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    // ── OVERVIEW ───────────────────────────────────────────────────────────

    public function overview(Request $request)
    {
        $store = $this->getStore();
        abort_if(!$store, 403);

        $range = $request->get('range', 'month');
        [$from, $to] = $this->getDateRange($range);

        $data = $this->getOverviewData($store, $range);

        return view('store.dashboard.overview', array_merge($data, compact('store', 'range')));
    }

    private function getOverviewData(Store $store, string $range): array
    {
        [$from, $to] = $this->getDateRange($range);

        $baseOrders = Order::whereHas('items.product', fn ($q) => $q->where('store_id', $store->id))
            ->whereBetween('created_at', [$from, $to]);

        $totalRevenue = (clone $baseOrders)
            ->where('status', '!=', 'cancelled')
            ->sum('grand_total');

        $totalOrders = (clone $baseOrders)->count();

        $commission   = (float) ($store->commission_rate ?? 0) / 100;
        $netProfit    = $totalRevenue * (1 - $commission);
        $aov          = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $visits         = (int) ($store->visit_count ?? 0);
        $conversionRate = $visits > 0 ? ($totalOrders / $visits) * 100 : 0;

        $chartData = Order::whereHas('items.product', fn ($q) => $q->where('store_id', $store->id))
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $orderStatuses = Order::whereHas('items.product', fn ($q) => $q->where('store_id', $store->id))
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('products.store_id', $store->id)
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$from, $to])
            ->selectRaw('products.id, products.name, SUM(order_items.quantity) as units_sold, SUM(order_items.quantity * order_items.price) as revenue')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        $periodLength = $from->diffInDays($to) + 1;
        $prevFrom     = $from->copy()->subDays($periodLength);
        $prevTo       = $from->copy()->subDay();

        $prevRevenue = Order::whereHas('items.product', fn ($q) => $q->where('store_id', $store->id))
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$prevFrom, $prevTo])
            ->sum('grand_total');

        $revenueChange = $prevRevenue > 0 ? (($totalRevenue - $prevRevenue) / $prevRevenue) * 100 : 0;

        $creditBalance   = $store->credits_balance ?? 0;
        $creditMonthly   = $store->monthlyCredits();
        $creditUsedTotal = $store->credits_used_total ?? 0;
        $has3DAccess     = $store->has3DAccess();
        $products3DCount   = $store->products()->where('model3d_status', 'ready')->count();
        $products3DPending = $store->products()->whereIn('model3d_status', ['queued', 'processing'])->count();

        return compact(
            'totalRevenue', 'netProfit', 'totalOrders', 'aov',
            'conversionRate', 'chartData', 'orderStatuses',
            'topProducts', 'revenueChange',
            'creditBalance', 'creditMonthly', 'creditUsedTotal',
            'has3DAccess', 'products3DCount', 'products3DPending'
        );
    }

    // ── ORDERS ─────────────────────────────────────────────────────────────

    public function orders(Request $request)
    {
        $store  = $this->getStore();
        $range  = $request->get('range', 'month');
        $status = $request->get('status', 'all');
        [$from, $to] = $this->getDateRange($range);

        $data = $this->getOrdersData($store, $range, $status);

        return view('store.dashboard.orders', array_merge($data, compact('store', 'range', 'status')));
    }

    private function getOrdersData(Store $store, string $range, string $status = 'all'): array
    {
        [$from, $to] = $this->getDateRange($range);

        $query = Order::whereHas('items.product', fn ($q) => $q->where('store_id', $store->id))
            ->with(['user', 'items.product'])
            ->whereBetween('created_at', [$from, $to]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        $statusCounts = Order::whereHas('items.product', fn ($q) => $q->where('store_id', $store->id))
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return compact('orders', 'statusCounts');
    }

    // ── INVENTORY ──────────────────────────────────────────────────────────

    public function inventory(Request $request)
    {
        $store = $this->getStore();
        $data  = $this->getInventoryData($store);

        return view('store.dashboard.inventory', array_merge($data, compact('store')));
    }

    private function getInventoryData(Store $store): array
    {
        $products = $store->products()
            ->with('images')
            ->orderBy('stock')
            ->paginate(20);

        $lowStock = $store->products()
            ->where('stock', '>', 0)
            ->where('stock', '<', 5)
            ->with('images')
            ->get();

        $outOfStock = $store->products()->where('stock', 0)->count();

        $bestSellers = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.store_id', $store->id)
            ->selectRaw('products.id, products.name, products.stock, SUM(order_items.quantity) as units_sold')
            ->groupBy('products.id', 'products.name', 'products.stock')
            ->orderByDesc('units_sold')
            ->take(5)
            ->get();

        $inventoryValue  = $store->products()->selectRaw('SUM(stock * price) as total_value')->value('total_value') ?? 0;
        $totalProducts   = $store->products()->count();

        return compact('products', 'lowStock', 'outOfStock', 'bestSellers', 'inventoryValue', 'totalProducts');
    }

    // ── CUSTOMERS ──────────────────────────────────────────────────────────

    public function customers(Request $request)
    {
        $store = $this->getStore();
        $range = $request->get('range', 'month');
        $data  = $this->getCustomersData($store, $range);

        return view('store.dashboard.customers', array_merge($data, compact('store', 'range')));
    }

    private function getCustomersData(Store $store, string $range): array
    {
        [$from, $to] = $this->getDateRange($range);

        // Join orders that contain this store's products via a subquery
        $storeOrderIds = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.store_id', $store->id)
            ->distinct()
            ->pluck('order_items.order_id');

        $topCustomers = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->whereIn('orders.id', $storeOrderIds)
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$from, $to])
            ->whereNotNull('orders.user_id')
            ->selectRaw('users.id, users.name, users.email, COUNT(orders.id) as order_count, SUM(orders.grand_total) as total_spent, MAX(orders.created_at) as last_order_at')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->take(10)
            ->get();

        $clv = DB::table('orders')
            ->whereIn('id', $storeOrderIds)
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('user_id')
            ->selectRaw('user_id, SUM(grand_total) as lifetime_value')
            ->groupBy('user_id')
            ->get()
            ->avg('lifetime_value') ?? 0;

        $newCustomers = DB::table('orders')
            ->whereIn('id', $storeOrderIds)
            ->whereBetween('created_at', [$from, $to])
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');

        $returningCustomers = DB::table('orders')
            ->whereIn('id', $storeOrderIds)
            ->whereNotNull('user_id')
            ->selectRaw('user_id, COUNT(*) as order_count')
            ->groupBy('user_id')
            ->having('order_count', '>', 1)
            ->get()
            ->count();

        $totalCustomers = DB::table('orders')
            ->whereIn('id', $storeOrderIds)
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');

        return compact('topCustomers', 'clv', 'newCustomers', 'returningCustomers', 'totalCustomers');
    }

    // ── TRAFFIC ────────────────────────────────────────────────────────────

    public function traffic(Request $request)
    {
        $store = $this->getStore();
        $range = $request->get('range', 'month');

        $totalVisits = (int) ($store->visit_count ?? 0);
        $lastVisited = $store->last_visited_at;

        $trafficSources = [
            'Direct'       => 45,
            'Social Media' => 30,
            'Search'       => 15,
            'Referral'     => 10,
        ];

        $pageViews = [
            'Store Page'    => $totalVisits,
            'Product Pages' => $store->products()->count() * 3,
        ];

        return view('store.dashboard.traffic', compact(
            'store', 'range', 'totalVisits', 'lastVisited', 'trafficSources', 'pageViews'
        ));
    }

    // ── CHART DATA (AJAX) ──────────────────────────────────────────────────

    public function chartData(Request $request)
    {
        $store = $this->getStore();
        $range = $request->get('range', 'month');
        [$from, $to] = $this->getDateRange($range);

        $data = Order::whereHas('items.product', fn ($q) => $q->where('store_id', $store->id))
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($data);
    }

    // ── PRINT REPORT ───────────────────────────────────────────────────────

    public function printReport(Request $request, string $section)
    {
        $store = $this->getStore();
        $range = $request->get('range', 'month');

        $data = match ($section) {
            'orders'    => $this->getOrdersData($store, $range),
            'inventory' => $this->getInventoryData($store),
            'customers' => $this->getCustomersData($store, $range),
            default     => $this->getOverviewData($store, $range),
        };

        return view('store.dashboard.print', array_merge($data, compact('store', 'section', 'range')));
    }
}
