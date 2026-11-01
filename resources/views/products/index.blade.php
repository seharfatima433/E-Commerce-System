@extends('layouts.app')
@section('title','Collection | Exclusive')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative z-10">
    <div class="flex items-center gap-3 text-sm text-accent/50 mb-8 fade-in-up">
        <a href="{{ route('home') }}" class="hover:text-accent transition">Home</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-accent font-medium">Collection</span>
    </div>

    <div class="flex flex-col lg:flex-row gap-8" x-data="{ showFilters: false }">
        {{-- MOBILE FILTER TOGGLE --}}
        <div class="lg:hidden flex justify-between items-center bg-white/5 p-4 rounded-2xl border border-white/10 mb-4">
            <p class="text-sm font-bold text-accent">Adjust Preferences</p>
            <button @click="showFilters = !showFilters" class="btn-glass px-4 py-2 text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                <i class="fa-solid fa-filter"></i>
                <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
            </button>
        </div>

        {{-- SIDEBAR FILTERS --}}
        <aside class="lg:w-72 flex-shrink-0" :class="showFilters ? 'block' : 'hidden lg:block'">
            <div class="glass-panel rounded-3xl sticky top-24 shadow-3d border border-white/5 overflow-hidden">
                <div class="p-6 border-b border-white/5 flex justify-between items-center bg-white/5">
                    <h3 class="font-bold text-accent text-lg tracking-wide">Filters</h3>
                    <a href="{{ route('products.index') }}" class="text-xs text-accent/50 hover:text-white transition font-medium uppercase tracking-widest">Clear</a>
                </div>
                
                <form method="GET" action="{{ route('products.index') }}" id="filterForm">
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                    {{-- Category --}}
                    <div class="p-6 border-b border-white/5">
                        <h4 class="text-xs font-bold text-accent/60 uppercase tracking-widest mb-4">Category</h4>
                        <div class="space-y-3 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                            <label class="flex items-center gap-3 text-sm text-accent/80 cursor-pointer hover:text-white transition group">
                                <input type="radio" name="category" value="" {{ !request('category')?'checked':'' }} class="accent-primary-dark w-4 h-4 bg-surfaceLight border-white/10" onchange="this.form.submit()"> 
                                <span class="group-hover:translate-x-1 transition-transform">All Categories</span>
                            </label>
                            @foreach($categories as $cat)
                            <label class="flex items-center gap-3 text-sm text-accent/80 cursor-pointer hover:text-white transition group">
                                <input type="radio" name="category" value="{{ $cat->slug }}" {{ request('category')==$cat->slug?'checked':'' }} class="accent-primary-dark w-4 h-4" onchange="this.form.submit()">
                                <span class="group-hover:translate-x-1 transition-transform">{{ $cat->name }}</span>
                                <span class="text-accent/30 text-xs ml-auto">({{ $cat->active_products_count }})</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="p-6 border-b border-white/5">
                        <h4 class="text-xs font-bold text-accent/60 uppercase tracking-widest mb-4">Price Range</h4>
                        <div class="flex gap-3 items-center">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full bg-surfaceLight/50 border border-white/10 rounded-xl px-3 py-2 text-sm text-accent focus:outline-none focus:border-accent/30">
                            <span class="text-accent/30">-</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full bg-surfaceLight/50 border border-white/10 rounded-xl px-3 py-2 text-sm text-accent focus:outline-none focus:border-accent/30">
                            <button type="submit" class="w-10 h-10 rounded-xl bg-accent text-primary-dark hover:scale-105 transition flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-arrow-right text-sm"></i></button>
                        </div>
                    </div>

                    {{-- Rating --}}
                    <div class="p-6 border-b border-white/5">
                        <h4 class="text-xs font-bold text-accent/60 uppercase tracking-widest mb-4">Minimum Rating</h4>
                        <div class="space-y-3">
                            @foreach([4,3,2,1] as $r)
                            <label class="flex items-center gap-3 text-sm text-accent/80 cursor-pointer hover:text-white transition">
                                <input type="radio" name="rating" value="{{ $r }}" {{ request('rating')==$r?'checked':'' }} class="accent-primary-dark w-4 h-4" onchange="this.form.submit()">
                                <div class="flex text-xs gap-0.5">
                                    @for($i=1;$i<=5;$i++)<i class="fa-solid fa-star {{ $i<=$r?'text-accent':'text-accent/20' }}"></i>@endfor
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Toggles --}}
                    <div class="p-6 space-y-4 text-sm text-accent/80">
                        <label class="flex items-center gap-3 cursor-pointer hover:text-white transition">
                            <input type="checkbox" name="in_stock" value="1" {{ request('in_stock')?'checked':'' }} class="accent-primary-dark w-4 h-4 rounded border-white/10 bg-surfaceLight" onchange="this.form.submit()">
                            Available Now
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer hover:text-white transition">
                            <input type="checkbox" name="on_sale" value="1" {{ request('on_sale')?'checked':'' }} class="accent-primary-dark w-4 h-4 rounded border-white/10 bg-surfaceLight" onchange="this.form.submit()">
                            Special Offers
                        </label>
                    </div>
                </form>
            </div>
        </aside>

        {{-- MAIN PRODUCT GRID --}}
        <div class="flex-1 min-w-0 fade-in-up delay-200">
            {{-- Toolbar --}}
            <div class="glass-panel border border-white/5 rounded-2xl shadow-3d p-4 mb-8 flex flex-wrap items-center justify-between gap-4">
                <p class="text-accent/60 text-sm"><span class="text-accent font-bold text-lg mr-1">{{ $products->total() }}</span> curated pieces</p>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-accent/60 uppercase tracking-widest text-[10px] font-bold">Sort By</span>
                    <form method="GET" action="{{ route('products.index') }}">
                        @foreach(request()->except('sort') as $k=>$v) <input type="hidden" name="{{ $k }}" value="{{ $v }}"> @endforeach
                        <select name="sort" onchange="this.form.submit()" class="bg-surfaceLight/80 border border-white/10 rounded-xl px-4 py-2 text-sm text-accent focus:outline-none focus:border-accent/50 appearance-none pr-8 relative">
                            <option value="latest" {{ request('sort','latest')=='latest'?'selected':'' }}>Newest Additions</option>
                            <option value="price_asc" {{ request('sort')=='price_asc'?'selected':'' }}>Price: Accessible First</option>
                            <option value="price_desc" {{ request('sort')=='price_desc'?'selected':'' }}>Price: Premium First</option>
                            <option value="rating" {{ request('sort')=='rating'?'selected':'' }}>Highest Rated</option>
                        </select>
                    </form>
                </div>
            </div>

            @if($products->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
                @foreach($products as $product)
                @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="mt-12 flex justify-center theme-pagination">
                {{ $products->links() }}
            </div>
            @else
            <div class="glass-panel border border-white/5 rounded-3xl p-16 text-center shadow-3d">
                <div class="text-7xl mb-6 text-accent/10 animate-float"><i class="fa-solid fa-wind"></i></div>
                <h3 class="text-2xl font-bold text-accent mb-3">No pieces found</h3>
                <p class="text-accent/50 mb-8 max-w-md mx-auto">We couldn't find any items matching your current filters. Try adjusting your preferences.</p>
                <a href="{{ route('products.index') }}" class="btn-glass px-8 py-3 rounded-full font-bold">Reset Filters</a>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Tailored Pagination Styling to fit the dark theme */
.theme-pagination nav {
    background: rgba(42, 67, 50, 0.6) !important;
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 9999px;
    padding: 0.5rem;
    box-shadow: 0 20px 40px -10px rgba(0,0,0,0.5);
}
.theme-pagination p.text-sm { color: rgba(244, 247, 245, 0.6); }
.theme-pagination a, .theme-pagination span {
    color: #F4F7F5 !important;
    background: transparent !important;
    border: none !important;
}
.theme-pagination span[aria-current="page"] > span {
    background: #F4F7F5 !important;
    color: #16251D !important;
    border-radius: 9999px;
    font-weight: bold;
}
.theme-pagination a:hover {
    background: rgba(255,255,255,0.1) !important;
    border-radius: 9999px;
}
</style>
@endsection
