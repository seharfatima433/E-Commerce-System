<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $user   = auth()->user();
        $orders = Order::forUser($user->id)->latest()->take(5)->get();
        $stats  = [
            'total_orders'  => Order::forUser($user->id)->count(),
            'total_spent'   => Order::forUser($user->id)->where('payment_status', 'paid')->sum('total'),
            'pending_orders'=> Order::forUser($user->id)->where('status', 'pending')->count(),
            'wishlist_count'=> $user->wishlistItems()->count(),
        ];
        return view('account.dashboard', compact('user', 'orders', 'stats'));
    }

    public function profile()
    {
        return view('account.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:500',
            'city'        => 'nullable|string|max:100',
            'country'     => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        $user->update($data);
        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|confirmed|min:8',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        auth()->user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password updated successfully!');
    }

    public function wishlist()
    {
        $items = auth()->user()->wishlistProducts()->with('category')->paginate(12);
        return view('account.wishlist', compact('items'));
    }
}
