@extends('layouts.admin')
@section('title','User Profile')
@section('page-title','User Profile')
@section('content')
<div class="flex gap-4 mb-6">
    <a href="{{ route('admin.users.index') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded text-sm font-semibold transition flex items-center gap-2 shadow-sm">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- User Profile Card --}}
    <div class="space-y-6">
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm text-center">
            <img src="{{ $user->avatar_url }}" class="w-24 h-24 rounded-full mx-auto border-4 border-gray-50 mb-4 object-cover shadow-sm" alt="">
            <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
            <p class="text-sm text-gray-500 mb-4">{{ $user->email }}</p>
            
            <form action="{{ route('admin.users.update-role',$user) }}" method="POST" class="flex gap-2 justify-center max-w-xs mx-auto border-t border-gray-100 pt-4 mt-2">
                @csrf @method('PATCH')
                <select name="role" class="bg-gray-50 border border-gray-200 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-primary font-medium" {{ auth()->id()==$user->id?'disabled':'' }}>
                    <option value="user" {{ $user->role=='user'?'selected':'' }}>Customer</option>
                    <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Administrator</option>
                </select>
                <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-4 py-1.5 rounded text-sm font-bold uppercase transition" {{ auth()->id()==$user->id?'disabled':'' }}>Update</button>
            </form>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
            <h3 class="font-medium text-gray-800 mb-4 pb-2 border-b border-gray-100"><i class="fa-solid fa-address-card text-gray-400 mr-2"></i> Contact Info</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-phone mt-1 text-gray-400 w-4"></i>
                    <span class="text-gray-600">{{ $user->phone ?: 'Not provided' }}</span>
                </div>
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-location-dot mt-1 text-gray-400 w-4"></i>
                    <span class="text-gray-600">
                        {{ $user->address ? $user->address.', '.$user->city.', '.$user->country.' '.$user->postal_code : 'Not provided' }}
                    </span>
                </div>
                <div class="flex items-start gap-3">
                    <i class="fa-regular fa-calendar mt-1 text-gray-400 w-4"></i>
                    <span class="text-gray-600">Joined {{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Order History --}}
    <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center mb-6 pb-2 border-b border-gray-100">
            <h2 class="font-medium text-gray-800">Order History</h2>
            <div class="text-sm">
                <span class="text-gray-500">Total Orders:</span> <span class="font-bold text-gray-800">{{ $orders->total() }}</span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-y border-gray-200">
                    <tr>
                        <th class="px-4 py-3">Order</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.orders.show',$order) }}" class="font-mono text-blue-600 font-medium hover:underline">{{ $order->order_number }}</a>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600 uppercase">{{ $order->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-right text-gray-800 font-medium">Rs. {{ number_format($order->total) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">This user hasn't placed any orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-center">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
