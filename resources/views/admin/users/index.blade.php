@extends('layouts.admin')
@section('title','Users')
@section('page-title','Users')
@section('content')
<div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
    <div class="flex flex-col sm:flex-row justify-between gap-4 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..." class="bg-white border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary w-full sm:w-64">
            <button type="submit" class="bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-600 px-3 py-2 rounded transition"><i class="fa-solid fa-search text-sm"></i></button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-y border-gray-200">
                <tr>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Joined</th>
                    <th class="px-4 py-3 text-center">Orders</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->avatar_url }}" class="w-10 h-10 rounded-full border border-gray-200 object-cover" alt="">
                            <div>
                                <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-1 rounded font-medium {{ $user->role==='admin'?'bg-primary text-white':'bg-gray-100 text-gray-600' }} uppercase tracking-wider">{{ $user->role }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center justify-center bg-gray-100 text-gray-700 text-xs font-bold px-2 py-1 rounded-full">{{ $user->orders_count }}</span>
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.users.show',$user) }}" class="text-blue-500 hover:text-blue-700 text-xs font-semibold uppercase tracking-wider px-2">View</a>
                        <form action="{{ route('admin.users.destroy',$user) }}" method="POST" class="inline" onsubmit="return confirm('WARNING: This will permanently delete the user and all their data. Are you sure?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold uppercase tracking-wider px-2" {{ auth()->id()==$user->id?'disabled':'' }}>Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 flex justify-center">{{ $users->links() }}</div>
</div>
@endsection
