<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockLog;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // Stock logs (history) dikhana
    public function index(Request $request)
    {
        $query = StockLog::with('product');

        // Type se filter (in/out)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Product name se search
        if ($request->filled('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $stockLogs = $query->latest()->paginate(15)->withQueryString();

        // Low stock products (threshold: 5 ya usse kam)
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)
            ->where('status', true)
            ->orderBy('stock_quantity', 'asc')
            ->get();

        return view('admin.inventory.index', compact('stockLogs', 'lowStockProducts'));
    }
}