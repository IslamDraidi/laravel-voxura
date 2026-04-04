<?php

namespace App\Http\Controllers;

use App\Models\Product;

class CompareController extends Controller
{
    private const MAX = 4;

    private const KEY = 'compare';

    /** GET /compare */
    public function index()
    {
        $ids = session(self::KEY, []);
        $products = Product::with('category', 'variants')->whereIn('id', $ids)->get();
        // Preserve session order
        $products = collect($ids)->map(fn ($id) => $products->firstWhere('id', $id))->filter()->values();

        return view('compare.index', compact('products'));
    }

    /** POST /compare/{product} */
    public function add(Product $product)
    {
        $ids = session(self::KEY, []);

        if (count($ids) >= self::MAX) {
            return back()->with('error', 'You can compare up to '.self::MAX.' products at a time.');
        }

        if (! in_array($product->id, $ids)) {
            $ids[] = $product->id;
            session([self::KEY => $ids]);
        }

        return back()->with('success', "\"{$product->name}\" added to compare.");
    }

    /** DELETE /compare/{product} */
    public function remove(Product $product)
    {
        $ids = session(self::KEY, []);
        session([self::KEY => array_values(array_filter($ids, fn ($id) => $id !== $product->id))]);

        return back()->with('success', "\"{$product->name}\" removed from compare.");
    }

    /** DELETE /compare */
    public function clear()
    {
        session()->forget(self::KEY);

        return back()->with('success', 'Compare list cleared.');
    }
}
