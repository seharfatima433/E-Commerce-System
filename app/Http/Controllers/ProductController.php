<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with('category');

        // Search
        if ($search = $request->get('search')) {
            $query->search($search);
        }

        // Filter by category
        if ($categorySlug = $request->get('category')) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();
            $query->forCategory($category->id);
        }

        // Filter by price
        $query->priceBetween($request->get('min_price'), $request->get('max_price'));

        // Filter by rating
        if ($rating = $request->get('rating')) {
            $query->where('rating', '>=', $rating);
        }

        // Filter by brand
        if ($brand = $request->get('brand')) {
            $query->where('brand', $brand);
        }

        // Filter by in-stock
        if ($request->get('in_stock')) {
            $query->inStock();
        }

        // Filter by on-sale
        if ($request->get('on_sale')) {
            $query->whereNotNull('sale_price');
        }

        // Sorting
        switch ($request->get('sort', 'latest')) {
            case 'price_asc':  $query->orderBy('price', 'asc');  break;
            case 'price_desc': $query->orderBy('price', 'desc'); break;
            case 'rating':     $query->orderBy('rating', 'desc'); break;
            case 'popular':    $query->orderBy('sales_count', 'desc'); break;
            default:           $query->latest(); break;
        }

        $perPage = $request->get('per_page', 12);
        $products   = $query->paginate($perPage)->withQueryString();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($products);
        }

        $categories = Category::active()->roots()->withCount('activeProducts')->get();
        $brands     = Product::active()->distinct()->pluck('brand')->filter()->values();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function show($slug)
    {
        $product  = Product::active()->where('slug', $slug)->with(['category', 'approvedReviews.user'])->firstOrFail();
        $related  = Product::active()
                        ->forCategory($product->category_id)
                        ->where('id', '!=', $product->id)
                        ->inStock()
                        ->take(4)
                        ->get();

        $userReview    = null;
        $alreadyReviewed = false;
        if (auth()->check()) {
            $userReview = $product->reviews()->where('user_id', auth()->id())->first();
            $alreadyReviewed = !is_null($userReview);
        }

        return view('products.show', compact('product', 'related', 'userReview', 'alreadyReviewed'));
    }

    public function search(Request $request)
    {
        $request->validate(['search' => 'required|string|min:2']);
        return redirect()->route('products.index', ['search' => $request->search]);
    }
}
