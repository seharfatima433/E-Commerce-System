<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with('category');

        if ($search = $request->get('search')) {
            $query->search($search);
        }
        if ($categorySlug = $request->get('category')) {
            $cat = Category::where('slug', $categorySlug)->first();
            if ($cat) $query->forCategory($cat->id);
        }
        $query->priceBetween($request->get('min_price'), $request->get('max_price'));

        if ($rating = $request->get('rating')) {
            $query->where('rating', '>=', $rating);
        }
        if ($request->get('featured')) {
            $query->featured();
        }

        switch ($request->get('sort', 'latest')) {
            case 'price_asc':  $query->orderBy('price', 'asc');  break;
            case 'price_desc': $query->orderBy('price', 'desc'); break;
            case 'rating':     $query->orderBy('rating', 'desc'); break;
            case 'popular':    $query->orderBy('sales_count', 'desc'); break;
            default:           $query->latest(); break;
        }

        $products = $query->paginate($request->get('per_page', 12));

        return response()->json([
            'success' => true,
            'data'    => $products->map(fn($p) => $this->formatProduct($p)),
            'meta'    => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'total'        => $products->total(),
                'per_page'     => $products->perPage(),
            ],
        ]);
    }

    public function show($slug)
    {
        $product = Product::active()->where('slug', $slug)->with(['category', 'approvedReviews.user'])->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => array_merge($this->formatProduct($product), [
                'description' => $product->description,
                'attributes'  => $product->attributes,
                'images'      => $product->images ?? [],
                'reviews'     => $product->approvedReviews->map(fn($r) => [
                    'id'         => $r->id,
                    'rating'     => $r->rating,
                    'title'      => $r->title,
                    'body'       => $r->body,
                    'user_name'  => $r->user->name,
                    'created_at' => $r->created_at->toDateString(),
                ]),
            ]),
        ]);
    }

    public function categories()
    {
        $categories = Category::active()->roots()->withCount('activeProducts')->orderBy('sort_order')->get();
        return response()->json(['success' => true, 'data' => $categories]);
    }

    private function formatProduct(Product $p): array
    {
        return [
            'id'               => $p->id,
            'name'             => $p->name,
            'slug'             => $p->slug,
            'short_description'=> $p->short_description,
            'price'            => $p->price,
            'sale_price'       => $p->sale_price,
            'current_price'    => $p->current_price,
            'discount_percent' => $p->discount_percent,
            'is_on_sale'       => $p->is_on_sale,
            'rating'           => $p->rating,
            'reviews_count'    => $p->reviews_count,
            'stock'            => $p->stock,
            'is_in_stock'      => $p->is_in_stock,
            'brand'            => $p->brand,
            'primary_image'    => $p->images && is_array($p->images) && count($p->images) > 0 ? asset('storage/' . $p->images[0]) : null,
            'is_featured'      => $p->is_featured,
            'category'         => ['id' => $p->category->id, 'name' => $p->category->name, 'slug' => $p->category->slug],
        ];
    }
}
