@extends('layouts.app')
@section('title','My Profile | Shopora')
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
                            <a href="{{ route('account.dashboard') }}" class="hover:text-primary transition">Manage My Account</a>
                            <ul class="pl-4 mt-2 space-y-2">
                                <li><a href="{{ route('account.profile') }}" class="text-primary font-medium hover:text-primary transition">My Profile</a></li>
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
                <h1 class="text-xl font-medium text-gray-800 mb-4">My Profile</h1>
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <form action="{{ route('account.profile.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Full Name</label>
                                <input type="text" name="name" value="{{ old('name',$user->name) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Email Address</label>
                                <input type="email" name="email" value="{{ old('email',$user->email) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone',$user->phone) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Street Address</label>
                                <input type="text" name="address" value="{{ old('address',$user->address) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">City</label>
                                <input type="text" name="city" value="{{ old('city',$user->city) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary">
                            </div>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-500 mb-1">Country</label>
                                    <input type="text" name="country" value="{{ old('country',$user->country) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-500 mb-1">Postal Code</label>
                                    <input type="text" name="postal_code" value="{{ old('postal_code',$user->postal_code) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary">
                                </div>
                            </div>
                        </div>
                        <div class="mt-8">
                            <button type="submit" class="btn-primary px-8 py-2.5 rounded text-sm font-medium shadow-sm uppercase">Save Changes</button>
                        </div>
                    </form>
                </div>

                <h1 class="text-xl font-medium text-gray-800 mb-4 mt-8">Change Password</h1>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <form action="{{ route('account.password.update') }}" method="POST" class="max-w-md">
                        @csrf @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Current Password</label>
                                <input type="password" name="current_password" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">New Password</label>
                                <input type="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary">
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-8 py-2.5 rounded text-sm font-medium shadow-sm uppercase transition">Update Password</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
