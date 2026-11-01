<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | Shopora Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config={
            theme:{
                extend:{
                    colors:{
                        primary:{DEFAULT:'#f85606',hover:'#d94905'},
                        gray:{50:'#f5f5f5',100:'#eeeeee',800:'#333333',900:'#1a1a1a'}
                    },
                    fontFamily:{sans:['Inter','sans-serif']},
                    boxShadow:{'daraz':'0 2px 4px 0 rgba(0,0,0,.08)'}
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body{font-family:'Inter',sans-serif;}
        .sidebar-link{display:flex;align-items:center;gap:12px;padding:10px 16px;border-radius:8px;font-size:.875rem;font-weight:500;color:#4b5563;transition:all .2s;}
        .sidebar-link:hover,.sidebar-link.active{background:#ffede5;color:#f85606;}
        .sidebar-link.active i{color:#f85606;}
        [x-cloak]{display:none!important;}
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased" x-data="{sidebarOpen:true}">
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside :class="sidebarOpen?'w-64':'w-16'" class="bg-white border-r border-gray-200 flex flex-col transition-all duration-300 flex-shrink-0 shadow-sm z-10">
        <div class="h-16 flex items-center px-4 border-b border-gray-200 gap-3 flex-shrink-0">
            <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-bag-shopping text-white text-sm"></i>
            </div>
            <span x-show="sidebarOpen" class="font-extrabold text-xl text-primary whitespace-nowrap tracking-tight">Shopora</span>
        </div>
        <nav class="flex-1 overflow-y-auto p-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard')?'active':'' }}">
                <i class="fa-solid fa-gauge fa-fw"></i><span x-show="sidebarOpen">Dashboard</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products*')?'active':'' }}">
                <i class="fa-solid fa-box fa-fw"></i><span x-show="sidebarOpen">Products</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories*')?'active':'' }}">
                <i class="fa-solid fa-layer-group fa-fw"></i><span x-show="sidebarOpen">Categories</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders*')?'active':'' }}">
                <i class="fa-solid fa-shopping-bag fa-fw"></i><span x-show="sidebarOpen">Orders</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users*')?'active':'' }}">
                <i class="fa-solid fa-users fa-fw"></i><span x-show="sidebarOpen">Users</span>
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="sidebar-link {{ request()->routeIs('admin.coupons*')?'active':'' }}">
                <i class="fa-solid fa-tag fa-fw"></i><span x-show="sidebarOpen">Coupons</span>
            </a>
            <div class="border-t border-gray-200 my-2"></div>
            <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
                <i class="fa-solid fa-arrow-up-right-from-square fa-fw"></i><span x-show="sidebarOpen">View Store</span>
            </a>
        </nav>
        <div class="p-3 border-t border-gray-200 flex-shrink-0">
            <div class="flex items-center gap-3 px-2 py-2">
                <img src="{{ auth()->user()->avatar_url }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0" alt="">
                <div x-show="sidebarOpen" class="min-w-0">
                    <p class="text-xs font-bold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-primary">Administrator</p>
                </div>
            </div>
        </div>
    </aside>
    <!-- Main area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-gray-50">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 flex-shrink-0 shadow-sm z-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen=!sidebarOpen" class="text-gray-500 hover:text-primary transition">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="font-bold text-lg text-gray-800">@yield('page-title','Dashboard')</h1>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs text-gray-500 font-medium bg-gray-100 px-3 py-1 rounded-full">{{ now()->format('D, d M Y') }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="text-sm font-medium text-gray-500 hover:text-red-500 transition flex items-center gap-2">
                        <i class="fa-solid fa-right-from-bracket"></i><span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </header>
        @if(session('success'))
        <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-circle-check text-green-500"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-circle-xmark text-red-500"></i> {{ session('error') }}
        </div>
        @endif
        <main class="flex-1 overflow-y-auto p-6">@yield('content')</main>
    </div>
</div>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
@stack('scripts')
</body>
</html>
