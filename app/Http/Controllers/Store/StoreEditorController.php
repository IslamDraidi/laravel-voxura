<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreEditorController extends Controller
{
    private function getStore(): Store
    {
        return view()->shared('currentStore') ?? auth()->user()->store;
    }

    public function index()
    {
        $store = $this->getStore();
        abort_if(!$store, 403);

        $products = $store->products()
            ->with('images')
            ->latest()
            ->get();

        $categories = $store->category_tags ?? [];

        return view('store.editor.index', compact('store', 'products', 'categories'));
    }

    public function preview()
    {
        $store = $this->getStore();
        abort_if(!$store, 403);

        $products = $store->products()
            ->with('images')
            ->latest()
            ->paginate(8);

        $totalProducts   = $store->products()->count();
        $total3DProducts = $store->products()->where('model3d_status', 'ready')->count();

        $featuredProducts = $store->products()
            ->with('images')
            ->latest()
            ->take(5)
            ->get();

        $similarStores = Store::approved()
            ->where('id', '!=', $store->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('store.editor.preview', compact(
            'store', 'products', 'totalProducts',
            'total3DProducts', 'featuredProducts', 'similarStores'
        ));
    }

    public function save(Request $request)
    {
        $store = $this->getStore();
        abort_if(!$store, 403);

        $validated = $request->validate([
            'name'                    => 'required|string|max:150',
            'tagline'                 => 'nullable|string|max:200',
            'description'             => 'nullable|string|max:2000',
            'accent_color'            => 'nullable|string|max:7',
            'category_tags'           => 'nullable|array',
            'social_links'            => 'nullable|array',
            'social_links.instagram'  => 'nullable|url',
            'social_links.facebook'   => 'nullable|url',
            'social_links.tiktok'     => 'nullable|url',
            'social_links.whatsapp'   => 'nullable|string',
            'social_links.website'    => 'nullable|url',
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $filename = $store->slug . '-logo.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images/stores'), $filename);
            $validated['logo_path'] = 'images/stores/' . $filename;
        }

        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $filename = $store->slug . '-banner.' . $banner->getClientOriginalExtension();
            $banner->move(public_path('images/stores'), $filename);
            $validated['banner_path'] = 'images/stores/' . $filename;
        }

        $store->update($validated);

        return response()->json([
            'success'     => true,
            'message'     => 'Store saved successfully!',
            'preview_url' => route('store.editor.preview'),
            'store'       => $store->fresh(),
        ]);
    }

    public function products()
    {
        $store    = $this->getStore();
        $products = $store->products()->with('images')->latest()->get();
        return response()->json($products);
    }

    public function storeProduct(Request $request)
    {
        $store = $this->getStore();

        $validated = $request->validate([
            'name'        => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        $product = $store->products()->create([
            ...$validated,
            'status'   => 'approved',
            'store_id' => $store->id,
        ]);

        return response()->json([
            'success' => true,
            'product' => $product->load('images'),
        ]);
    }

    public function editProduct(Product $product)
    {
        $store = $this->getStore();
        abort_if($product->store_id !== $store->id, 403);
        return response()->json($product->load('images'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $store = $this->getStore();
        abort_if($product->store_id !== $store->id, 403);

        $validated = $request->validate([
            'name'        => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        $product->update($validated);

        return response()->json([
            'success' => true,
            'product' => $product->fresh()->load('images'),
        ]);
    }

    public function deleteProduct(Product $product)
    {
        $store = $this->getStore();
        abort_if($product->store_id !== $store->id, 403);
        $product->delete();
        return response()->json(['success' => true]);
    }

    public function uploadProductImage(Request $request, Product $product)
    {
        $store = $this->getStore();
        abort_if($product->store_id !== $store->id, 403);

        $request->validate(['image' => 'required|image|max:5120']);

        $img      = $request->file('image');
        $filename = 'product-' . $product->id . '-' . time() . '.' . $img->getClientOriginalExtension();
        $img->move(public_path('images/products'), $filename);

        $image = $product->images()->create([
            'image_path' => 'images/products/' . $filename,
        ]);

        return response()->json(['success' => true, 'image' => $image]);
    }

    public function deleteProductImage(Product $product, $imageId)
    {
        $store = $this->getStore();
        abort_if($product->store_id !== $store->id, 403);

        $image    = $product->images()->findOrFail($imageId);
        $fullPath = public_path($image->image_path);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        $image->delete();

        return response()->json(['success' => true]);
    }
}
