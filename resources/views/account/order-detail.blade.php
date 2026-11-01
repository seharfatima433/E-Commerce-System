@extends('layouts.app')
@section('title','Order Details | Shopora')
@section('content')
<div class="bg-gray-100 py-6 min-h-[calc(100vh-200px)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('orders.index') }}" class="text-sm text-gray-500 hover:text-primary transition"><i class="fa-solid fa-arrow-left mr-1"></i> Back to Orders</a>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6 flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-xl font-medium text-gray-800">Order Details</h1>
                <p class="text-sm text-gray-500 mt-1">Order <span class="text-blue-600 font-medium">#{{ $order->order_number }}</span> · Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 mb-1">Total</p>
                <p class="text-xl font-bold text-primary">Rs. {{ number_format($order->total) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                {{-- Tracker --}}
                <div class="glass-panel rounded-3xl shadow-3d border border-white/5 p-8">
                    <h3 class="text-lg font-bold text-accent mb-8 uppercase tracking-widest flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-primary text-sm"></i>
                        Status Tracker
                    </h3>
                    <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-8 md:gap-0 px-4">
                        <!-- Connecting Line (Desktop) -->
                        <div class="absolute left-0 right-0 top-1/2 h-0.5 bg-primary/10 -z-10 -translate-y-1/2 mx-16 hidden md:block"></div>
                        <!-- Connecting Line (Mobile) -->
                        <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-primary/10 -z-10 my-4 md:hidden"></div>
                        
                        @php $statuses=['pending','processing','shipped','delivered']; $cur=array_search($order->status,$statuses); @endphp
                        @foreach($statuses as $idx => $s)
                        <div class="flex flex-row md:flex-col items-center gap-4 md:gap-2">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500 {{ $idx<=$cur?'bg-primary text-white shadow-lg shadow-primary/30':'bg-surfaceLight text-accent/20 border border-white/10' }} z-10">
                                <i class="fa-solid {{ $idx<$cur?'fa-check':($idx==$cur?'fa-spinner fa-spin':'fa-circle') }} text-xs"></i>
                            </div>
                            <p class="text-[10px] font-black {{ $idx<=$cur?'text-accent':'text-accent/20' }} uppercase tracking-[0.2em]">{{ $s }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Items --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-100">
                        <h3 class="font-medium text-gray-800">Package Items</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($order->items as $item)
                        <div class="p-4 flex gap-4">
                            <div class="w-20 h-20 bg-gray-50 border border-gray-100 flex items-center justify-center text-3xl flex-shrink-0 overflow-hidden">
                                @if($item->product && $item->product->images && is_array($item->product->images) && count($item->product->images) > 0)
                                    <img src="{{ asset('storage/' . $item->product->images[0]) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                @else
                                    🛍️
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-accent text-sm leading-tight mb-2 uppercase tracking-tight">{{ $item->product_name }}</p>
                                <p class="text-[10px] text-accent/40 uppercase tracking-widest">Qty: {{ $item->quantity }} · Price: Rs. {{ number_format($item->price) }}</p>
                                
                                @if($order->status === 'delivered')
                                <a href="{{ route('products.show', $item->product->slug) }}#reviews" class="inline-flex items-center gap-2 mt-4 text-xs font-bold text-primary hover:text-accent transition-colors">
                                    <i class="fa-solid fa-star-sharp text-[10px]"></i>
                                    WRITE A REVIEW
                                </a>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-accent">Rs. {{ number_format($item->total) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                {{-- Summary --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-medium text-gray-800 mb-4">Total Summary</h3>
                    <div class="space-y-2 text-sm text-gray-600 mb-4 pb-4 border-b border-gray-100">
                        <div class="flex justify-between"><span>Subtotal</span><span>Rs. {{ number_format($order->subtotal) }}</span></div>
                        <div class="flex justify-between"><span>Shipping Fee</span><span>{{ $order->shipping==0?'Free':'Rs. '.number_format($order->shipping) }}</span></div>
                        @if($order->discount>0)<div class="flex justify-between text-green-600"><span>Voucher Discount</span><span>- Rs. {{ number_format($order->discount) }}</span></div>@endif
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-800 text-sm">Total</span>
                        <span class="text-primary font-bold text-xl block leading-none">Rs. {{ number_format($order->total) }}</span>
                    </div>
                    @if(in_array($order->status,['pending','processing']))
                    <form action="{{ route('orders.cancel',$order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                        @csrf
                        <button class="w-full border border-red-500 text-red-500 font-medium py-2 text-sm rounded hover:bg-red-50 transition">CANCEL ORDER</button>
                    </form>
                    @endif
                </div>

                {{-- Delivery Info --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-medium text-gray-800 mb-4">Delivery Information</h3>
                    <p class="text-sm font-medium text-gray-800 mb-1">{{ $order->shipping_name }}</p>
                    <p class="text-sm text-gray-600"><span class="bg-gray-100 px-1 py-0.5 rounded mr-1 text-xs">HOME</span> {{ $order->shipping_address }}, {{ $order->shipping_city }} - {{ $order->shipping_country }}</p>
                    <p class="text-sm text-gray-600 mt-2">{{ $order->shipping_phone }}</p>
                    <p class="text-sm text-gray-600">{{ $order->shipping_email }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-medium text-gray-800 mb-2">Payment Method</h3>
                    <p class="text-sm text-gray-600">{{ ucwords(str_replace('_',' ',$order->payment_method)) }}</p>
                    <p class="text-xs text-gray-500 mt-1 uppercase">{{ $order->payment_status }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
