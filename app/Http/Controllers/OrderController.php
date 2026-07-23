<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Order confirmation page
    public function confirmation(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized Action.');
        }

        $order->load('items.product', 'address');

        return view('orders.confirmation', compact('order'));
    }

    // User ki saari orders (Order History)
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    // Single order details (Order Tracking)
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized Action.');
        }

        $order->load('items.product', 'address');

        return view('orders.show', compact('order'));
    }
}