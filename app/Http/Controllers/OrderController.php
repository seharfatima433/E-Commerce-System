<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $orders = Order::forUser(auth()->id())
                    ->with('items')
                    ->latest()
                    ->paginate(10);

        return view('account.orders', compact('orders', 'user'));
    }

    public function show(Order $order)
    {
        if ($order->user_id != auth()->id()) abort(403);

        $order->load('items.product');
        return view('account.order-detail', compact('order'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id != auth()->id()) abort(403);

        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        // Restore stock
        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->quantity);
            $item->product->decrement('sales_count', $item->quantity);
        }

        $order->update(['status' => 'cancelled']);
        return back()->with('success', 'Order cancelled successfully.');
    }
}
