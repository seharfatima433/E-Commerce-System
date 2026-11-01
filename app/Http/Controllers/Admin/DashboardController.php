<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_revenue'   => Order::where('payment_status', 'paid')->sum('total'),
            'total_orders'    => Order::count(),
            'total_products'  => Product::count(),
            'total_users'     => User::where('role', 'customer')->count(),
            'pending_orders'  => Order::where('status', 'pending')->count(),
            'low_stock'       => Product::where('stock', '<=', 5)->where('stock', '>', 0)->count(),
            'out_of_stock'    => Product::where('stock', 0)->count(),
            'monthly_revenue' => Order::where('payment_status', 'paid')
                                    ->whereMonth('created_at', now()->month)
                                    ->sum('total'),
        ];

        $recentOrders   = Order::with('user')->latest()->take(8)->get();
        $topProducts    = Product::orderBy('sales_count', 'desc')->take(5)->get();
        $recentUsers    = User::where('role', 'customer')->latest()->take(5)->get();

        // Monthly revenue chart data (last 6 months)
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartData[] = [
                'month'   => $date->format('M Y'),
                'revenue' => Order::where('payment_status', 'paid')
                                ->whereYear('created_at', $date->year)
                                ->whereMonth('created_at', $date->month)
                                ->sum('total'),
                'orders'  => Order::whereYear('created_at', $date->year)
                                ->whereMonth('created_at', $date->month)
                                ->count(),
            ];
        }

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'recentUsers', 'chartData'));
    }
}
