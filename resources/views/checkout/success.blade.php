@extends('layouts.app')
@section('title','Order Confirmed | Shopora')
@section('content')
<div class="py-12 md:py-20 min-h-[calc(100vh-300px)] relative overflow-hidden">
    {{-- Decorative blobs --}}
    <div class="absolute top-20 right-0 w-96 h-96 bg-primary/5 rounded-full blur-[100px] -z-10"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary/5 rounded-full blur-[100px] -z-10"></div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="glass-panel rounded-[2rem] md:rounded-[3rem] p-8 md:p-12 text-center mb-8 shadow-3d border border-white/5 relative overflow-hidden">
            <div class="w-16 h-16 md:w-20 md:h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 border border-primary/20 animate-bounce-slow">
                <i class="fa-solid fa-check text-3xl md:text-4xl text-primary"></i>
            </div>
            
            <h1 class="text-3xl md:text-5xl font-black text-accent tracking-tighter uppercase leading-tight mb-4">Investment Secured</h1>
            <p class="text-sm md:text-lg text-accent/60 font-medium mb-8">Thank you for choosing quality. Your order has been received and is now being processed by our team.</p>
            
            <div class="inline-flex flex-col items-center gap-2 bg-surfaceLight/50 px-8 py-4 rounded-3xl border border-white/10 shadow-inner">
                <p class="text-[10px] font-black text-accent/40 uppercase tracking-[0.2em]">Order Reference</p>
                <p class="text-xl md:text-2xl font-black text-primary tracking-widest">#{{ $order->order_number ?? $order->id }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="glass-panel rounded-3xl p-6 md:p-8 shadow-3d border border-white/5">
                <h3 class="text-[10px] font-black text-accent/40 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-primary"></i> Shipping To
                </h3>
                <p class="text-sm font-bold text-accent mb-1">{{ $order->shipping_name }}</p>
                <p class="text-xs text-accent/60 leading-relaxed mb-4">{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
                <div class="flex items-center gap-2 text-[10px] font-bold text-accent/40">
                    <i class="fa-solid fa-phone-flip text-[8px]"></i> {{ $order->shipping_phone }}
                </div>
            </div>

            <div class="glass-panel rounded-3xl p-6 md:p-8 shadow-3d border border-white/5">
                <h3 class="text-[10px] font-black text-accent/40 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-wallet text-primary"></i> Payment Data
                </h3>
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-bold text-accent uppercase tracking-tight">{{ str_replace('_',' ',$order->payment_method) }}</p>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest {{ $order->payment_status === 'paid' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500' }}">
                        {{ $order->payment_status }}
                    </span>
                </div>
                <p class="text-xs text-accent/40 leading-relaxed">
                    @if($order->payment_method === 'cash_on_delivery')
                        Please have the exact amount ready upon delivery for a smooth experience.
                    @else
                        Payment confirmed via secure gateway.
                    @endif
                </p>
            </div>
        </div>

        <div class="text-center space-y-4">
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('orders.index') }}" class="w-full sm:w-auto px-10 py-4 bg-accent text-primary-dark font-black text-sm rounded-full shadow-3d hover:scale-105 transition-transform uppercase tracking-widest">
                    Track Journey
                </a>
                <a href="{{ route('home') }}" class="w-full sm:w-auto px-10 py-4 border-2 border-accent/20 text-accent font-black text-sm rounded-full hover:bg-accent/5 transition-all uppercase tracking-widest">
                    Continue Exploring
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
