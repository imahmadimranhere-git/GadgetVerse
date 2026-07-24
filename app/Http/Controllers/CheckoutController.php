<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Checkout page dikhana
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $addresses = Address::where('user_id', auth()->id())->get();

        $subtotal = $cartItems->sum(function ($item) {
            $price = $item->product->discount_price ?? $item->product->price;
            return $price * $item->quantity;
        });

        // Session mein coupon hai to discount calculate karna
        $discount = 0;
        $appliedCoupon = null;

        if (session()->has('coupon')) {
            $appliedCoupon = session('coupon');
            $discount = $this->calculateDiscount($appliedCoupon, $subtotal);
        }

        $total = $subtotal - $discount;

        return view('checkout.index', compact('cartItems', 'addresses', 'subtotal', 'discount', 'total', 'appliedCoupon'));
    }

    // Coupon apply karna
    public function applyCoupon(Request $request)
    {
        $validated = $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', strtoupper($validated['coupon_code']))->first();

        // Coupon exist karta hai?
        if (!$coupon) {
            return back()->with('error', 'Invalid coupon code.');
        }

        // Coupon active hai?
        if (!$coupon->status) {
            return back()->with('error', 'This coupon is no longer active.');
        }

        // Coupon expire to nahi hua?
        if (\Carbon\Carbon::parse($coupon->expiry_date)->isPast()) {
            return back()->with('error', 'This coupon has expired.');
        }

        // Session mein coupon store karna
        session(['coupon' => [
            'code' => $coupon->code,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
        ]]);

        return back()->with('success', 'Coupon applied successfully!');
    }

    // Coupon remove karna
    public function removeCoupon()
    {
        session()->forget('coupon');

        return back()->with('success', 'Coupon removed.');
    }

    // Order place karna
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'nullable|exists:addresses,id',
            'full_name' => 'required_without:address_id|string|max:255',
            'phone' => 'required_without:address_id|string|max:20',
            'address_line' => 'required_without:address_id|string|max:255',
            'city' => 'required_without:address_id|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'payment_method' => 'required|in:cod,stripe,jazzcash,easypaisa',
        ]);

        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        foreach ($cartItems as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                return back()->with('error', "Sorry, '{$item->product->name}' does not have enough stock.");
            }
        }

        // Coupon discount calculate karna (agar session mein hai)
        $subtotal = $cartItems->sum(function ($item) {
            $price = $item->product->discount_price ?? $item->product->price;
            return $price * $item->quantity;
        });

        $discount = 0;
        $couponCode = null;

        if (session()->has('coupon')) {
            $appliedCoupon = session('coupon');
            $discount = $this->calculateDiscount($appliedCoupon, $subtotal);
            $couponCode = $appliedCoupon['code'];
        }

        $order = DB::transaction(function () use ($validated, $cartItems, $request, $subtotal, $discount, $couponCode) {

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

            $totalAmount = $subtotal - $discount;

            $order = Order::create([
                'user_id' => auth()->id(),
                'address_id' => $addressId,
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'coupon_code' => $couponCode,
                'discount_amount' => $discount,
            ]);

            foreach ($cartItems as $item) {
                $price = $item->product->discount_price ?? $item->product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                ]);

                $item->product->decrement('stock_quantity', $item->quantity);

                StockLog::create([
                    'product_id' => $item->product_id,
                    'type' => 'out',
                    'quantity' => $item->quantity,
                    'note' => 'Order #' . $order->order_number,
                ]);
            }

            Cart::where('user_id', auth()->id())->delete();

            return $order;
        });

        // Order place hone ke baad coupon session se hata dena
        session()->forget('coupon');

        return redirect()->route('orders.confirmation', $order->id)
            ->with('success', 'Your order has been placed successfully!');
    }

    // Discount calculate karne ka helper method
    private function calculateDiscount(array $coupon, float $subtotal): float
    {
        if ($coupon['discount_type'] === 'percentage') {
            return $subtotal * ($coupon['discount_value'] / 100);
        }

        return min($coupon['discount_value'], $subtotal);
    }
}