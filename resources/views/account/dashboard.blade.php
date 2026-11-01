@extends('layouts.app')
@section('title','Manage My Account | Shopora')
@section('content')
<div class="bg-gray-100 py-6 min-h-[calc(100vh-200px)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row gap-6">
            
            {{-- Sidebar --}}
            <div class="md:w-64 flex-shrink-0">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
                    <p class="text-xs text-gray-500 mb-1">Hello,</p>
                    <p class="font-medium text-gray-800">{{ $user->name }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <ul class="space-y-4 text-sm text-gray-600">
                        <li>
                            <a href="{{ route('account.dashboard') }}" class="text-primary font-medium hover:text-primary transition">Manage My Account</a>
                            <ul class="pl-4 mt-2 space-y-2">
                                <li><a href="{{ route('account.profile') }}" class="hover:text-primary transition">My Profile</a></li>
                                <li><a href="{{ route('account.profile') }}" class="hover:text-primary transition">Address Book</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('orders.index') }}" class="hover:text-primary transition">My Orders</a>
                        </li>
                        <li>
                            <a href="{{ route('account.wishlist') }}" class="hover:text-primary transition">My Wishlist</a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="flex-1 min-w-0">
                <h1 class="text-xl font-medium text-gray-800 mb-4">Manage My Account</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center gap-4 mb-2">
                            <i class="fa-solid fa-box text-3xl text-gray-300"></i>
                            <div>
                                <p class="text-sm text-gray-500">Total Orders</p>
                                <p class="text-2xl font-medium text-gray-800">{{ $stats['total_orders'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center gap-4 mb-2">
                            <i class="fa-solid fa-clock-rotate-left text-3xl text-gray-300"></i>
                            <div>
                                <p class="text-sm text-gray-500">Pending</p>
                                <p class="text-2xl font-medium text-gray-800">{{ $stats['pending_orders'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center gap-4 mb-2">
                            <i class="fa-solid fa-heart text-3xl text-gray-300"></i>
                            <div>
                                <p class="text-sm text-gray-500">Wishlist Items</p>
                                <p class="text-2xl font-medium text-gray-800">{{ $stats['wishlist_count'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-base font-medium text-gray-800">Personal Profile</h3>
                            <a href="{{ route('account.profile') }}" class="text-sm text-blue-600 hover:underline">Edit</a>
                        </div>
                        <p class="text-sm text-gray-800 mb-1">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500 mb-1">{{ $user->email }}</p>
                        <p class="text-sm text-gray-500">{{ $user->phone ?? 'No phone added' }}</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-base font-medium text-gray-800">Address Book</h3>
                            <a href="{{ route('account.profile') }}" class="text-sm text-blue-600 hover:underline">Edit</a>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Default Delivery Address</p>
                        <p class="text-sm text-gray-800">{{ $user->address ?? 'No address added' }}</p>
                        <p class="text-sm text-gray-500">{{ $user->city ? $user->city.', '.$user->country.' - '.$user->postal_code : '' }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-base font-medium text-gray-800">Recent Orders</h3>
                        <a href="{{ route('orders.index') }}" class="text-sm text-blue-600 hover:underline uppercase">View All</a>
                    </div>
                    @if($orders->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-500 border-y border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 font-medium">Order #</th>
                                    <th class="px-4 py-3 font-medium">Placed On</th>
                                    <th class="px-4 py-3 font-medium">Items</th>
                                    <th class="px-4 py-3 font-medium">Total</th>
                                    <th class="px-4 py-3 font-medium text-center">Status</th>
                                    <th class="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($orders->take(5) as $order)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-4 text-blue-600"><a href="{{ route('orders.show',$order) }}" class="hover:underline">{{ $order->order_number }}</a></td>
                                    <td class="px-4 py-4 text-gray-600">{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td class="px-4 py-4 text-gray-600">{{ $order->items->count() }}</td>
                                    <td class="px-4 py-4 font-medium text-gray-800">Rs. {{ number_format($order->total) }}</td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600 uppercase">{{ $order->status }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <a href="{{ route('orders.show',$order) }}" class="text-blue-600 hover:underline uppercase text-xs font-medium">Manage</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-6 text-gray-500 text-sm">You haven't placed any orders yet.</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
