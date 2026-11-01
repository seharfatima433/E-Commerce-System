@extends('layouts.admin')
@section('title','Products')
@section('page-title','Products')
@section('content')
<div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
    <div class="flex flex-col sm:flex-row justify-between gap-4 mb-6">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="bg-white border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary w-full sm:w-64">
            <select name="category_id" class="bg-white border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary hidden sm:block">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-600 px-3 py-2 rounded transition"><i class="fa-solid fa-search text-sm"></i></button>
        </form>
        <a href="{{ route('admin.products.create') }}" class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded text-sm font-medium transition flex items-center justify-center gap-2 whitespace-nowrap shadow-sm">
            <i class="fa-solid fa-plus"></i> Add Product
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-y border-gray-200">
                <tr>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Stock</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $p)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded border border-gray-200 bg-gray-50 flex items-center justify-center text-xl flex-shrink-0 overflow-hidden">
                                @if($p->images && is_array($p->images) && count($p->images) > 0)
                                    <img src="{{ asset('storage/' . $p->images[0]) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                                @else
                                    🛍️
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 truncate max-w-[200px]">{{ $p->name }}</p>
                                <p class="text-xs text-gray-500">SKU: {{ $p->sku ?: 'N/A' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $p->category->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3">
                        <p class="text-gray-800 font-medium">Rs. {{ number_format($p->price) }}</p>
                        @if($p->is_on_sale)<p class="text-xs text-primary font-medium">Sale: Rs. {{ number_format($p->sale_price) }}</p>@endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs font-medium {{ $p->stock > 5 ? 'bg-green-50 text-green-600 border border-green-200' : ($p->stock > 0 ? 'bg-yellow-50 text-yellow-600 border border-yellow-200' : 'bg-red-50 text-red-600 border border-red-200') }}">
                            {{ $p->stock }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center space-x-2 whitespace-nowrap">
                        <form action="{{ route('admin.products.toggle-active', $p) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xl {{ $p->is_active ? 'text-green-500 hover:text-green-600' : 'text-gray-300 hover:text-gray-400' }}" title="Toggle Active"><i class="fa-solid fa-toggle-on"></i></button>
                        </form>
                        <form action="{{ route('admin.products.toggle-featured', $p) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-lg {{ $p->is_featured ? 'text-[#faca51] hover:text-[#e5b642]' : 'text-gray-300 hover:text-gray-400' }}" title="Toggle Featured"><i class="fa-solid fa-star"></i></button>
                        </form>
                    </td>
                    <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                        <a href="{{ route('admin.products.edit', $p) }}" class="text-blue-500 hover:text-blue-700 px-2"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 px-2"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6 flex justify-center">{{ $products->links() }}</div>
</div>
@endsection
