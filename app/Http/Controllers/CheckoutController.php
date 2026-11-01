<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Safepay\SafepayClient;
use Safepay\Checkout;

class CheckoutController extends Controller
{
    // ── Safepay Sandbox Keys ──────────────────────────────────────────────────
    private $key_secret = 'f467fcf92e7cfefc01465bd87a7aa54363068ca469878334f3989253cb13d6c5';
    private $key_public = 'sec_7c16cb91-27cd-4f42-9b38-c32a25d39d93';
    private $api_base   = 'https://sandbox.api.getsafepay.com';

    public function __construct()
    {
        $this->middleware('auth')->except(['safepayWebhook']);
    }

    // ── Safepay SDK Client ────────────────────────────────────────────────────
    private function safepayClient()
    {
        $client = new SafepayClient([
            'api_key'  => $this->key_secret,
            'api_base' => $this->api_base,
        ]);
        return $client;
    }

    // ────────────────────────────────────────────────────────────────────────
    // Checkout Page
    // ────────────────────────────────────────────────────────────────────────
    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())->with('items.product')->first();
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $user = auth()->user();
        return view('checkout.index', compact('cart', 'user'));
    }

    // ────────────────────────────────────────────────────────────────────────
    // Process Order  —  COD / Bank Transfer
    // ────────────────────────────────────────────────────────────────────────
    public function process(Request $request)
    {
        if (strtolower($request->payment_method) === 'card') {
            return $this->initiateSafepay($request);
        }

        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string|max:500',
            'city'           => 'required|string|max:100',
            'postal_code'    => 'required|string|max:20',
            'payment_method' => 'required|in:cash_on_delivery,card',
            'notes'          => 'nullable|string|max:500',
        ]);

        $data['country'] = 'Pakistan';

        $cart = Cart::where('user_id', auth()->id())->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return back()->with('error', "'{$item->product->name}' has insufficient stock.");
            }
        }

        DB::beginTransaction();
        try {
            $shipping = $cart->subtotal >= 10000 ? 0 : 299;

            $order = Order::create([
                'user_id'          => auth()->id(),
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
                'payment_method'   => $data['payment_method'],
                'subtotal'         => $cart->subtotal,
                'shipping'         => $shipping,
                'discount'         => $cart->discount,
                'tax'              => 0,
                'total'            => $cart->subtotal + $shipping - $cart->discount,
                'coupon_code'      => $cart->coupon_code,
                'shipping_name'    => $data['name'],
                'shipping_email'   => $data['email'],
                'shipping_phone'   => $data['phone'],
                'shipping_address' => $data['address'],
                'shipping_city'    => $data['city'],
                'shipping_country' => $data['country'],
                'shipping_postal'  => $data['postal_code'],
                'notes'            => $data['notes'] ?? null,
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item->product_id,
                    'product_name'  => $item->product->name,
                    'product_image' => $item->product->images[0] ?? null,
                    'quantity'      => $item->quantity,
                    'price'         => $item->price,
                    'total'         => $item->price * $item->quantity,
                ]);
                $item->product->decrement('stock', $item->quantity);
                $item->product->increment('sales_count', $item->quantity);
            }

            if ($cart->coupon_code) {
                Coupon::where('code', $cart->coupon_code)->increment('used_count');
            }

            $cart->items()->delete();
            $cart->update(['coupon_code' => null, 'discount' => 0]);
            DB::commit();

            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    // ────────────────────────────────────────────────────────────────────────
    // Safepay — Iframe approach
    // ────────────────────────────────────────────────────────────────────────
    public function initiateSafepay(Request $request)
    {
        $cart = Cart::where('user_id', auth()->id())->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return back()->with('error', "'{$item->product->name}' has insufficient stock.");
            }
        }

        $shipping = $cart->subtotal >= 10000 ? 0 : 299;
        $total    = $cart->subtotal + $shipping - $cart->discount;

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'          => auth()->id(),
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
                'payment_method'   => 'card',
                'subtotal'         => $cart->subtotal,
                'shipping'         => $shipping,
                'discount'         => $cart->discount,
                'tax'              => 0,
                'total'            => $total,
                'coupon_code'      => $cart->coupon_code,
                'shipping_name'    => $request->name        ?? auth()->user()->name,
                'shipping_email'   => $request->email       ?? auth()->user()->email,
                'shipping_phone'   => $request->phone       ?? '',
                'shipping_address' => $request->address     ?? '',
                'shipping_city'    => $request->city        ?? '',
                'shipping_country' => $request->country     ?? 'Pakistan',
                'shipping_postal'  => $request->postal_code ?? '',
                'notes'            => $request->notes       ?? null,
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item->product_id,
                    'product_name'  => $item->product->name,
                    'product_image' => $item->product->images[0] ?? null,
                    'quantity'      => $item->quantity,
                    'price'         => $item->price,
                    'total'         => $item->price * $item->quantity,
                ]);
                $item->product->decrement('stock', $item->quantity);
                $item->product->increment('sales_count', $item->quantity);
            }

            if ($cart->coupon_code) {
                Coupon::where('code', $cart->coupon_code)->increment('used_count');
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Order creation failed: ' . $e->getMessage());
        }

        try {
            $safepay = $this->safepayClient();

            $response = $safepay->order->setup([
                "merchant_api_key" => $this->key_public,
                "intent"           => "CYBERSOURCE",
                "mode"             => "payment",
                "currency"         => "PKR",
                "amount"           => (int)($total * 100),
                "metadata"         => [
                    "order_id" => (string) $order->id,
                ],
            ]);

            $trackerToken = $response['tracker']['token'];
            Log::info('Safepay Tracker Created', ['token' => $trackerToken]);

            $passport = $safepay->passport->create();
            $tbt      = $passport['token'];
            Log::info('Safepay Passport Created', ['tbt' => $tbt]);

            $callbackUrl = route('checkout.safepay.callback')
                         . '?order_id=' . $order->id
                         . '&uid=' . auth()->id();

            $checkoutUrl = Checkout::constructURL([
                "environment"  => "sandbox",
                "tracker"      => $trackerToken,
                "tbt"          => $tbt,
                "source"       => "custom",
                "redirect_url" => $callbackUrl,
                "cancel_url"   => route('cart.index'),
            ]);

            Log::info('Safepay Checkout URL', ['url' => $checkoutUrl]);

            // ✅ Iframe page par show karo
            return view('checkout.safepay_checkout', compact('order', 'checkoutUrl'));

        } catch (\Exception $e) {
            Log::error('Safepay SDK Error', ['message' => $e->getMessage()]);

            $order->load('items');
            $order->update(['status' => 'cancelled', 'payment_status' => 'unpaid']);
            foreach ($order->items as $item) {
                \App\Models\Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                \App\Models\Product::where('id', $item->product_id)->decrement('sales_count', $item->quantity);
            }

            return back()->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }

    // ────────────────────────────────────────────────────────────────────────
    // Safepay Callback
    // ────────────────────────────────────────────────────────────────────────
    public function safepayCallback(Request $request)
    {
        $orderId = $request->query('order_id') ?? session('safepay_order_id');
        $userId  = $request->query('uid')      ?? auth()->id();

        Log::info('Safepay Callback Hit', [
            'order_id'    => $orderId,
            'uid'         => $userId,
            'auth_id'     => auth()->id(),
            'params'      => $request->all(),
        ]);

        if (!$orderId) {
            return redirect()->route('home')->with('error', 'Invalid payment session.');
        }

        // ✅ uid se match karo — auth() se nahi (session expire ho sakti hai)
        $order = Order::where('id', $orderId)
                      ->where('user_id', $userId)
                      ->first();

        if (!$order) {
            Log::error('Safepay Callback: Order not found', [
                'order_id' => $orderId,
                'uid'      => $userId,
            ]);
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        if ($order->payment_status !== 'paid') {
            $order->update([
                'payment_status' => 'paid',
                'status'         => 'processing',
            ]);

            $cart = Cart::where('user_id', $order->user_id)->first();
            if ($cart) {
                $cart->items()->delete();
                $cart->update(['coupon_code' => null, 'discount' => 0]);
            }
        }

        session()->forget(['safepay_tracker', 'safepay_order_id']);

        return redirect()->route('checkout.success', $order->id)
            ->with('success', 'Payment successful! Your order has been placed. 🎉');
    }

    // ────────────────────────────────────────────────────────────────────────
    // Safepay Webhook
    // ────────────────────────────────────────────────────────────────────────
    public function safepayWebhook(Request $request)
    {
        $payload = $request->all();
        Log::info('Safepay Webhook', $payload);

        $state   = $payload['data']['tracker']['state']                ?? null;
        $orderId = $payload['data']['tracker']['metadata']['order_id'] ?? null;

        if ($orderId && $state === 'TRACKER_ENDED') {
            $order = Order::find($orderId);
            if ($order && $order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'status'         => 'processing',
                ]);

                $cart = Cart::where('user_id', $order->user_id)->first();
                if ($cart) {
                    $cart->items()->delete();
                    $cart->update(['coupon_code' => null, 'discount' => 0]);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    // ────────────────────────────────────────────────────────────────────────
    // Order Success Page
    // ────────────────────────────────────────────────────────────────────────
    public function success(Order $order)
    {
        // ✅ FIXED: auth()->id() ki jagah loose comparison
        if ((int)$order->user_id !== (int)auth()->id()) {
            abort(403);
        }
        return view('checkout.success', compact('order'));
    }
}