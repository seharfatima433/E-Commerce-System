<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()->featured()->inStock()->with('category')->take(8)->get();
        $categories       = Category::active()->roots()->withCount('activeProducts')->orderBy('sort_order')->get();
        $newArrivals      = Product::active()->inStock()->with('category')->latest()->take(8)->get();
        $onSale           = Product::active()->inStock()->whereNotNull('sale_price')->with('category')->take(4)->get();

        return view('home.index', compact('featuredProducts', 'categories', 'newArrivals', 'onSale'));
    }
}
