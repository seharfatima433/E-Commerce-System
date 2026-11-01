<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%$search%")->orWhere('sku', 'like', "%$search%");
        }
        if ($cat = $request->get('category_id')) {
            $query->where('category_id', $cat);
        }
        if ($request->get('low_stock')) {
            $query->where('stock', '<=', 5);
        }

        $products   = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::active()->get();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        $data['slug'] = Str::slug($data['name']);
        $data['images'] = $this->handleImages($request);

        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product->id);
        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('images')) {
            $data['images'] = $this->handleImages($request);
        }

        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return back()->with('success', 'Product status updated.');
    }

    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);
        return back()->with('success', 'Product featured status updated.');
    }

    private function validateProduct(Request $request, $ignoreId = null): array
    {
        return $request->validate([
            'name'              => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'sale_price'        => 'nullable|numeric|min:0|lt:price',
            'sku'               => 'nullable|string|max:100|unique:products,sku,' . $ignoreId,
            'stock'             => 'required|integer|min:0',
            'low_stock_threshold' => 'integer|min:1',
            'category_id'       => 'required|exists:categories,id',
            'brand'             => 'nullable|string|max:100',
            'is_featured'       => 'boolean',
            'is_active'         => 'boolean',
            'weight'            => 'nullable|string',
            'dimensions'        => 'nullable|string',
        ]);
    }

    private function handleImages(Request $request): array
    {
        $paths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $paths[] = $file->store('products', 'public');
            }
        }
        return $paths;
    }
}
