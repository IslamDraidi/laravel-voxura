<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class UserController extends Controller 
{
    public function index()
    {
        $products = Product::with('category')->get();
        $featuredProducts = $products->take(3);

        return view('home', compact('products', 'featuredProducts'));
    }
}