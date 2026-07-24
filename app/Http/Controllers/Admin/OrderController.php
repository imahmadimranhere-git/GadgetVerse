<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Saare orders dikhana, filter ke sath
    public function index(Request $request)
    {
        $query = Order::with(['user', 'address']);

        // Order status se filter
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // Order number ya customer name se search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($subQuery) use ($request) {
                      $subQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    // Single order ki details dikhana
    public function show(Order $order)
    {
        $order->load(['user', 'address', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    // Order status update karna
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['order_status' => $validated['order_status']]);

        // Agar order deliver ho gaya hai aur COD hai, payment bhi "paid" mark kar dete hain
        if ($validated['order_status'] === 'delivered' && $order->payment_method === 'cod') {
            $order->update(['payment_status' => 'paid']);
        }

        return back()->with('success', 'Order status updated successfully.');
    }

    // Payment status update karna
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $order->update(['payment_status' => $validated['payment_status']]);

        return back()->with('success', 'Payment status updated successfully.');
    }
}