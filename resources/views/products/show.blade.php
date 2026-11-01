@extends('layouts.app')
@section('title', $product->name . ' | Shopora')
@section('content')
<div class="py-6 md:py-12 relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-[10px] md:text-xs font-bold text-accent/40 uppercase tracking-widest mb-6 md:mb-10 fade-in-up">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
            <i class="fa-solid fa-chevron-right text-[8px]"></i>
            <a href="{{ route('products.index') }}" class="hover:text-primary transition-colors">Collection</a>
            <i class="fa-solid fa-chevron-right text-[8px]"></i>
            <span class="text-accent/80 truncate max-w-[150px] md:max-w-none">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-16 items-start">
            {{-- Image Gallery --}}
            <div class="space-y-4 fade-in-up delay-100">
                <div class="glass-panel rounded-3xl md:rounded-[3rem] p-4 md:p-8 shadow-3d border border-white/5 bg-white relative overflow-hidden group">
                    <div class="aspect-square flex items-center justify-center">
                        @if($product->images && is_array($product->images) && count($product->images) > 0)
                            <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-700">
                        @else
                            <i class="fa-solid fa-box text-accent/5 text-[8rem]"></i>
                        @endif
                    </div>
                </div>
                
                @if($product->images && is_array($product->images) && count($product->images) > 1)
                <div class="flex gap-3 md:gap-4 overflow-x-auto pb-2 custom-scrollbar">
                    @foreach($product->images as $img)
                    <div class="w-16 h-16 md:w-24 md:h-24 glass-panel rounded-xl md:rounded-3xl border-2 border-white/10 hover:border-primary transition-all cursor-pointer flex-shrink-0 p-2 bg-white">
                        <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-contain">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Product Info --}}
            <div class="space-y-6 md:space-y-8 fade-in-up delay-200" x-data="{ qty: 1 }">
                <div>
                    @if($product->is_on_sale)
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100 text-red-600 text-[10px] font-bold mb-4 border border-red-200 uppercase tracking-widest">
                        <i class="fa-solid fa-fire animate-pulse"></i> Limited Offer
                    </div>
                    @endif
                    
                    <h1 class="text-2xl md:text-5xl font-black text-accent uppercase tracking-tighter leading-tight mb-2 md:mb-4">{{ $product->name }}</h1>
                    
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1.5 text-amber-400 text-xs md:text-sm">
                            @for($i=1;$i<=5;$i++)
                            <i class="fa-solid fa-star {{ $i<=round($product->rating)?'':'text-accent/10' }}"></i>
                            @endfor
                            <span class="text-accent/40 font-bold ml-1">({{ $product->reviews_count }})</span>
                        </div>
                        <span class="text-accent/20 text-xs font-bold">|</span>
                        <p class="text-[10px] md:text-xs text-accent/40 font-bold uppercase tracking-[0.2em]">{{ $product->brand ?? 'Exclusive Collection' }}</p>
                    </div>
                </div>

                <div class="flex items-end gap-4 md:gap-6">
                    <div>
                        <p class="text-[9px] md:text-[10px] text-accent/40 font-black uppercase tracking-[0.2em] mb-1 md:mb-2">Investment</p>
                        <span class="text-3xl md:text-5xl font-black text-accent tracking-tighter leading-none">Rs. {{ number_format($product->current_price) }}</span>
                    </div>
                    @if($product->is_on_sale)
                    <div class="pb-1">
                        <span class="text-sm md:text-xl text-accent/20 line-through block">Rs. {{ number_format($product->price) }}</span>
                        <span class="text-emerald-500 text-[9px] md:text-[10px] font-black uppercase tracking-widest mt-1">-{{ $product->discount_percent }}% Savings</span>
                    </div>
                    @endif
                </div>

                <div class="space-y-4 md:space-y-6 pt-6 md:pt-8 border-t border-white/5">
                    <p class="text-sm md:text-lg text-accent/60 leading-relaxed font-medium">
                        {{ Str::limit($product->description, 250) }}
                    </p>
                    
                    <div class="grid grid-cols-2 gap-3 md:gap-4 bg-primary/5 p-4 md:p-6 rounded-2xl md:rounded-3xl border border-primary/10">
                        @foreach(['Standard Logistics', '14 Day Exchange', 'Premium Quality', 'Secure Payment'] as $f)
                        <div class="flex items-center gap-2 text-[8px] md:text-[10px] font-bold text-accent/70 uppercase tracking-widest">
                            <i class="fa-solid fa-circle-check text-primary text-[8px]"></i> {{ $f }}
                        </div>
                        @endforeach
                    </div>
                </div>

                @if($product->is_in_stock)
                <div class="space-y-6 pt-2">
                    <div class="flex items-center gap-6">
                        <span class="text-[10px] font-black text-accent/40 uppercase tracking-widest">Pieces</span>
                        <div class="flex items-center bg-surfaceLight rounded-full p-1 border border-white/10">
                            <button @click="qty=Math.max(1,qty-1)" class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full text-accent/40 hover:bg-white/10 hover:text-primary transition-all"><i class="fa-solid fa-minus text-[10px]"></i></button>
                            <span class="w-10 md:w-12 text-center text-sm font-black text-accent" x-text="qty"></span>
                            <button @click="qty=Math.min({{ $product->stock }},qty+1)" class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full text-accent/40 hover:bg-white/10 hover:text-primary transition-all"><i class="fa-solid fa-plus text-[10px]"></i></button>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 md:gap-4">
                        <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" :value="qty">
                            <button type="submit" class="w-full btn-brand py-4 md:py-5 font-bold text-sm md:text-base shadow-3d hover:shadow-3d-hover transition-all flex items-center justify-center gap-3">
                                <i class="fa-solid fa-cart-plus text-xs"></i>
                                ADD TO SPACE
                            </button>
                        </form>
                        <button class="btn-glass p-4 md:p-5 rounded-full text-accent flex items-center justify-center">
                            <i class="fa-regular fa-heart text-lg md:text-xl"></i>
                        </button>
                    </div>
                </div>
                @else
                <div class="bg-red-500/5 border border-red-500/10 p-6 rounded-3xl text-center">
                    <p class="text-red-500 text-xs font-bold uppercase tracking-widest">Out of Space (Restocking Soon)</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

        {{-- Details & Reviews --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-9 space-y-6">
                {{-- Description --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b border-gray-100">Product details of {{ $product->name }}</h2>
                    <div class="text-gray-600 text-sm leading-relaxed prose max-w-none">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                {{-- Reviews --}}
                <div id="reviews" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-6 pb-2 border-b border-gray-100">Ratings & Reviews</h2>
                    
                    <div class="flex flex-col md:flex-row gap-8 mb-8 pb-8 border-b border-gray-100">
                        <div class="text-center md:text-left">
                            <div class="flex items-end justify-center md:justify-start gap-2 mb-1">
                                <span class="text-5xl font-medium text-gray-800">{{ $product->rating }}</span>
                                <span class="text-2xl text-gray-400 mb-1">/5</span>
                            </div>
                            <div class="flex text-2xl justify-center md:justify-start mb-2">
                                @for($i=1;$i<=5;$i++)<i class="fa-solid fa-star {{ $i<=round($product->rating)?'text-[#faca51]':'text-gray-300' }}"></i>@endfor
                            </div>
                            <p class="text-sm text-gray-500">{{ $product->reviews_count }} Ratings</p>
                        </div>
                    </div>

                    @auth
                    @if(!$alreadyReviewed)
                    @php
                        $canReview = \App\Models\Order::where('user_id', auth()->id())
                            ->where('status', 'delivered')
                            ->whereHas('items', function($q) use ($product) {
                                $q->where('product_id', $product->id);
                            })->exists();
                    @endphp

                    @if($canReview)
                    <div class="mb-12 glass-panel border border-white/5 rounded-3xl p-8 shadow-3d">
                        <h4 class="text-xl font-bold text-accent mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-pen-nib text-primary text-sm"></i>
                            Share Your Experience
                        </h4>
                        <form action="{{ route('reviews.store', $product) }}" method="POST">
                            @csrf
                            <div x-data="{ star: 0 }" class="mb-8">
                                <p class="text-[10px] font-black text-accent/40 uppercase tracking-[0.2em] mb-3">Overall Rating</p>
                                <div class="flex gap-2 text-3xl cursor-pointer">
                                    @for($i=1;$i<=5;$i++)
                                    <i @click="star={{ $i }}" 
                                       class="fa-solid fa-star transition-all duration-300 transform hover:scale-125" 
                                       :class="star>={{ $i }}?'text-amber-400 drop-shadow-[0_0_8px_rgba(251,191,36,0.5)]':'text-accent/10'"></i>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" :value="star" required>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-black text-accent/40 uppercase tracking-[0.2em] mb-2">Headline</label>
                                    <input type="text" name="title" placeholder="Summarize your findings..." class="w-full bg-surfaceLight/50 border border-white/10 rounded-2xl px-6 py-3 text-sm text-accent focus:outline-none focus:border-primary transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-accent/40 uppercase tracking-[0.2em] mb-2">Detailed Thoughts</label>
                                    <textarea name="body" rows="4" placeholder="What did you like or dislike? How was the quality?" class="w-full bg-surfaceLight/50 border border-white/10 rounded-2xl px-6 py-4 text-sm text-accent focus:outline-none focus:border-primary transition-all resize-none"></textarea>
                                </div>
                                <button type="submit" class="w-full btn-brand py-4 font-bold text-base shadow-3d hover:shadow-3d-hover transition-all flex items-center justify-center gap-3">
                                    SUBMIT REVIEW
                                    <i class="fa-solid fa-paper-plane text-xs"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    @else
                    <div class="mb-12 bg-primary/5 border border-primary/10 rounded-2xl p-6 text-center">
                        <i class="fa-solid fa-circle-info text-primary mb-3 text-xl"></i>
                        <p class="text-sm font-medium text-accent">You can only review this product after your order has been delivered.</p>
                        <a href="{{ route('orders.index') }}" class="text-xs font-bold text-primary hover:underline mt-2 inline-block">View My Orders</a>
                    </div>
                    @endif
                    @endif
                    @endauth

                    <div class="space-y-6">
                        @forelse($product->approvedReviews as $review)
                        <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs text-gray-400 font-medium">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            @php 
                                $hasPurchased = \App\Models\Order::where('user_id', $review->user_id)
                                    ->where('status', 'delivered')
                                    ->whereHas('items', function($q) use ($product) {
                                        $q->where('product_id', $product->id);
                                    })->exists();
                            @endphp
                            <div class="flex items-center gap-2 mb-3">
                                <p class="text-xs font-bold text-accent uppercase tracking-tight">by {{ $review->user->name }}</p>
                                @if($hasPurchased)
                                <span class="flex items-center gap-1 text-[10px] font-bold text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded-full uppercase tracking-widest">
                                    <i class="fa-solid fa-circle-check"></i>
                                    Verified Purchase
                                </span>
                                @endif
                            </div>
                            @if($review->title)<p class="font-medium text-gray-800 text-sm mb-1">{{ $review->title }}</p>@endif
                            @if($review->body)<p class="text-sm text-gray-600">{{ $review->body }}</p>@endif
                        </div>
                        @empty
                        <div class="text-center text-gray-500 py-4 text-sm">No reviews yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Recommendations --}}
            <div class="lg:col-span-3">
                <h3 class="font-medium text-gray-800 mb-4">Just For You</h3>
                <div class="grid grid-cols-1 gap-3">
                    @foreach($related->take(4) as $rel)
                    @include('partials.product-card',['product'=>$rel])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
