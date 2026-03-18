<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('admin.dashboard', compact('products'));
    }

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
        $product = Product::withTrashed()->findOrFail($id);
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
            $imageName = time() . '.' . $request->image->extension();
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
        return redirect('/admin')->with('success', 'Product deleted successfully!');
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
}