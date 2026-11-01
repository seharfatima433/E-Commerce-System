<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getOrCreateCart()
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(['user_id' => auth()->id()]);
        }
        $sessionId = session()->getId();
        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load('items.product.category');
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->is_in_stock) {
            return back()->with('error', 'Sorry, this product is out of stock.');
        }

        if ($request->quantity > $product->stock) {
            return back()->with('error', "Only {$product->stock} items available in stock.");
        }

        $cart = $this->getOrCreateCart();

        $existing = $cart->items()->where('product_id', $product->id)->first();

        if ($existing) {
            $newQty = $existing->quantity + $request->quantity;
            if ($newQty > $product->stock) {
                return back()->with('error', "Cannot add more. Only {$product->stock} items available.");
            }
            $existing->update(['quantity' => $newQty]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
                'price'      => $product->current_price,
                'options'    => $request->options ?? null,
            ]);
        }

        if ($request->expectsJson()) {
            $cart->load('items');
            return response()->json(['success' => true, 'item_count' => $cart->item_count, 'message' => 'Product added to cart!']);
        }

        return back()->with('success', "'{$product->name}' added to your cart!");
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:99']);

        if ($request->quantity > $cartItem->product->stock) {
            return back()->with('error', "Only {$cartItem->product->stock} items available.");
        }

        $cartItem->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Cart updated.');
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
        return back()->with('success', 'Item removed from cart.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);

        $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();
        $cart   = $this->getOrCreateCart();
        $cart->load('items');

        if (!$coupon) {
            return back()->with('error', 'Invalid coupon code.');
        }

        if (!$coupon->isValid($cart->subtotal)) {
            return back()->with('error', 'This coupon is not applicable to your order.');
        }

        $discount = $coupon->calculateDiscount($cart->subtotal);
        $cart->update(['coupon_code' => $coupon->code, 'discount' => $discount]);

        return back()->with('success', "Coupon applied! You saved PKR " . number_format($discount, 2));
    }

    public function removeCoupon()
    {
        $cart = $this->getOrCreateCart();
        $cart->update(['coupon_code' => null, 'discount' => 0]);
        return back()->with('success', 'Coupon removed.');
    }

    public function count()
    {
        $cart = $this->getOrCreateCart();
        return response()->json(['count' => $cart->item_count]);
    }
}
