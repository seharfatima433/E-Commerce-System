<div class="card-3d bg-white rounded-3xl overflow-hidden group h-full flex flex-col relative border border-primary/8 transition-all duration-500 hover:border-primary/25 hover:shadow-3d">
    
    {{-- Image Area: white/light bg → dark text badges --}}
    <div class="relative overflow-hidden aspect-[4/3] flex items-center justify-center p-6 bg-gradient-to-b from-surfaceLight to-white">
        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full flex items-center justify-center z-10">
            @if($product->images && is_array($product->images) && count($product->images) > 0)
                <img src="{{ asset('storage/' . $product->images[0]) }}" 
                     alt="{{ $product->name }}" 
                     class="product-pop w-full h-full object-contain drop-shadow-lg">
            @else
                <i class="fa-solid fa-box-open text-6xl text-primary/20 product-pop"></i>
            @endif
        </a>

        {{-- Sale badge: dark green bg → white text --}}
        @if($product->is_on_sale)
        <div class="absolute top-4 left-4 z-20 bg-primary text-white text-[10px] font-black px-3 py-1 rounded-full shadow-lg uppercase tracking-widest">
            -{{ $product->discount_percent }}%
        </div>
        @endif

        {{-- Wishlist btn: light bg → dark icon --}}
        @auth
        <form action="{{ route('wishlist.toggle', $product) }}" method="POST" 
              class="absolute top-4 right-4 z-20 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
            @csrf
            <button type="submit" 
                    class="w-8 h-8 rounded-full bg-white border border-primary/15 shadow-sm flex items-center justify-center text-primary/50 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition-all duration-200">
                <i class="fa-solid fa-heart text-xs"></i>
            </button>
        </form>
        @endauth

        <!-- Subtle ambient glow -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 bg-primary/5 rounded-full blur-xl group-hover:bg-primary/10 transition-colors duration-500 pointer-events-none"></div>
    </div>

    {{-- Card Body: white bg → dark text --}}
    <div class="p-5 pt-3 flex-1 flex flex-col bg-white z-20">
        
        {{-- Stars: visible on white bg --}}
        <div class="flex items-center gap-1 mb-2">
            <div class="flex text-[10px] gap-0.5">
                @for($i = 1; $i <= 5; $i++)
                <i class="fa-solid fa-star {{ $i <= round($product->rating) ? 'text-amber-400' : 'text-gray-200' }}"></i>
                @endfor
            </div>
            <span class="text-[10px] text-accent/45 font-medium">({{ $product->reviews_count }})</span>
        </div>

        {{-- Product Name: dark text on white --}}
        <a href="{{ route('products.show', $product->slug) }}" 
           class="font-bold text-accent hover:text-primary transition-colors duration-200 text-base leading-tight line-clamp-2 flex-1 mb-2" 
           title="{{ $product->name }}">
            {{ $product->name }}
        </a>

        {{-- Price + Cart: white bg → dark text --}}
        <div class="mt-auto pt-4 border-t border-primary/8 flex items-end justify-between gap-4">
            <div>
                @if($product->is_on_sale)
                <span class="text-xs text-accent/40 line-through block mb-0.5">Rs. {{ number_format($product->price) }}</span>
                <span class="text-xl font-black text-accent">Rs. {{ number_format($product->sale_price) }}</span>
                @else
                <span class="text-xl font-black text-accent">Rs. {{ number_format($product->price) }}</span>
                @endif
            </div>

            <div class="relative">
                @if($product->is_in_stock)
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    {{-- Cart btn: dark green bg → white icon --}}
                    <button type="submit" 
                            class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center hover:bg-accent hover:scale-110 transition-all duration-200 shadow-md hover:shadow-primary/30">
                        <i class="fa-solid fa-cart-plus text-sm"></i>
                    </button>
                </form>
                @else
                <button disabled 
                        class="w-10 h-10 rounded-full bg-gray-100 text-gray-300 flex items-center justify-center cursor-not-allowed border border-gray-200">
                    <i class="fa-solid fa-ban text-sm"></i>
                </button>
                @endif
            </div>
        </div>
    </div>
</div>