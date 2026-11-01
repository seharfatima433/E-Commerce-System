<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Exclusive') | Premium</title>
    <meta name="description" content="@yield('meta_description', 'Make space for something better.')">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:  { DEFAULT: '#2D6A4F', hover: '#1B4332', light: '#D8F3DC', dark: '#081C15' },
                        surface: '#FFFFFF',
                        surfaceLight: '#F4F9F6',
                        accent: '#1B4332',
                        brandBlack: '#111111'
                    },
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    boxShadow: {
                        '3d': '0 20px 40px -10px rgba(45,106,79,0.15)',
                        '3d-hover': '0 30px 60px -12px rgba(45,106,79,0.25)',
                        'glass': '0 8px 32px 0 rgba(45,106,79, 0.1)',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #EBF4F0; color: #1B4332; overflow-x: hidden; }
        
        /* 3D and Glassmorphism Utilities */
        .glass-panel { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(45, 106, 79, 0.1); }
        .glass-nav { background: rgba(235, 244, 240, 0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-bottom: 1px solid rgba(45, 106, 79, 0.1); }
        
        .card-3d { transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.5s ease; transform-style: preserve-3d; perspective: 1000px; }
        .card-3d:hover { transform: translateY(-8px) rotateX(2deg) rotateY(-2deg); box-shadow: 0 30px 60px -12px rgba(0,0,0,0.7); }
        
        .product-pop { transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275); filter: drop-shadow(0 15px 25px rgba(0,0,0,0.4)); }
        .card-3d:hover .product-pop { transform: translateZ(60px) scale(1.1) translateY(-15px); filter: drop-shadow(0 25px 35px rgba(0,0,0,0.6)); }

        .btn-brand { background-color: #2D6A4F; color: white; transition: all 0.3s ease; border-radius: 9999px; }
        .btn-brand:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(45,106,79,0.3); background-color: #1B4332; }
        
        .btn-glass { background: rgba(45,106,79,0.1); backdrop-filter: blur(10px); color: #2D6A4F; border: 1px solid rgba(45,106,79,0.2); transition: all 0.3s ease; border-radius: 9999px; }
        .btn-glass:hover { background: rgba(45,106,79,0.2); transform: translateY(-2px); }

        [x-cloak] { display: none !important; }
        
        /* Global Contrast Fixes */
        .bg-primary, .bg-accent, .bg-primary-dark, .bg-brandBlack { color: white !important; }
        .bg-primary i, .bg-accent i, .bg-primary-dark i, .bg-brandBlack i { color: white !important; }
        .bg-primary p, .bg-accent p, .bg-primary-dark p, .bg-brandBlack p { color: rgba(255,255,255,0.7) !important; }
        .bg-primary h1, .bg-primary h2, .bg-primary h3, .bg-accent h1, .bg-accent h2, .bg-accent h3, .bg-brandBlack h1, .bg-brandBlack h2, .bg-brandBlack h3 { color: white !important; }
        ::-webkit-scrollbar { width: 8px; } ::-webkit-scrollbar-track { background:#EBF4F0; } ::-webkit-scrollbar-thumb { background:#2D6A4F; border-radius:4px; } ::-webkit-scrollbar-thumb:hover { background:#1B4332; }

        /* Animation Keyframes */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up { animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
    </style>
    @stack('styles')
</head>
<body class="antialiased selection:bg-accent selection:text-primary-dark">

<!-- ── TOP BAR ────────────────────────────────── -->
<div class="bg-primary text-xs text-white border-b border-primary-dark hidden md:block">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-10 flex justify-between items-center">
        <div class="flex gap-6">
            <a href="#" class="hover:text-primary-light transition flex items-center gap-2"><i class="fa-solid fa-gem text-[10px]"></i> Exclusive Premium</a>
        </div>
        <div class="flex gap-6">
            <a href="#" class="hover:text-primary-light transition">Track Order</a>
            @auth
                <a href="{{ route('account.dashboard') }}" class="hover:text-accent transition">{{ auth()->user()->name }}'s Space</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-accent transition">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:text-accent transition">Sign In</a>
                <a href="{{ route('register') }}" class="hover:text-accent transition">Create Account</a>
            @endauth
        </div>
    </div>
</div>

<!-- ── NAVBAR ────────────────────────────────────────────────────────────── -->
<nav class="glass-nav sticky top-0 z-50 transition-all duration-300" x-data="{ mobileOpen: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" :class="scrolled ? 'py-3' : 'py-5'">
        <div class="flex items-center justify-between gap-6">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center group-hover:scale-110 transition-transform duration-500 shadow-glass">
                    <i class="fa-solid fa-leaf text-white text-xl"></i>
                </div>
                <span class="font-bold text-2xl text-accent tracking-tight hidden sm:block">Exclusive</span>
            </a>

            <!-- Categories Dropdown (Desktop) -->
            <div class="relative hidden lg:block" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open" class="flex items-center gap-2 px-5 py-2.5 rounded-full btn-glass text-sm font-semibold hover:bg-primary hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-grid-2 text-xs"></i>
                    Categories
                    <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                </button>
                
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-2"
                     class="absolute top-full left-0 mt-4 w-64 glass-panel rounded-2xl shadow-3d overflow-hidden z-[60]" x-cloak>
                    <div class="p-2 space-y-1">
                        @foreach($categories as $cat)
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-primary/10 transition-all group">
                            <div class="w-8 h-8 rounded-lg bg-surfaceLight flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                                <i class="fa-solid fa-circle text-[6px]"></i>
                            </div>
                            <span class="text-sm font-medium text-accent">{{ $cat->name }}</span>
                        </a>
                        @endforeach
                        <div class="border-t border-primary/5 mt-2 pt-2">
                            <a href="{{ route('products.index') }}" class="flex items-center justify-center p-3 text-xs font-bold text-primary hover:bg-primary/5 rounded-xl transition-all uppercase tracking-widest">
                                View All Collections
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <form action="{{ route('products.search') }}" method="POST" class="flex-1 max-w-xl hidden md:flex items-center relative" x-data="liveSearch()" @click.away="isOpen = false">
                @csrf
                <div class="relative w-full group">
                    <input type="text" name="search" x-model="query" @input.debounce.300ms="fetchResults" @focus="isOpen = true" placeholder="Discover better things..." autocomplete="off"
                        class="w-full bg-white border border-primary/20 rounded-full pl-6 pr-12 py-3 text-sm text-accent placeholder-accent/40 focus:outline-none focus:border-primary focus:bg-white transition-all shadow-sm">
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-primary text-white rounded-full hover:scale-110 transition flex items-center justify-center">
                        <i class="fa-solid fa-search text-sm"></i>
                    </button>
                </div>
                
                <!-- Live Search Dropdown -->
                <div x-show="isOpen && (query.length > 0 || isLoading)" x-transition x-cloak class="absolute top-full left-0 right-0 mt-4 glass-panel rounded-2xl shadow-3d overflow-hidden z-50">
                    <div x-show="isLoading" class="p-6 text-center text-sm text-accent/60">
                        <i class="fa-solid fa-circle-notch fa-spin text-accent mr-2"></i> Unveiling...
                    </div>
                    <div x-show="!isLoading && results.length > 0" class="max-h-[60vh] overflow-y-auto custom-scrollbar p-2">
                        <template x-for="product in results" :key="product.id">
                            <a :href="'{{ url('products') }}/' + product.slug" class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/5 transition duration-300">
                                <div class="w-14 h-14 bg-accent/10 rounded-lg overflow-hidden flex-shrink-0 flex items-center justify-center">
                                    <template x-if="product.primary_image">
                                        <img :src="product.primary_image" :alt="product.name" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!product.primary_image">
                                        <i class="fa-solid fa-box text-accent/30 text-xl"></i>
                                    </template>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-accent truncate" x-text="product.name"></p>
                                    <p class="text-[10px] text-accent/50 uppercase tracking-widest mt-1" x-text="product.category.name"></p>
                                </div>
                                <div class="text-right pl-4">
                                    <p class="text-sm font-bold text-accent" x-text="'$' + Number(product.current_price).toLocaleString()"></p>
                                    <template x-if="product.is_on_sale">
                                        <p class="text-[10px] text-accent/40 line-through" x-text="'$' + Number(product.price).toLocaleString()"></p>
                                    </template>
                                </div>
                            </a>
                        </template>
                        <a :href="'{{ url('products') }}?search=' + query" class="block p-4 mt-2 text-center text-sm text-accent/80 font-medium hover:text-accent hover:bg-white/5 rounded-xl transition tracking-wide">
                            View All Findings
                        </a>
                    </div>
                    <div x-show="!isLoading && results.length === 0 && query.length > 0" class="p-8 text-center text-sm text-accent/50">
                        <i class="fa-solid fa-wind text-3xl text-accent/20 mb-4 block animate-pulse"></i>
                        Space empty for "<span x-text="query" class="text-accent"></span>"
                    </div>
                </div>
            </form>

            <!-- Right Actions -->
            <div class="flex items-center gap-5 flex-shrink-0">
                <a href="{{ route('account.wishlist') }}" class="w-10 h-10 rounded-full btn-glass flex items-center justify-center hidden sm:flex">
                    <i class="fa-regular fa-heart"></i>
                </a>
                
                <!-- Cart -->
                <a href="{{ route('cart.index') }}" class="relative w-10 h-10 rounded-full btn-glass flex items-center justify-center group">
                    <i class="fa-solid fa-cart-shopping group-hover:scale-110 transition-transform"></i>
                    <span id="cart-badge" class="absolute -top-1 -right-1 bg-brandBlack text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center hidden border border-white/20 shadow-lg">0</span>
                </a>

                <!-- Mobile menu toggle -->
                <button @click="mobileOpen = !mobileOpen" class="w-10 h-10 rounded-full btn-glass flex items-center justify-center md:hidden">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="md:hidden glass-panel absolute w-full border-t border-white/10 shadow-3d">
        <div class="px-4 py-4 space-y-3">
            <form action="{{ route('products.search') }}" method="POST" class="flex relative">
                @csrf
                <input type="text" name="search" placeholder="Search..." class="w-full bg-surfaceLight/50 border border-white/10 rounded-full pl-5 pr-10 py-2 text-xs text-accent focus:outline-none focus:border-accent/30">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 bg-accent text-primary-dark rounded-full text-[10px]"><i class="fa-solid fa-search"></i></button>
            </form>
            <div class="grid gap-1">
                <a href="{{ route('home') }}" class="p-2.5 rounded-xl hover:bg-white/5 text-xs text-accent font-medium transition">Home</a>
                
                <!-- Mobile Categories -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-2.5 rounded-xl hover:bg-white/5 text-xs text-accent font-medium transition">
                        Shop by Category
                        <i class="fa-solid fa-chevron-down text-[8px] transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition class="pl-4 space-y-0.5 mt-0.5">
                        @foreach($categories as $cat)
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="block p-2 text-[11px] text-accent/70 hover:text-primary transition">{{ $cat->name }}</a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('products.index') }}" class="p-2.5 rounded-xl hover:bg-white/5 text-xs text-accent font-medium transition">All Collections</a>
                @auth
                <a href="{{ route('account.dashboard') }}" class="p-3 rounded-xl hover:bg-white/5 text-accent font-medium transition">My Space</a>
                <a href="{{ route('orders.index') }}" class="p-3 rounded-xl hover:bg-white/5 text-accent font-medium transition">Orders</a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="p-3 rounded-xl bg-white/10 text-accent font-bold transition">Command Center</a>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full p-3 rounded-xl border border-red-500/30 text-red-400 font-medium hover:bg-red-500/10 transition">Disconnect</button>
                </form>
                @else
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <a href="{{ route('login') }}" class="btn-glass text-center py-3 text-sm font-medium">Sign In</a>
                    <a href="{{ route('register') }}" class="bg-accent text-primary-dark text-center py-3 rounded-full text-sm font-bold hover:bg-white transition">Create Account</a>
                </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
@if(session('success'))
<div id="flash-msg" class="fixed top-24 right-4 z-50 glass-panel border-l-4 border-l-accent text-accent px-6 py-4 rounded-xl shadow-3d flex items-center gap-4 max-w-sm fade-in-up">
    <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center">
        <i class="fa-solid fa-check text-accent"></i>
    </div>
    <p class="text-sm font-medium">{{ session('success') }}</p>
</div>
@endif

<!-- Main Content -->
<main class="min-h-screen relative z-10 overflow-hidden">
    <!-- Ambient Background Glows -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary-light/5 rounded-full blur-[120px] -z-10 pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-emerald-500/5 rounded-full blur-[150px] -z-10 pointer-events-none"></div>
    
    @yield('content')
</main>

<!-- ── FOOTER ─────────────────────────────────────────────────────────────── -->
<footer class="relative bg-surfaceLight pt-12 md:pt-20 pb-8 md:pb-10 border-t border-primary/10 mt-20 z-10">
    <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-accent/20 to-transparent"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-16">
            <div class="md:col-span-5">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center shadow-glass">
                        <i class="fa-solid fa-leaf text-white text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-accent text-3xl tracking-tight">Exclusive</h4>
                </div>
                <p class="text-accent/60 text-sm leading-relaxed max-w-sm mb-8">
                    Make space for something better. Curated collections for the modern aesthetic, designed to elevate your everyday living.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full btn-glass flex items-center justify-center hover:bg-accent hover:text-primary-dark"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full btn-glass flex items-center justify-center hover:bg-accent hover:text-primary-dark"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full btn-glass flex items-center justify-center hover:bg-accent hover:text-primary-dark"><i class="fab fa-dribbble"></i></a>
                </div>
            </div>
            
            <div class="md:col-span-2 md:col-start-8">
                <h4 class="font-bold text-accent mb-6 text-sm tracking-widest uppercase">Explore</h4>
                <ul class="space-y-4 text-sm text-accent/60">
                    <li><a href="#" class="hover:text-accent transition flex items-center gap-2 group"><span class="w-0 h-px bg-accent transition-all group-hover:w-4"></span> Collection</a></li>
                    <li><a href="#" class="hover:text-accent transition flex items-center gap-2 group"><span class="w-0 h-px bg-accent transition-all group-hover:w-4"></span> New Arrivals</a></li>
                    <li><a href="#" class="hover:text-accent transition flex items-center gap-2 group"><span class="w-0 h-px bg-accent transition-all group-hover:w-4"></span> Sustainability</a></li>
                </ul>
            </div>
            
            <div class="md:col-span-3">
                <h4 class="font-bold text-accent mb-6 text-sm tracking-widest uppercase">Assistance</h4>
                <ul class="space-y-4 text-sm text-accent/60">
                    <li><a href="#" class="hover:text-accent transition flex items-center gap-2 group"><span class="w-0 h-px bg-accent transition-all group-hover:w-4"></span> Care Center</a></li>
                    <li><a href="#" class="hover:text-accent transition flex items-center gap-2 group"><span class="w-0 h-px bg-accent transition-all group-hover:w-4"></span> Returns</a></li>
                    <li><a href="#" class="hover:text-accent transition flex items-center gap-2 group"><span class="w-0 h-px bg-accent transition-all group-hover:w-4"></span> Terms of Service</a></li>
                </ul>
            </div>
        </div>
        
        <div class="flex flex-col md:flex-row justify-between items-center pt-8 border-t border-white/5 text-xs text-accent/40">
            <p>&copy; {{ date('Y') }} Exclusive Premium. Crafted with precision.</p>
            <div class="flex items-center gap-2 mt-4 md:mt-0">
                <i class="fa-brands fa-cc-visa text-xl"></i>
                <i class="fa-brands fa-cc-mastercard text-xl"></i>
                <i class="fa-brands fa-cc-apple-pay text-xl"></i>
            </div>
        </div>
    </div>
</footer>

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Flash message dismissal
    const flash = document.getElementById('flash-msg');
    if (flash) {
        setTimeout(() => { 
            flash.style.opacity = '0'; 
            flash.style.transform = 'translateY(-20px)';
            flash.style.transition = 'all 0.5s cubic-bezier(0.16, 1, 0.3, 1)'; 
            setTimeout(() => flash.remove(), 500); 
        }, 4000);
    }

    // Cart Count
    fetch('{{ route('cart.count') }}').then(r => r.json()).then(data => {
        const badge = document.getElementById('cart-badge');
        if (badge && data.count > 0) {
            badge.textContent = data.count;
            badge.classList.remove('hidden');
            badge.classList.add('animate-bounce');
            setTimeout(() => badge.classList.remove('animate-bounce'), 1000);
        }
    }).catch(() => {});
    
    // Simple intersection observer for scroll animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.scroll-animate').forEach(el => observer.observe(el));
});

document.addEventListener('alpine:init', () => {
    Alpine.data('liveSearch', () => ({
        query: '',
        results: [],
        isLoading: false,
        isOpen: false,
        async fetchResults() {
            if (this.query.length < 2) {
                this.results = [];
                this.isOpen = false;
                return;
            }
            this.isLoading = true;
            this.isOpen = true;
            try {
                const response = await fetch(`{{ url('api/v1/products') }}?search=${encodeURIComponent(this.query)}&per_page=4`);
                const data = await response.json();
                this.results = data.data;
            } catch (error) {
                console.error('Search failed:', error);
            } finally {
                this.isLoading = false;
            }
        }
    }));
});
</script>
@stack('scripts')
</body>
</html>
