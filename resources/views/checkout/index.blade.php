@extends('layouts.app')
@section('title','Checkout | Shopora')
@section('content')
<div class="bg-gray-100 py-6 min-h-[calc(100vh-200px)]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ session('error') }}
        </div>
        @endif

        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded text-sm shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
        @endif

        <form id="checkout-form" onsubmit="this.action = (document.querySelector('input[name=payment_method]:checked').value === 'card') ? '{{ route('checkout.safepay.initiate') }}' : '{{ route('checkout.process') }}';" method="POST" x-data="{ method: '{{ old('payment_method', 'cash_on_delivery') }}' }">
            @csrf
            <div class="flex flex-col lg:flex-row gap-8">
                
                {{-- Left Column: Shipping & Payment --}}
                <div class="lg:flex-1 space-y-4 md:space-y-6">
                    {{-- Shipping Info --}}
                    <div class="glass-panel rounded-2xl md:rounded-3xl shadow-3d border border-white/5 p-5 md:p-8">
                        <h2 class="text-lg md:text-xl font-bold text-accent mb-4 md:mb-6 flex items-center gap-3">
                            <div class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-primary/10 flex items-center justify-center">
                                <i class="fa-solid fa-truck-fast text-primary text-xs md:text-sm"></i>
                            </div>
                            Shipping
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-accent/50 uppercase tracking-widest mb-1.5">Full Name</label>
                                <input type="text" name="name" value="{{ old('name',$user->name) }}" required class="w-full bg-surfaceLight/50 border border-white/10 rounded-xl md:rounded-2xl px-4 py-2 md:px-5 md:py-3 text-xs md:text-sm text-accent focus:outline-none focus:border-primary transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-accent/50 uppercase tracking-widest mb-1.5">Email Address</label>
                                <input type="email" name="email" value="{{ old('email',$user->email) }}" required class="w-full bg-surfaceLight/50 border border-white/10 rounded-xl md:rounded-2xl px-4 py-2 md:px-5 md:py-3 text-xs md:text-sm text-accent focus:outline-none focus:border-primary transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-accent/50 uppercase tracking-widest mb-1.5">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone',$user->phone) }}" required class="w-full bg-surfaceLight/50 border border-white/10 rounded-xl md:rounded-2xl px-4 py-2 md:px-5 md:py-3 text-xs md:text-sm text-accent focus:outline-none focus:border-primary transition-all" placeholder="03xx-xxxxxxx">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-accent/50 uppercase tracking-widest mb-1.5">Street Address</label>
                                <input type="text" name="address" value="{{ old('address',$user->address) }}" required class="w-full bg-surfaceLight/50 border border-white/10 rounded-xl md:rounded-2xl px-4 py-2 md:px-5 md:py-3 text-xs md:text-sm text-accent focus:outline-none focus:border-primary transition-all" placeholder="House no. / building / street / area">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-accent/50 uppercase tracking-widest mb-1.5">City</label>
                                <input type="text" name="city" value="{{ old('city',$user->city) }}" required class="w-full bg-surfaceLight/50 border border-white/10 rounded-xl md:rounded-2xl px-4 py-2 md:px-5 md:py-3 text-xs md:text-sm text-accent focus:outline-none focus:border-primary transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-accent/50 uppercase tracking-widest mb-1.5">Postal Code</label>
                                <input type="text" name="postal_code" value="{{ old('postal_code',$user->postal_code) }}" required class="w-full bg-surfaceLight/50 border border-white/10 rounded-xl md:rounded-2xl px-4 py-2 md:px-5 md:py-3 text-xs md:text-sm text-accent focus:outline-none focus:border-primary transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="glass-panel rounded-2xl md:rounded-3xl shadow-3d border border-white/5 p-5 md:p-8">
                        <h2 class="text-lg md:text-xl font-bold text-accent mb-4 md:mb-6 flex items-center gap-3">
                            <div class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-primary/10 flex items-center justify-center">
                                <i class="fa-solid fa-credit-card text-primary text-xs md:text-sm"></i>
                            </div>
                            Payment
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                            @foreach([
                                ['cash_on_delivery','fa-money-bill-wave','COD', 'Pay at door'],
                                ['card','fa-credit-card','Card', 'Pay online']
                            ] as [$val,$icon,$label,$desc])
                            <label :class="method==='{{ $val }}'?'border-primary bg-primary/5 ring-2 ring-primary/20':'border-white/10 bg-surfaceLight/30 hover:bg-white/5'" 
                                   class="flex items-center gap-3 md:gap-4 border rounded-xl md:rounded-2xl p-4 md:p-5 cursor-pointer transition-all duration-300 group">
                                <div class="relative flex items-center justify-center">
                                    <input type="radio" name="payment_method" value="{{ $val }}" @change="method='{{ $val }}'" {{ old('payment_method','cash_on_delivery')===$val?'checked':'' }} class="sr-only">
                                    <div class="w-4 h-4 md:w-5 md:h-5 rounded-full border-2 border-primary/30 flex items-center justify-center group-hover:border-primary transition-colors" :class="method==='{{ $val }}'?'border-primary':''">
                                        <div class="w-2 md:w-2.5 h-2 md:h-2.5 rounded-full bg-primary transition-transform duration-300" :class="method==='{{ $val }}'?'scale-100':'scale-0'"></div>
                                    </div>
                                </div>
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-white/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="fa-solid {{ $icon }} text-lg md:text-xl" :class="method==='{{ $val }}'?'text-primary':'text-accent/40'"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold text-xs md:text-sm text-accent">{{ $label }}</p>
                                    <p class="text-[8px] md:text-[10px] text-accent/40 uppercase tracking-widest">{{ $desc }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Right Column: Order Summary --}}
                <div class="lg:w-[400px]">
                    <div class="glass-panel rounded-2xl md:rounded-3xl shadow-3d border border-white/5 p-5 md:p-6 sticky top-24">
                        <h3 class="text-base md:text-lg font-bold text-accent mb-4 md:mb-6">Summary</h3>
                        
                        <div class="space-y-3 md:space-y-4 mb-4 md:mb-6 max-h-[30vh] md:max-h-[40vh] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($cart->items as $item)
                            <div class="flex gap-3 md:gap-4 p-2 rounded-xl md:rounded-2xl hover:bg-white/5 transition-all">
                                <div class="w-12 h-12 md:w-16 md:h-16 bg-white rounded-lg md:rounded-xl border border-white/10 flex items-center justify-center overflow-hidden flex-shrink-0 p-1">
                                    @if($item->product->images && is_array($item->product->images) && count($item->product->images) > 0)
                                        <img src="{{ asset('storage/' . $item->product->images[0]) }}" alt="{{ $item->product->name }}" class="w-full h-full object-contain">
                                    @else
                                        <i class="fa-solid fa-box text-accent/20 text-xs"></i>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0 py-0.5">
                                    <p class="text-xs md:text-sm font-bold text-accent line-clamp-1 uppercase tracking-tight">{{ $item->product->name }}</p>
                                    <div class="flex justify-between items-center mt-0.5 md:mt-1">
                                        <p class="text-[10px] text-accent/50">Qty: {{ $item->quantity }}</p>
                                        <span class="text-xs md:text-sm font-bold text-accent">Rs. {{ number_format($item->line_total) }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @php $shipping = $cart->subtotal >= 10000 ? 0 : 299; @endphp
                        <div class="space-y-2 md:space-y-3 text-[11px] md:text-sm text-accent/60 mb-6 md:mb-8 border-t border-white/5 pt-4 md:pt-6">
                            <div class="flex justify-between"><span>Subtotal</span><span class="text-accent font-medium">Rs. {{ number_format($cart->subtotal) }}</span></div>
                            <div class="flex justify-between"><span>Logistics</span><span class="text-primary font-bold">{{ $shipping==0?'FREE':'Rs. '.number_format($shipping) }}</span></div>
                            @if($cart->discount>0)<div class="flex justify-between text-emerald-500 font-bold"><span>Savings</span><span>- Rs. {{ number_format($cart->discount) }}</span></div>@endif
                        </div>
                        
                        <div class="flex justify-between items-end mb-6 md:mb-8 bg-primary/5 p-3 md:p-4 rounded-xl md:rounded-2xl border border-primary/10">
                            <div>
                                <p class="text-[8px] md:text-[10px] text-primary/60 font-bold uppercase tracking-widest">Total</p>
                                <span class="text-xl md:text-2xl font-black text-accent block leading-none mt-1">Rs. {{ number_format($cart->total + $shipping) }}</span>
                            </div>
                        </div>

                        <button type="submit" 
                                id="submit-btn"
                                onclick="this.innerHTML='<i class=\'fa-solid fa-spinner animate-spin\'></i> PROCESSING...'; this.classList.add('opacity-80', 'cursor-not-allowed');"
                                class="w-full btn-brand py-3 md:py-4 font-bold text-sm md:text-base shadow-3d hover:shadow-3d-hover transition-all flex items-center justify-center gap-3">
                            CONFIRM
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
