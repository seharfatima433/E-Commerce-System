@extends('layouts.admin')
@section('title','Coupons')
@section('page-title','Coupons')
@section('content')
<div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <form method="GET" action="{{ route('admin.coupons.index') }}" class="flex gap-2 w-full sm:w-64">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search code..." class="bg-white border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary w-full">
            <button type="submit" class="bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-600 px-3 py-2 rounded transition"><i class="fa-solid fa-search text-sm"></i></button>
        </form>
        <a href="{{ route('admin.coupons.create') }}" class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded text-sm font-medium transition flex items-center justify-center gap-2 whitespace-nowrap shadow-sm">
            <i class="fa-solid fa-plus"></i> Add Coupon
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-y border-gray-200">
                <tr>
                    <th class="px-4 py-3">Code</th>
                    <th class="px-4 py-3 text-center">Type</th>
                    <th class="px-4 py-3 text-right">Value</th>
                    <th class="px-4 py-3 text-center">Uses</th>
                    <th class="px-4 py-3">Valid Until</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($coupons as $coupon)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono font-medium text-primary">{{ $coupon->code }}</td>
                    <td class="px-4 py-3 text-center"><span class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded uppercase">{{ $coupon->type }}</span></td>
                    <td class="px-4 py-3 text-right text-gray-800 font-medium">
                        {{ $coupon->type === 'fixed' ? 'Rs. '.number_format($coupon->value) : $coupon->value.'%' }}
                    </td>
                    <td class="px-4 py-3 text-center text-gray-600">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?: '∞' }}</td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : 'Never' }}
                        @if($coupon->expires_at && $coupon->expires_at->isPast()) <span class="text-xs text-red-500 block">Expired</span> @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <form action="{{ route('admin.coupons.toggle',$coupon) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xl {{ $coupon->is_active ? 'text-green-500 hover:text-green-600' : 'text-gray-300 hover:text-gray-400' }}"><i class="fa-solid fa-toggle-on"></i></button>
                        </form>
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.coupons.edit',$coupon) }}" class="text-blue-500 hover:text-blue-700 px-2"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="{{ route('admin.coupons.destroy',$coupon) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 px-2"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">No coupons found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 flex justify-center">{{ $coupons->links() }}</div>
</div>
@endsection
