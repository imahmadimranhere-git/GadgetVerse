<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Cart page dikhana
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            $price = $item->product->discount_price ?? $item->product->price;
            return $price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    // Cart mein product add karna
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $quantity = $validated['quantity'] ?? 1;

        // Check karna: kya stock available hai?
        if ($product->stock_quantity < $quantity) {
            return back()->with('error', 'Maazrat, itni quantity stock mein nahi hai.');
        }

        // Check karna: kya ye product pehle se cart mein hai?
        $existingCartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingCartItem) {
            $existingCartItem->increment('quantity', $quantity);
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return back()->with('success', 'Product cart mein add ho gaya.');
    }

    // Cart item ki quantity update karna
    public function update(Request $request, Cart $cart)
    {
        $this->authorizeCartItem($cart);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($cart->product->stock_quantity < $validated['quantity']) {
            return back()->with('error', 'Itni quantity stock mein nahi hai.');
        }

        $cart->update(['quantity' => $validated['quantity']]);

        return back()->with('success', 'Cart update ho gaya.');
    }

    // Cart se product remove karna
    public function destroy(Cart $cart)
    {
        $this->authorizeCartItem($cart);

        $cart->delete();

        return back()->with('success', 'Product cart se remove ho gaya.');
    }

    // Security check: kya ye cart item isi logged-in user ka hai?
    private function authorizeCartItem(Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403, 'Unauthorized Action.');
        }
    }
}