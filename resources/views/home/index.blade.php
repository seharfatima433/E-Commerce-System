@extends('layouts.app')
@section('title', 'Shopora - Premium Collections')
@section('content')

{{-- ── HERO SECTION ───────────────────────────────────────── --}}
<div class="relative min-h-[85vh] flex items-center pt-10 pb-20 overflow-hidden">
    <!-- bg-primary-dark (#081C15) = very dark → WHITE text -->
    <div class="absolute inset-0 bg-primary-dark -z-20"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-primary/20 rounded-full blur-[100px] -z-10 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            {{-- Left Content: dark bg → white text --}}
            <div class="space-y-6 md:space-y-8 fade-in-up">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 md:px-4 md:py-2 rounded-full border border-white/20 bg-white/10 text-white text-[10px] md:text-xs font-semibold tracking-widest uppercase mb-2 md:mb-4">
                    <span class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-primary-light animate-pulse"></span>
                    Premium Collection
                </div>

                <h1 class="text-3xl md:text-6xl lg:text-7xl font-bold text-white leading-[1.1] tracking-tight">
                    Make Space for <br class="hidden md:block"/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-light via-white to-primary-light animate-gradient-x">Something Better</span>
                </h1>

                <p class="text-sm md:text-lg text-white/70 max-w-lg leading-relaxed">
                    Discover premium furniture and lifestyle pieces designed to elevate your everyday environment. Crafted with precision, styled for the modern aesthetic.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 md:gap-4 pt-2 md:pt-4">
                    <a href="{{ route('products.index') }}"
                       class="btn-brand px-6 py-3 md:px-8 md:py-4 text-center font-bold tracking-wide shadow-3d hover:shadow-3d-hover text-white text-xs md:text-sm">
                        Explore Collection
                    </a>
                    <a href="{{ route('products.index', ['on_sale' => 1]) }}"
                       class="px-6 py-3 md:px-8 md:py-4 text-center font-bold tracking-wide rounded-full border-2 border-white/40 text-white hover:bg-white/10 hover:border-white/70 transition-all duration-300 text-xs md:text-sm">
                        View Offers
                    </a>
                </div>
            </div>

            {{-- Right Decorative Card --}}
            <div class="relative h-[400px] md:h-[600px] flex items-center justify-center fade-in-up delay-200">
                <div class="card-3d w-full max-w-[280px] md:max-w-md aspect-[4/5] bg-white rounded-[3rem] md:rounded-[4rem] shadow-3d relative cursor-pointer group border border-white/20 overflow-hidden flex items-center justify-center">
 
                    {{-- Decorative badge --}}
                    <div class="absolute top-8 left-8 z-20 bg-primary/10 px-4 py-2 rounded-full text-primary font-bold text-[10px] md:text-xs border border-primary/20">
                        Premium Quality
                    </div>
 
                    {{-- Chair Image - Now blends with white card --}}
                    <div class="relative w-full h-full flex justify-center items-center z-10 p-0">
                        <img src="https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?q=80&w=1000&auto=format&fit=crop"
                             alt="Premium Chair"
                             class="product-pop w-full h-full object-cover pointer-events-none transform scale-110 group-hover:scale-125 transition-transform duration-700">
                    </div>
 
                    {{-- Soft Overlay for depth --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/5 to-transparent pointer-events-none"></div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ── BELOW HERO: light bg (#EBF4F0) → dark text ──────────────────────── --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">

    {{-- ── FLASH SALE ─────────────────────────────────────────────────────────── --}}
    @if($onSale->count())
    <div class="mb-16 md:mb-24 scroll-animate relative">
        <div class="absolute -top-20 -right-20 w-64 h-64 bg-primary/10 rounded-full blur-3xl -z-10"></div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 md:mb-8 gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100 text-red-600 text-[10px] md:text-xs font-bold mb-2 md:mb-3 border border-red-200">
                    <i class="fa-solid fa-fire animate-pulse"></i> Limited Time
                </div>
                <h3 class="text-2xl md:text-3xl font-bold text-accent">Exclusive Offers</h3>
            </div>

            {{-- Countdown: light bg → dark text; digits: dark bg → white --}}
            <div class="flex items-center gap-3 bg-white px-3 py-1.5 md:px-4 md:py-2 rounded-xl border border-primary/10 shadow-sm">
                <span class="text-accent/70 text-[10px] md:text-sm font-medium">Ends in:</span>
                <div class="flex items-center gap-1 font-mono font-bold text-xs md:text-sm">
                    <span class="bg-primary-dark text-white px-1.5 py-0.5 md:px-2 md:py-1 rounded shadow-inner">12</span>
                    <span class="text-accent/50">:</span>
                    <span class="bg-primary-dark text-white px-1.5 py-0.5 md:px-2 md:py-1 rounded shadow-inner">34</span>
                    <span class="text-accent/50">:</span>
                    <span class="bg-primary-dark text-white px-1.5 py-0.5 md:px-2 md:py-1 rounded shadow-inner">56</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($onSale->take(4) as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── DISCOVER MORE ──────────────────────────────────────────────────────── --}}
    <div class="scroll-animate relative">
        <div class="absolute top-40 -left-20 w-80 h-80 bg-primary/5 rounded-full blur-3xl -z-10"></div>

        <div class="text-center mb-12">
            <h3 class="text-3xl font-bold text-accent mb-4">Discover More</h3>
            <p class="text-accent/65 max-w-xl mx-auto">Handpicked selections based on the modern aesthetic.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
            @foreach($newArrivals->take(4) as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>

        <div class="mt-16 text-center">
            <a href="{{ route('products.index') }}"
               class="inline-block btn-glass px-12 py-4 rounded-full font-bold transition-all hover:px-14 text-accent">
                Explore Entire Collection <i class="fa-solid fa-arrow-right ml-2 text-sm"></i>
            </a>
        </div>
    </div>

</div>

<style>
@keyframes shimmer {
    100% { transform: translateX(200%); }
}
</style>
@endsection