@extends('layouts.admin')
@section('title','Orders')
@section('page-title','Orders')
@section('content')
<div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
    <div class="flex flex-col sm:flex-row justify-between gap-4 mb-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Order # or Customer..." class="bg-white border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary w-full sm:w-64">
            <select name="status" class="bg-white border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary hidden sm:block">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                <option value="processing" {{ request('status')=='processing'?'selected':'' }}>Processing</option>
                <option value="shipped" {{ request('status')=='shipped'?'selected':'' }}>Shipped</option>
                <option value="delivered" {{ request('status')=='delivered'?'selected':'' }}>Delivered</option>
                <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
            </select>
            <button type="submit" class="bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-600 px-3 py-2 rounded transition"><i class="fa-solid fa-search text-sm"></i></button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-y border-gray-200">
                <tr>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Payment</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.orders.show',$order) }}" class="font-mono text-blue-600 font-medium hover:underline">{{ $order->order_number }}</a>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $order->user->name }}</p>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600 uppercase">{{ $order->status }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-xs px-2 py-1 rounded {{ $order->payment_status==='paid'?'bg-green-50 text-green-600 border border-green-200':'bg-gray-100 text-gray-600 border border-gray-200' }} uppercase">{{ $order->payment_status }}</span>
                    </td>
                    <td class="px-4 py-3 text-right text-gray-800 font-medium">Rs. {{ number_format($order->total) }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.orders.show',$order) }}" class="text-blue-500 hover:text-blue-700 text-xs font-semibold uppercase tracking-wider">Manage</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 flex justify-center">{{ $orders->links() }}</div>
</div>
@endsection
