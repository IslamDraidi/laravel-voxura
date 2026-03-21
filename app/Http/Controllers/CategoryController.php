<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create(['name' => $request->name]);
        return redirect('/admin/categories')->with('success', "Category \"{$request->name}\" created!");
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update(['name' => $request->name]);
        return redirect('/admin/categories')->with('success', 'Category updated!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect('/admin/categories')->with('error', "Cannot delete \"{$category->name}\" — it has products assigned to it.");
        }

        $category->delete();
        return redirect('/admin/categories')->with('success', "Category \"{$category->name}\" deleted.");
    }
}