<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('stock')) {
            if ($request->stock === 'low')  $query->where('stock', '<=', 10)->where('stock', '>', 0);
            if ($request->stock === 'out')  $query->where('stock', 0);
        }

        $products   = $query->get();
        $categories = Category::all();

        $stats = [
            'total'          => Product::count(),
            'low_stock'      => Product::where('stock', '<=', 10)->where('stock', '>', 0)->count(),
            'out'            => Product::where('stock', 0)->count(),
            'archived'       => Product::onlyTrashed()->count(),
            'users'          => User::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('products', 'categories', 'stats'));
    }

    // ── Products ──

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        }

        Product::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'category_id' => $request->category_id,
            'image'       => $imageName,
        ]);

        return redirect('/admin')->with('success', 'Product added successfully!');
    }

    public function edit($id)
    {
        $product    = Product::withTrashed()->findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName      = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $product->image = $imageName;
        }

        $product->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'category_id' => $request->category_id,
            'image'       => $product->image,
        ]);

        $redirectTo = $request->input('redirect_to') === 'archive' ? '/admin/archive' : '/admin';
        return redirect($redirectTo)->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
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
        $product   = Product::withTrashed()->findOrFail($id);
        $imagePath = public_path('images/' . $product->image);
        if ($product->image && file_exists($imagePath)) unlink($imagePath);
        $product->forceDelete();
        return redirect('/admin/archive')->with('success', 'Product permanently deleted.');
    }

    // ── Orders ──

    public function orders(Request $request)
    {
        $query = Order::with('user', 'items.product')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->get();

        $statusCounts = [
            'all'        => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped'    => Order::where('status', 'shipped')->count(),
            'delivered'  => Order::where('status', 'delivered')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', "Order #{$order->id} status updated to " . ucfirst($request->status) . '.');
    }
}