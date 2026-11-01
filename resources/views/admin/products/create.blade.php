@extends('layouts.admin')
@section('title', isset($product) ? 'Edit Product' : 'Add Product')
@section('page-title', isset($product) ? 'Edit Product' : 'Add Product')
@section('content')
<div class="bg-white border border-gray-200 rounded-lg p-6 max-w-4xl shadow-sm">
    <form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                <textarea name="short_description" rows="2" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary resize-none">{{ old('short_description', $product->short_description ?? '') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Description</label>
                <textarea name="description" rows="5" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary resize-none">{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                <select name="category_id" required class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-gray-700">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                <input type="text" name="brand" value="{{ old('brand', $product->brand ?? '') }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price (Rs.) *</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" required class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sale Price (Rs.)</label>
                <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price ?? '') }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity *</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Product Images</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2.5 text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white hover:file:bg-primary-hover transition">
                <p class="text-xs text-gray-500 mt-1">You can select multiple images.</p>
            </div>
            
            <div class="md:col-span-2 flex gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 accent-primary">
                    <span class="text-sm font-medium text-gray-700">Active (Visible to customers)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }} class="w-4 h-4 accent-yellow-400">
                    <span class="text-sm font-medium text-gray-700">Featured</span>
                </label>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-200 flex gap-4">
            <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-6 py-2.5 rounded text-sm font-bold transition shadow-sm uppercase tracking-wide">Save Product</button>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded text-sm font-bold transition uppercase tracking-wide">Cancel</a>
        </div>
    </form>
</div>
@endsection
