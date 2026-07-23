<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Saare reviews dikhana, sath filter ke liye status
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);

        // Agar koi specific status filter select kiya hai
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reviews = $query->latest()->paginate(10)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    // Review ka status update karna (Approve/Reject)
    public function updateStatus(Request $request, Review $review)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $review->update(['status' => $validated['status']]);

        return back()->with('success', 'Review status updated successfully.');
    }

    // Review delete karna
    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}