@extends('layouts.app')
@section('title','My Orders | Shopora')
@section('content')
<div class="bg-gray-100 py-6 min-h-[calc(100vh-200px)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row gap-6">
            
            {{-- Sidebar --}}
            <div class="md:w-64 flex-shrink-0">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
                    <p class="text-xs text-gray-500 mb-1">Hello,</p>
                    <p class="font-medium text-gray-800">{{ $user->name }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <ul class="space-y-4 text-sm text-gray-600">
                        <li>
                            <a href="{{ route('account.dashboard') }}" class="hover:text-primary transition">Manage My Account</a>
                        </li>
                        <li>
                            <a href="{{ route('orders.index') }}" class="text-primary font-medium hover:text-primary transition">My Orders</a>
                        </li>
                        <li>
                            <a href="{{ route('account.wishlist') }}" class="hover:text-primary transition">My Wishlist</a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="flex-1 min-w-0">
                <h1 class="text-xl font-medium text-gray-800 mb-4">My Orders</h1>
                
                @if($orders->count())
                <div class="space-y-4">
                    @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-3 border-b border-gray-100 flex flex-wrap justify-between items-center gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Order</span> <span class="font-medium text-blue-600 ml-1">#{{ $order->order_number }}</span>
                                <span class="text-gray-400 mx-2">|</span>
                                <span class="text-gray-500">Placed on {{ $order->created_at->format('d M Y') }}</span>
                            </div>
                            <a href="{{ route('orders.show',$order) }}" class="text-blue-600 font-medium hover:underline uppercase text-xs">Manage</a>
                        </div>
                        <div class="p-6">
                            @foreach($order->items->take(2) as $item)
                            <div class="flex items-center gap-4 mb-4 last:mb-0">
                                <div class="w-16 h-16 bg-gray-50 border border-gray-100 flex items-center justify-center text-2xl flex-shrink-0 overflow-hidden">
                                    @if($item->product && $item->product->images && is_array($item->product->images) && count($item->product->images) > 0)
                                        <img src="{{ asset('storage/' . $item->product->images[0]) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        🛍️
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-800 text-sm leading-tight line-clamp-2">{{ $item->product_name }}</p>
                                </div>
                                <div class="text-right w-24">
                                    <span class="text-xs text-gray-500">Qty: {{ $item->quantity }}</span>
                                </div>
                                <div class="w-32 text-right">
                                    <span class="text-xs font-semibold px-2 py-1 rounded bg-gray-100 text-gray-600 uppercase">{{ $order->status }}</span>
                                </div>
                            </div>
                            @endforeach
                            @if($order->items->count() > 2)
                            <p class="text-xs text-gray-500 mt-2 text-center border-t border-gray-100 pt-2">+ {{ $order->items->count() - 2 }} more items</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-6 flex justify-end">{{ $orders->links() }}</div>
                @else
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-box-open text-3xl text-gray-300"></i>
                    </div>
                    <h2 class="text-lg font-medium text-gray-800 mb-2">No orders yet</h2>
                    <a href="{{ route('products.index') }}" class="btn-outline px-6 py-2 rounded font-medium text-sm inline-block uppercase mt-4">Continue Shopping</a>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
