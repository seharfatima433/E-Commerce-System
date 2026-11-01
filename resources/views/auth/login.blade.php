@extends('layouts.app')
@section('title','Login | Shopora')
@section('content')
<div class="bg-gray-50 py-10 min-h-[calc(100vh-200px)] flex items-center justify-center relative overflow-hidden" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
    
    <!-- Decorative background elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-orange-400/10 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-md relative z-10 transition-all duration-700 ease-out transform"
         :class="show ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-12 scale-95'">
        
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/50 p-8 sm:p-10">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-user-lock text-2xl text-primary"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Welcome Back</h1>
                <p class="text-sm text-gray-500 mt-2">Sign in to continue to Shopora</p>
            </div>
            
            @if($errors->any())
            <div class="mb-6 bg-red-50/80 backdrop-blur-sm border-l-4 border-red-500 text-red-700 p-4 rounded-r shadow-sm text-sm animate-pulse">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div class="relative group">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1 transition-colors group-focus-within:text-primary">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-regular fa-envelope text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-gray-50/50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all duration-300 placeholder-gray-400" placeholder="name@example.com">
                    </div>
                </div>
                
                <div class="relative group">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1 transition-colors group-focus-within:text-primary">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input type="password" name="password" required class="w-full bg-gray-50/50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all duration-300 placeholder-gray-400" placeholder="••••••••">
                    </div>
                </div>
                
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer group">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="remember" class="peer sr-only">
                            <div class="w-4 h-4 border-2 border-gray-300 rounded peer-checked:bg-primary peer-checked:border-primary transition-colors"></div>
                            <i class="fa-solid fa-check text-white text-[10px] absolute inset-0 m-auto opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                        </div>
                        <span class="group-hover:text-gray-800 transition-colors">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-primary hover:text-primary-hover font-medium transition-colors">Forgot Password?</a>
                </div>
                
                <button type="submit" class="w-full bg-gradient-to-r from-primary to-orange-500 hover:from-orange-500 hover:to-primary text-white font-bold py-3.5 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2 mt-2">
                    <span>SIGN IN</span>
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>
            
            <div class="mt-8 text-center text-sm text-gray-600 border-t border-gray-100 pt-6">
                Don't have an account? <a href="{{ route('register') }}" class="text-primary hover:text-primary-hover font-semibold transition-colors">Create one now</a>
            </div>


        </div>
    </div>
</div>
@endsection
