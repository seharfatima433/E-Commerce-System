<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($payment = $request->get('payment_status')) {
            $query->where('payment_status', $payment);
        }
        if ($search = $request->get('search')) {
            $query->where('order_number', 'like', "%$search%")
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%$search%"));
        }

        $orders = $query->latest()->paginate(20)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status'         => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'payment_status' => 'required|in:unpaid,paid,refunded',
        ]);

        $updates = ['status' => $data['status'], 'payment_status' => $data['payment_status']];

        if ($data['status'] === 'shipped' && !$order->shipped_at) {
            $updates['shipped_at'] = now();
        }
        if ($data['status'] === 'delivered' && !$order->delivered_at) {
            $updates['delivered_at'] = now();
        }
        if ($data['payment_status'] === 'paid' && !$order->paid_at) {
            $updates['paid_at'] = now();
        }

        $order->update($updates);
        return back()->with('success', 'Order status updated.');
    }

    public function destroy(Order $order)
    {
        $order->items()->delete();
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted.');
    }
}
