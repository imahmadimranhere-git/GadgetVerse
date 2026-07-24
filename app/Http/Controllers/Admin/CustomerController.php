<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Saare customers dikhana
    public function index(Request $request)
    {
        $query = User::role('customer')->withCount('orders');

        // Search by name or email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    // Customer ko Block/Unblock karna (Toggle)
    public function toggleBlock(User $customer)
    {
        $customer->update(['is_blocked' => !$customer->is_blocked]);

        $message = $customer->is_blocked
            ? 'Customer has been blocked successfully.'
            : 'Customer has been unblocked successfully.';

        return back()->with('success', $message);
    }
}