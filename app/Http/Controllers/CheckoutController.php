<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Checkout page dikhana
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();

        // Agar cart khali hai, checkout pe ane ka koi faida nahi
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Aapka cart khali hai.');
        }

        $addresses = Address::where('user_id', auth()->id())->get();

        $total = $cartItems->sum(function ($item) {
            $price = $item->product->discount_price ?? $item->product->price;
            return $price * $item->quantity;
        });

        return view('checkout.index', compact('cartItems', 'addresses', 'total'));
    }

    // Order place karna
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'nullable|exists:addresses,id',
            // Naya address (agar user naya address add kar raha hai)
            'full_name' => 'required_without:address_id|string|max:255',
            'phone' => 'required_without:address_id|string|max:20',
            'address_line' => 'required_without:address_id|string|max:255',
            'city' => 'required_without:address_id|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'payment_method' => 'required|in:cod,stripe,jazzcash,easypaisa',
        ]);

        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Aapka cart khali hai.');
        }

        // Stock Verify Karna (Checkout se pehle final check)
        foreach ($cartItems as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                return back()->with('error', "Maazrat, '{$item->product->name}' ka stock kam hai.");
            }
        }

        $order = DB::transaction(function () use ($validated, $cartItems, $request) {

            // Address: Ya existing use karo, ya naya banao
            if ($request->filled('address_id')) {
                $addressId = $validated['address_id'];
            } else {
                $address = Address::create([
                    'user_id' => auth()->id(),
                    'full_name' => $validated['full_name'],
                    'phone' => $validated['phone'],
                    'address_line' => $validated['address_line'],
                    'city' => $validated['city'],
                    'postal_code' => $validated['postal_code'] ?? null,
                ]);
                $addressId = $address->id;
            }

            // Total Amount Calculate Karna
            $totalAmount = $cartItems->sum(function ($item) {
                $price = $item->product->discount_price ?? $item->product->price;
                return $price * $item->quantity;
            });

            // Order Banana
            $order = Order::create([
                'user_id' => auth()->id(),
                'address_id' => $addressId,
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_method'] === 'cod' ? 'pending' : 'pending',
                'order_status' => 'pending',
            ]);

            // Har Cart Item Ko Order Item Mein Convert Karna
            foreach ($cartItems as $item) {
                $price = $item->product->discount_price ?? $item->product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                ]);

                // Product Ka Stock Kam Karna
                $item->product->decrement('stock_quantity', $item->quantity);

                // Stock Log Banana
                StockLog::create([
                    'product_id' => $item->product_id,
                    'type' => 'out',
                    'quantity' => $item->quantity,
                    'note' => 'Order #' . $order->order_number,
                ]);
            }

            // Cart Khali Karna
            Cart::where('user_id', auth()->id())->delete();

            return $order;
        });

        return redirect()->route('orders.confirmation', $order->id)
            ->with('success', 'Aapka order successfully place ho gaya!');
    }
}