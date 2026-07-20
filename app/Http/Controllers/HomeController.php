<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('status', true)->latest()->get();

        $featuredProducts = Product::with(['category', 'brand'])
            ->where('status', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $newArrivals = Product::with(['category', 'brand'])
            ->where('status', true)
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact('banners', 'featuredProducts', 'newArrivals'));
    }
}