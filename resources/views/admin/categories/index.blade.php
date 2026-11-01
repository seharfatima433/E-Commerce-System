@extends('layouts.admin')
@section('title','Categories')
@section('page-title','Categories')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
        <h2 class="font-medium text-gray-800 mb-4">Category List</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-y border-gray-200">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3">Parent</th>
                        <th class="px-4 py-3 text-center">Products</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $c)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-800 font-medium">{{ $c->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $c->slug }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $c->parent->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-center text-gray-700">{{ $c->products_count }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.categories.edit', $c) }}" class="text-blue-500 hover:text-blue-700 px-2"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form action="{{ route('admin.categories.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 px-2"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No categories found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-center">{{ $categories->links() }}</div>
    </div>

    <div>
        <div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-6 shadow-sm">
            <h2 class="font-medium text-gray-800 mb-4 pb-2 border-b border-gray-100">{{ isset($category) ? 'Edit' : 'Add' }} Category</h2>
            <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                @if(isset($category)) @method('PUT') @endif
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary resize-none">{{ old('description', $category->description ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Parent Category</label>
                    <select name="parent_id" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-white">
                        <option value="">None</option>
                        @foreach($parents as $p)
                        <option value="{{ $p->id }}" {{ old('parent_id', $category->parent_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 accent-primary">
                    <span class="text-sm font-medium text-gray-700">Active</span>
                </div>
                <div class="pt-4 border-t border-gray-100 flex gap-3">
                    <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded text-sm font-bold transition w-full uppercase shadow-sm">Save</button>
                    @if(isset($category))
                    <a href="{{ route('admin.categories.index') }}" class="bg-gray-100 hover:bg-gray-200 border border-gray-300 text-gray-700 px-4 py-2 rounded text-sm font-bold transition w-full text-center uppercase">Cancel</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
