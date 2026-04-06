<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $parents = Category::whereNull('parent_id')
            ->withCount('products')
            ->with(['children' => fn($q) => $q->withCount('products')])
            ->orderBy('name')
            ->get();

        // Flat list of parents for the "parent" dropdown in forms
        $parentOptions = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.categories.index', compact('parents', 'parentOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create([
            'name'      => $request->name,
            'parent_id' => $request->parent_id ?: null,
        ]);

        return redirect('/admin/categories')->with('success', "Category \"{$request->name}\" created!");
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'      => 'required|string|max:255|unique:categories,name,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Prevent a category from becoming its own parent
        $parentId = ($request->parent_id && $request->parent_id != $category->id)
            ? $request->parent_id
            : null;

        $category->update([
            'name'      => $request->name,
            'parent_id' => $parentId,
        ]);

        return redirect('/admin/categories')->with('success', 'Category updated!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect('/admin/categories')->with('error', "Cannot delete \"{$category->name}\" — it has products assigned to it.");
        }

        if ($category->children()->count() > 0) {
            return redirect('/admin/categories')->with('error', "Cannot delete \"{$category->name}\" — it has subcategories. Delete or reassign them first.");
        }

        $category->delete();
        return redirect('/admin/categories')->with('success', "Category \"{$category->name}\" deleted.");
    }
}
