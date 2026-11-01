@extends('layouts.app')

@section('title', 'Payment Pending - Complete Your Order')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-8 text-center">

        {{-- Icon --}}
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- Heading --}}
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Payment Successful!</h1>
        <p class="text-gray-500 mb-2">Your payment has been processed by Safepay.</p>

        @if($order)
        <p class="text-sm text-gray-400 mb-6">Order: <span class="font-semibold text-gray-600">#{{ $order->order_number }}</span></p>
        @endif

        {{-- Complete Order Button --}}
        <a href="{{ route('checkout.safepay.callback') }}"
           class="block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-xl transition duration-200 mb-4">
            ✅ Complete My Order
        </a>

        <p class="text-xs text-gray-400">
            Click the button above to confirm your order and receive your order confirmation.
        </p>

    </div>
</div>
@endsection