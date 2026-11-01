@extends('layouts.admin')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['fa-money-bill-wave','Total Revenue','Rs. '.number_format($stats['total_revenue']),'text-green-500','bg-green-50'],
        ['fa-bag-shopping','Total Orders',$stats['total_orders'],'text-primary','bg-orange-50'],
        ['fa-box','Products',$stats['total_products'],'text-blue-500','bg-blue-50'],
        ['fa-users','Customers',$stats['total_users'],'text-purple-500','bg-purple-50'],
    ] as [$icon,$label,$val,$color,$bg])
    <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-500 font-medium">{{ $label }}</span>
            <div class="w-8 h-8 rounded-lg {{ $bg }} flex items-center justify-center">
                <i class="fa-solid {{ $icon }} {{ $color }} text-sm"></i>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $val }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white border border-gray-200 rounded-lg p-4 text-center shadow-sm">
        <p class="text-2xl font-bold text-yellow-500">{{ $stats['pending_orders'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Pending Orders</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-lg p-4 text-center shadow-sm">
        <p class="text-2xl font-bold text-blue-500">Rs. {{ number_format($stats['monthly_revenue']) }}</p>
        <p class="text-xs text-gray-500 mt-1">This Month Revenue</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-lg p-4 text-center shadow-sm">
        <p class="text-2xl font-bold text-orange-500">{{ $stats['low_stock'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Low Stock Items</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-lg p-4 text-center shadow-sm">
        <p class="text-2xl font-bold text-red-500">{{ $stats['out_of_stock'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Out of Stock</p>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm mb-6">
    <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
        <div>
            <h2 class="font-bold text-gray-800 text-lg"><i class="fa-solid fa-bolt text-yellow-500 mr-2"></i> Quick Add Product</h2>
            <p class="text-xs text-gray-500 mt-1">For non-technical admins: Use this simple form to quickly list a product.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="text-xs bg-gray-100 border border-gray-200 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded font-medium transition">Switch to Advanced Form</a>
    </div>
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap gap-4 items-end">
        @csrf
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">Product Name *</label>
            <input type="text" name="name" required class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-sm placeholder-gray-400" placeholder="e.g. New Wireless Mouse">
        </div>
        <div class="w-32">
            <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">Price (Rs.) *</label>
            <input type="number" name="price" required class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-sm">
        </div>
        <div class="w-24">
            <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">Stock *</label>
            <input type="number" name="stock" value="10" required class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-sm">
        </div>
        <div class="w-48">
            <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">Category *</label>
            <select name="category_id" required class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-sm">
                @foreach(\App\Models\Category::active()->get() as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-48">
            <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">Image</label>
            <input type="file" name="images[]" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
        </div>
        <input type="hidden" name="is_active" value="1">
        <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-6 py-2 rounded text-sm font-bold uppercase transition tracking-wider shadow-sm h-[38px] w-full sm:w-auto">
            Add Product
        </button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Recent Orders --}}
    <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-medium text-gray-800">Recent Orders</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-blue-600 hover:underline">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 text-xs border-b border-gray-100 bg-gray-50">
                        <th class="text-left p-3 font-medium rounded-tl-lg">Order</th>
                        <th class="text-left p-3 font-medium">Customer</th>
                        <th class="text-left p-3 font-medium">Status</th>
                        <th class="text-right p-3 font-medium rounded-tr-lg">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentOrders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-3">
                            <a href="{{ route('admin.orders.show',$order) }}" class="text-blue-600 hover:underline font-mono text-xs">{{ $order->order_number }}</a>
                        </td>
                        <td class="p-3 text-gray-700">{{ $order->user->name }}</td>
                        <td class="p-3">
                            <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600 uppercase">{{ $order->status }}</span>
                        </td>
                        <td class="p-3 text-right text-gray-800 font-medium">Rs. {{ number_format($order->total) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top Products + Recent Users --}}
    <div class="space-y-6">
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
            <h2 class="font-medium text-gray-800 mb-4">Top Products</h2>
            <div class="space-y-3">
                @foreach($topProducts as $p)
                <div class="flex items-center justify-between pb-2 border-b border-gray-50 last:border-0 last:pb-0">
                    <p class="text-sm text-gray-600 truncate flex-1 mr-2">{{ $p->name }}</p>
                    <span class="text-xs text-primary font-semibold flex-shrink-0">{{ $p->sales_count }} sold</span>
                </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
            <h2 class="font-medium text-gray-800 mb-4">New Customers</h2>
            <div class="space-y-3">
                @foreach($recentUsers as $u)
                <div class="flex items-center gap-3 pb-2 border-b border-gray-50 last:border-0 last:pb-0">
                    <img src="{{ $u->avatar_url }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0 border border-gray-200" alt="">
                    <div class="min-w-0">
                        <p class="text-sm text-gray-800 truncate font-medium">{{ $u->name }}</p>
                        <p class="text-xs text-gray-500">{{ $u->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
