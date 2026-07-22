<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // Wishlist page dikhana
    public function index()
    {
        $wishlistItems = Wishlist::with('product.category')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('wishlist.index', compact('wishlistItems'));
    }

    // Wishlist mein add/remove karna (Toggle)
    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $existing = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Product wishlist se remove ho gaya.';
            $status = 'removed';
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $validated['product_id'],
            ]);
            $message = 'Product wishlist mein add ho gaya.';
            $status = 'added';
        }

        return back()->with('success', $message);
    }

    // Wishlist se ek item remove karna
    public function destroy(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== auth()->id()) {
            abort(403, 'Unauthorized Action.');
        }

        $wishlist->delete();

        return back()->with('success', 'Product wishlist se remove ho gaya.');
    }
}