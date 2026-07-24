<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    // Saare coupons dikhana
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);

        return view('admin.coupons.index', compact('coupons'));
    }

    // Naya coupon banane ka form
    public function create()
    {
        return view('admin.coupons.create');
    }

    // Naya coupon save karna
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:1',
            'expiry_date' => 'required|date|after:today',
            'status' => 'nullable|boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['status'] = $request->has('status');

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    // Edit form
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    // Update karna
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:1',
            'expiry_date' => 'required|date',
            'status' => 'nullable|boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['status'] = $request->has('status');

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    // Delete karna
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }
}