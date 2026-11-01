@extends('layouts.app')
@section('title','Shopping Cart | Shopora')
@section('content')
<div class="py-6 md:py-12 min-h-screen relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if($cart->items->isEmpty())
        <div class="glass-panel rounded-3xl md:rounded-[3rem] p-10 md:p-20 text-center shadow-3d border border-white/5 animate-float">
            <div class="w-24 h-24 md:w-40 md:h-40 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-6 md:mb-8 border border-primary/10">
                <i class="fa-solid fa-cart-shopping text-4xl md:text-6xl text-primary/20"></i>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-accent mb-4 uppercase tracking-tighter">Space Empty</h2>
            <p class="text-accent/50 mb-8 md:mb-10 max-w-sm mx-auto uppercase tracking-widest text-[10px] font-bold leading-loose">Continue shopping to find pieces that resonate with your aesthetic.</p>
            <a href="{{ route('products.index') }}" class="btn-brand px-10 py-3 md:px-12 md:py-4 rounded-full font-bold uppercase tracking-widest text-[10px]">Explore Collections</a>
        </div>
        @else
        <h1 class="text-2xl md:text-4xl font-black text-accent mb-6 md:mb-10 uppercase tracking-tighter fade-in-up">Shopping <span class="text-primary">Cart</span> <span class="text-accent/20">({{ $cart->item_count }})</span></h1>

        <div class="flex flex-col lg:flex-row gap-6 md:gap-10">
            {{-- Cart Items --}}
            <div class="lg:flex-1 space-y-4 md:space-y-6 fade-in-up delay-100">
                <div class="glass-panel rounded-2xl md:rounded-3xl shadow-3d border border-white/5 overflow-hidden">
                    <div class="divide-y divide-white/5">
                        @foreach($cart->items as $item)
                        <div class="p-4 md:p-8 flex flex-row gap-4 md:gap-8 group transition-all duration-500 hover:bg-white/5">
                            {{-- Image Container - Fixed smaller size on mobile --}}
                            <div class="w-24 h-24 md:w-40 md:h-40 bg-white rounded-xl md:rounded-[2rem] border border-white/10 flex flex-shrink-0 items-center justify-center overflow-hidden relative p-2">
                                @if($item->product->images && is_array($item->product->images) && count($item->product->images) > 0)
                                    <img src="{{ asset('storage/' . $item->product->images[0]) }}" alt="{{ $item->product->name }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <i class="fa-solid fa-box text-accent/10 text-2xl md:text-4xl"></i>
                                @endif
                            </div>
                            
                            <div class="flex-1 min-w-0 flex flex-col justify-between">
                                <div class="flex flex-col md:flex-row justify-between gap-1 md:gap-4 items-start">
                                    <div class="max-w-md">
                                        <a href="{{ route('products.show',$item->product->slug) }}" class="text-sm md:text-xl font-bold text-accent hover:text-primary transition-colors leading-tight line-clamp-2 uppercase tracking-tight mb-1">{{ $item->product->name }}</a>
                                        <p class="text-[8px] md:text-[10px] text-accent/40 uppercase tracking-[0.2em] font-bold">{{ $item->product->brand ?? 'Exclusive Collection' }}</p>
                                    </div>
                                    <div class="text-left md:text-right flex-shrink-0 mt-1 md:mt-0">
                                        <p class="text-lg md:text-2xl font-black text-accent tracking-tighter">Rs. {{ number_format($item->price) }}</p>
                                        @if($item->product->is_on_sale)
                                        <div class="flex items-center gap-2 mt-0.5 justify-start md:justify-end">
                                            <span class="text-[10px] text-accent/30 line-through">Rs. {{ number_format($item->product->price) }}</span>
                                            <span class="text-emerald-500 text-[8px] md:text-[10px] font-black uppercase">-{{ $item->product->discount_percent }}%</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center mt-4 md:mt-8 pt-3 md:pt-6 border-t border-white/5">
                                    <form action="{{ route('cart.remove',$item) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-accent/30 hover:text-red-400 text-[8px] md:text-[10px] font-black uppercase tracking-[0.2em] flex items-center gap-2 transition-all">
                                            <i class="fa-solid fa-trash-can text-xs"></i> <span class="hidden sm:inline">Remove</span>
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('cart.update',$item) }}" method="POST" class="flex items-center bg-surfaceLight rounded-full p-0.5 border border-white/10">
                                        @csrf @method('PATCH')
                                        <button name="quantity" value="{{ max(1,$item->quantity-1) }}" class="w-7 h-7 md:w-10 md:h-10 flex items-center justify-center rounded-full text-accent/40 hover:bg-white/10 hover:text-primary transition-all"><i class="fa-solid fa-minus text-[8px] md:text-[10px]"></i></button>
                                        <span class="w-8 md:w-12 text-center text-xs md:text-sm font-black text-accent">{{ $item->quantity }}</span>
                                        <button name="quantity" value="{{ $item->quantity+1 }}" class="w-7 h-7 md:w-10 md:h-10 flex items-center justify-center rounded-full text-accent/40 hover:bg-white/10 hover:text-primary transition-all"><i class="fa-solid fa-plus text-[8px] md:text-[10px]"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="lg:w-[400px] fade-in-up delay-200">
                <div class="glass-panel rounded-2xl md:rounded-[2.5rem] shadow-3d border border-white/5 p-6 md:p-8 sticky top-24">
                    <h3 class="text-lg md:text-xl font-bold text-accent mb-6 md:mb-8 uppercase tracking-tighter">Summary</h3>
                    
                    <div class="space-y-3 md:space-y-4 text-[12px] md:text-sm text-accent/50 mb-6 md:mb-8 pb-6 md:pb-8 border-b border-white/5">
                        <div class="flex justify-between"><span>Subtotal ({{ $cart->item_count }} Items)</span><span class="text-accent font-bold">Rs. {{ number_format($cart->subtotal) }}</span></div>
                        <div class="flex justify-between"><span>Logistics</span><span class="text-primary font-bold uppercase tracking-widest text-[9px] md:text-[10px]">{{ $cart->subtotal >= 10000 ? 'Complimentary' : 'Rs. 299' }}</span></div>
                        @if($cart->discount>0)<div class="flex justify-between text-emerald-500 font-bold"><span>Voucher Savings</span><span>- Rs. {{ number_format($cart->discount) }}</span></div>@endif
                    </div>
                    
                    <div class="flex justify-between items-end mb-6 md:mb-10 bg-primary/5 p-4 md:p-6 rounded-2xl md:rounded-[2rem] border border-primary/10">
                        <div>
                            <p class="text-[9px] md:text-[10px] text-primary/60 font-black uppercase tracking-[0.2em] mb-1 md:mb-2">Total</p>
                            <span class="text-2xl md:text-3xl font-black text-accent block leading-none tracking-tighter">Rs. {{ number_format($cart->total + ($cart->subtotal >= 10000 ? 0 : 299)) }}</span>
                        </div>
                    </div>
                    
                    @auth
                    <a href="{{ route('checkout.index') }}" class="w-full btn-brand py-3.5 md:py-5 font-bold text-sm md:text-base shadow-3d hover:shadow-3d-hover transition-all flex items-center justify-center gap-3">
                        CHECKOUT
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="w-full btn-brand py-3.5 md:py-5 font-bold text-sm md:text-base shadow-3d hover:shadow-3d-hover transition-all flex items-center justify-center gap-3">
                        CONNECT
                        <i class="fa-solid fa-user text-xs"></i>
                    </a>
                    @endauth

                    <div class="mt-6 md:mt-10 pt-6 md:pt-8 border-t border-white/5">
                        @if($cart->coupon_code)
                        <div class="flex justify-between items-center bg-emerald-500/10 px-4 py-3 md:px-5 md:py-4 border border-emerald-500/20 rounded-xl md:rounded-2xl">
                            <span class="text-emerald-500 text-[9px] md:text-[10px] font-black uppercase tracking-widest"><i class="fa-solid fa-tag mr-2"></i> {{ $cart->coupon_code }} Active</span>
                            <form action="{{ route('cart.coupon.remove') }}" method="POST">@csrf @method('DELETE')
                                <button class="text-accent/30 hover:text-red-500 text-[9px] md:text-[10px] font-black uppercase tracking-widest">Remove</button>
                            </form>
                        </div>
                        @else
                        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2 md:gap-3">
                            @csrf
                            <input type="text" name="coupon_code" placeholder="VOUCHER" class="flex-1 bg-surfaceLight/50 border border-white/10 rounded-xl md:rounded-2xl px-4 py-2 md:px-5 md:py-3 text-[10px] text-accent focus:outline-none focus:border-primary uppercase tracking-widest font-bold">
                            <button class="bg-accent text-white px-4 md:px-6 py-2 md:py-3 rounded-xl md:rounded-2xl text-[9px] md:text-[10px] font-black uppercase tracking-widest transition-all hover:bg-primary">Apply</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
