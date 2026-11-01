@extends('layouts.admin')
@section('title','Order ' . $order->order_number)
@section('page-title','Order ' . $order->order_number)
@section('content')
<div class="flex gap-4 mb-6">
    <a href="{{ route('admin.orders.index') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded text-sm font-semibold transition flex items-center gap-2 shadow-sm">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Order Details & Items --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
            <h2 class="font-medium text-gray-800 mb-4 pb-2 border-b border-gray-100">Update Status</h2>
            <form action="{{ route('admin.orders.update-status',$order) }}" method="POST" class="flex flex-col sm:flex-row items-end gap-4">
                @csrf @method('PATCH')
                <div class="w-full">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Order Status</label>
                    <select name="status" class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary">
                        @foreach(['pending','processing','shipped','delivered','cancelled','refunded'] as $s)
                        <option value="{{ $s }}" {{ $order->status==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Payment Status</label>
                    <select name="payment_status" class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary">
                        @foreach(['unpaid','paid','refunded'] as $s)
                        <option value="{{ $s }}" {{ $order->payment_status==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-6 py-2 rounded text-sm font-bold uppercase transition w-full sm:w-auto shadow-sm tracking-wide">Update</button>
            </form>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-100">
                <h2 class="font-medium text-gray-800">Order Items</h2>
            </div>
            <div class="divide-y divide-gray-100 px-6">
                @foreach($order->items as $item)
                <div class="flex items-center gap-4 py-4">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-800 text-sm"><a href="{{ route('products.show',$item->product->slug ?? '#') }}" class="hover:text-primary transition" target="_blank">{{ $item->product_name }}</a></p>
                        <p class="text-xs text-gray-500 mt-0.5">Qty: {{ $item->quantity }} × Rs. {{ number_format($item->price) }}</p>
                    </div>
                    <span class="font-bold text-gray-800 text-sm">Rs. {{ number_format($item->total) }}</span>
                </div>
                @endforeach
            </div>
            <div class="p-6 border-t border-gray-100 space-y-2 text-sm text-gray-600 bg-gray-50 rounded-b-lg">
                <div class="flex justify-between"><span>Subtotal</span><span>Rs. {{ number_format($order->subtotal) }}</span></div>
                <div class="flex justify-between"><span>Shipping</span><span>Rs. {{ number_format($order->shipping) }}</span></div>
                @if($order->discount>0)<div class="flex justify-between text-green-600"><span>Discount ({{ $order->coupon_code }})</span><span>- Rs. {{ number_format($order->discount) }}</span></div>@endif
                <div class="flex justify-between font-bold text-gray-800 text-base pt-3 mt-1 border-t border-gray-200"><span>Total</span><span>Rs. {{ number_format($order->total) }}</span></div>
            </div>
        </div>
    </div>

    {{-- Customer & Shipping --}}
    <div class="space-y-6">
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
            <h3 class="font-medium text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2"><i class="fa-solid fa-user text-gray-400"></i> Customer</h3>
            <div class="flex items-center gap-3 mb-4">
                <img src="{{ $order->user->avatar_url }}" class="w-10 h-10 rounded-full border border-gray-200" alt="">
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ $order->user->name }}</p>
                    <p class="text-xs text-gray-500">{{ $order->user->email }}</p>
                </div>
            </div>
            <a href="{{ route('admin.users.show',$order->user) }}" class="text-xs text-blue-600 hover:underline">View Profile</a>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
            <h3 class="font-medium text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2"><i class="fa-solid fa-truck text-gray-400"></i> Shipping Address</h3>
            <p class="text-sm text-gray-800 font-medium mb-1">{{ $order->shipping_name }}</p>
            <p class="text-sm text-gray-600">{{ $order->shipping_address }}</p>
            <p class="text-sm text-gray-600">{{ $order->shipping_city }}, {{ $order->shipping_country }} {{ $order->shipping_postal }}</p>
            <p class="text-sm text-gray-600 mt-2"><i class="fa-solid fa-phone w-4 text-gray-400"></i> {{ $order->shipping_phone }}</p>
            <p class="text-sm text-gray-600"><i class="fa-solid fa-envelope w-4 text-gray-400"></i> {{ $order->shipping_email }}</p>
        </div>

        @if($order->notes)
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 shadow-sm">
            <h3 class="font-medium text-orange-800 mb-2 text-sm"><i class="fa-solid fa-note-sticky"></i> Order Notes</h3>
            <p class="text-sm text-orange-700">{{ $order->notes }}</p>
        </div>
        @endif
        
        <form action="{{ route('admin.orders.destroy',$order) }}" method="POST" onsubmit="return confirm('WARNING: This will permanently delete the order. Are you sure?')">
            @csrf @method('DELETE')
            <button class="w-full bg-white border border-red-200 hover:bg-red-50 text-red-600 font-bold py-2.5 rounded text-sm transition uppercase shadow-sm">Delete Order</button>
        </form>
    </div>
</div>
@endsection
