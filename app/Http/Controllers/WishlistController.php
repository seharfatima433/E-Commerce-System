<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Product $product)
    {
        $userId = auth()->id();
        $entry  = Wishlist::where('user_id', $userId)->where('product_id', $product->id)->first();

        if ($entry) {
            $entry->delete();
            $message = 'Removed from wishlist.';
            $inWishlist = false;
        } else {
            Wishlist::create(['user_id' => $userId, 'product_id' => $product->id]);
            $message = 'Added to wishlist!';
            $inWishlist = true;
        }

        if (request()->expectsJson()) {
            return response()->json(['in_wishlist' => $inWishlist, 'message' => $message]);
        }
        return back()->with('success', $message);
    }
}
