<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $totalSales = Order::where('payment_status', 'paid')->sum('total_amount');
    $totalOrders = Order::count();
    $totalUsers = User::role('customer')->count();
    $totalProducts = Product::count();
    $lowStockCount = Product::where('stock_quantity', '<=', 5)->where('status', true)->count();

    $recentOrders = Order::with('user')->latest()->take(5)->get();

    return view('admin.dashboard', compact(
        'totalSales',
        'totalOrders',
        'totalUsers',
        'totalProducts',
        'lowStockCount',
        'recentOrders'
    ));
}
}