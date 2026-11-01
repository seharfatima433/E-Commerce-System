@extends('layouts.app')
@section('title','My Wishlist | Shopora')
@section('content')

<div class="bg-surfaceLight py-8 min-h-[calc(100vh-200px)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row gap-6">

            {{-- Sidebar --}}
            <div class="md:w-64 flex-shrink-0">
                <div class="glass-panel rounded-2xl p-5 mb-4">
                    <p class="text-xs text-accent/50 mb-1 uppercase tracking-widest">Hello,</p>
                    <p class="font-semibold text-accent text-lg">{{ auth()->user()->name }}</p>
                </div>
                <div class="glass-panel rounded-2xl p-5">
                    <ul class="space-y-3 text-sm">
                        <li>
                            <a href="{{ route('account.dashboard') }}"
                               class="flex items-center gap-3 p-2 rounded-xl text-accent/70 hover:text-accent hover:bg-primary/5 transition">
                                <i class="fa-regular fa-user w-4 text-center"></i> My Account
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('orders.index') }}"
                               class="flex items-center gap-3 p-2 rounded-xl text-accent/70 hover:text-accent hover:bg-primary/5 transition">
                                <i class="fa-regular fa-box w-4 text-center"></i> My Orders
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('account.wishlist') }}"
                               class="flex items-center gap-3 p-2 rounded-xl bg-primary/10 text-primary font-semibold transition">
                                <i class="fa-solid fa-heart w-4 text-center"></i> My Wishlist
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold text-accent mb-6">
                    My Wishlist
                    <span class="text-sm font-normal text-accent/50 ml-2">({{ $items->total() }} items)</span>
                </h1>

                @if($items->count())
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($items as $item)
                    @php
                        // Direct Product model ya Wishlist->product relation dono handle karo
                        $product = isset($item->slug) ? $item : ($item->product ?? null);
                    @endphp

                    @if($product && $product->slug)
                    <div class="bg-white rounded-2xl overflow-hidden border border-primary/8 hover:border-primary/25 hover:shadow-3d transition-all duration-300 group flex flex-col">

                        {{-- Image --}}
                        <div class="relative aspect-[4/3] bg-surfaceLight flex items-center justify-center p-4 overflow-hidden">
                            <a href="{{ route('products.show', $product->slug) }}" class="w-full h-full flex items-center justify-center">
                                @if(!empty($product->images) && is_array($product->images) && count($product->images) > 0)
                                    <img src="{{ asset('storage/' . $product->images[0]) }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-contain drop-shadow-md group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <i class="fa-solid fa-box-open text-5xl text-primary/20"></i>
                                @endif
                            </a>

                            {{-- Sale badge --}}
                            @if($product->is_on_sale)
                            <div class="absolute top-3 left-3 bg-primary text-white text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-widest">
                                -{{ $product->discount_percent }}%
                            </div>
                            @endif

                            {{-- Remove from wishlist --}}
                            <form action="{{ route('wishlist.toggle', $product) }}" method="POST"
                                  class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                @csrf
                                <button type="submit" title="Remove from wishlist"
                                        class="w-8 h-8 rounded-full bg-white border border-red-200 shadow-sm flex items-center justify-center text-red-400 hover:bg-red-50 transition">
                                    <i class="fa-solid fa-heart text-xs"></i>
                                </button>
                            </form>
                        </div>

                        {{-- Details --}}
                        <div class="p-4 flex flex-col flex-1">
                            <a href="{{ route('products.show', $product->slug) }}"
                               class="font-semibold text-accent hover:text-primary transition text-sm leading-snug line-clamp-2 mb-3 flex-1">
                                {{ $product->name }}
                            </a>

                            <div class="flex items-center justify-between mt-auto">
                                <div>
                                    @if($product->is_on_sale)
                                        <p class="text-xs text-accent/40 line-through">Rs. {{ number_format($product->price) }}</p>
                                        <p class="text-base font-black text-accent">Rs. {{ number_format($product->sale_price) }}</p>
                                    @else
                                        <p class="text-base font-black text-accent">Rs. {{ number_format($product->price) }}</p>
                                    @endif
                                </div>

                                @if($product->is_in_stock)
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit"
                                            class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center hover:bg-accent hover:scale-110 transition-all shadow-md">
                                        <i class="fa-solid fa-cart-plus text-xs"></i>
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-accent/40 font-medium">Out of stock</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-10 flex justify-center">
                    {{ $items->links() }}
                </div>

                @else
                {{-- Empty State --}}
                <div class="glass-panel rounded-3xl p-16 text-center">
                    <div class="w-24 h-24 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-regular fa-heart text-4xl text-primary/40"></i>
                    </div>
                    <h2 class="text-xl font-bold text-accent mb-2">Your wishlist is empty</h2>
                    <p class="text-accent/55 text-sm mb-8">Save items you love and come back to them anytime.</p>
                    <a href="{{ route('products.index') }}"
                       class="btn-brand inline-block px-8 py-3 font-bold text-sm tracking-wide text-white">
                        Explore Collection
                    </a>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

@endsection