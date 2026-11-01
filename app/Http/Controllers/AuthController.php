<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return auth()->check() ? redirect()->route('home') : view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $oldSessionId = session()->getId();

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $this->transferGuestCartToUser($oldSessionId, auth()->id());
            
            return redirect()->intended(route('home'))->with('success', 'Welcome back, ' . auth()->user()->name . '!');
        }

        return back()->withErrors(['email' => 'These credentials do not match our records.'])->withInput($request->only('email'));
    }

    public function showRegister()
    {
        return auth()->check() ? redirect()->route('home') : view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone'    => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'phone'    => $data['phone'] ?? null,
            'role'     => 'customer',
        ]);

        $oldSessionId = session()->getId();
        Auth::login($user);
        $this->transferGuestCartToUser($oldSessionId, $user->id);

        return redirect()->route('home')->with('success', 'Welcome to LuxeShop, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }

    private function transferGuestCartToUser($sessionId, $userId)
    {
        $guestCart = \App\Models\Cart::where('session_id', $sessionId)->first();
        
        if ($guestCart) {
            $userCart = \App\Models\Cart::where('user_id', $userId)->first();
            
            if ($userCart) {
                // Merge items
                foreach ($guestCart->items as $item) {
                    $existingItem = $userCart->items()->where('product_id', $item->product_id)->first();
                    
                    if ($existingItem) {
                        $existingItem->update(['quantity' => $existingItem->quantity + $item->quantity]);
                        $item->delete();
                    } else {
                        $item->update(['cart_id' => $userCart->id]);
                    }
                }
                $guestCart->delete();
            } else {
                $guestCart->update(['user_id' => $userId, 'session_id' => null]);
            }
        }
    }
}
