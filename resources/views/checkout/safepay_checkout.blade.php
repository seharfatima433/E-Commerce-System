@extends('layouts.app')

@section('title', 'Complete Payment')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-2xl mx-auto">

        {{-- Order Summary --}}
        <div class="bg-white rounded-xl shadow-sm p-4 mb-4 flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500">Order</p>
                <p class="font-semibold text-gray-800">#{{ $order->order_number }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Amount</p>
                <p class="font-bold text-green-600 text-lg">Rs. {{ number_format($order->total) }}</p>
            </div>
        </div>

        {{-- Safepay Iframe --}}
        <div id="iframe-container" class="bg-white rounded-xl shadow-sm overflow-hidden" style="height:600px;">
            <iframe
                id="safepay-frame"
                src="{{ $checkoutUrl }}"
                width="100%"
                height="100%"
                frameborder="0"
                allow="payment 'none'"
                sandbox="allow-scripts allow-same-origin allow-forms allow-popups allow-popups-to-escape-sandbox allow-top-navigation"
            ></iframe>
        </div>

        {{-- Success button --}}
        <div id="manual-btn" style="display:none;" class="text-center mt-4">
            <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                <div class="text-4xl mb-2">✅</div>
                <h3 class="text-green-700 font-bold text-lg mb-1">Payment Successful!</h3>
                <p class="text-gray-500 text-sm mb-4">Click below to complete your order</p>
                <a href="{{ route('checkout.safepay.callback') }}?order_id={{ $order->id }}&uid={{ $order->user_id }}"
                   class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-xl transition">
                    Complete My Order →
                </a>
            </div>
        </div>

        {{-- Sandbox Bypass / Simulate Payment --}}
        <div class="text-center mt-6 p-4 border border-blue-200 bg-blue-50 rounded-xl">
            <h4 class="text-blue-800 font-bold mb-2">Sandbox Developer Mode</h4>
            <p class="text-blue-600 text-sm mb-3">Safepay iframe is currently experiencing GPay init errors. Use this to simulate a successful payment.</p>
            <a href="{{ route('checkout.safepay.callback') }}?order_id={{ $order->id }}&uid={{ $order->user_id }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition shadow-sm">
                Simulate Successful Payment →
            </a>
        </div>

        {{-- Cancel --}}
        <div id="cancel-link" class="text-center mt-4">
            <a href="{{ route('cart.index') }}" class="text-sm text-gray-400 hover:text-gray-600 font-medium">
                ← Cancel and return to cart
            </a>
        </div>
    </div>
</div>

<script>
    var shown     = false;
    var loadCount = 0;

    function showCompleteButton() {
        if (shown) return;
        shown = true;
        document.getElementById('iframe-container').style.display = 'none';
        document.getElementById('manual-btn').style.display      = 'block';
        document.getElementById('cancel-link').style.display     = 'none';
    }

    // ✅ iframe load count — 2nd load = success page
    document.getElementById('safepay-frame').addEventListener('load', function() {
        loadCount++;
        console.log('iframe load count:', loadCount);
        if (loadCount >= 2) {
            showCompleteButton();
        }
    });

    // ✅ postMessage listener
    window.addEventListener('message', function(event) {
        if (!event.data) return;

        var dataStr = typeof event.data === 'string'
            ? event.data
            : JSON.stringify(event.data);

        // Ignore internal messages
        if (
            dataStr.includes('postrobot_hello') ||
            dataStr.includes('tmx_profiling') ||
            dataStr.includes('fireAndForget') ||
            dataStr.includes('GPAY') ||
            dataStr.includes('google')
        ) return;

        // Real success
        if (
            dataStr.includes('TRACKER_ENDED') ||
            dataStr.includes('payment:success') ||
            dataStr.includes('checkout:complete') ||
            dataStr.includes('"status":"paid"')
        ) {
            showCompleteButton();
        }
    });
</script>
@endsection