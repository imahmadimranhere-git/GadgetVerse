<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // Saare products dikhana
    public function index()
    {
        $products = Product::with(['category', 'brand'])->latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    // Naya product banane ka form
    public function create()
    {
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    // Naya product save karna
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'specifications' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'thumbnail' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated, $request) {

            $validated['slug'] = Str::slug($validated['name']) . '-' . uniqid();
            $validated['status'] = $request->has('status');
            $validated['is_featured'] = $request->has('is_featured');
            $validated['thumbnail'] = $request->file('thumbnail')->store('products', 'public');

            $product = Product::create($validated);

            // Gallery Images Save Karna (Multiple)
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $image) {
                    $path = $image->store('products/gallery', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                    ]);
                }
            }

            // Initial Stock Log Entry
            StockLog::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $product->stock_quantity,
                'note' => 'Initial stock on product creation',
            ]);

        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    // Edit form
    public function edit(Product $product)
    {
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        $product->load('images');

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    // Update karna
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'specifications' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated, $request, $product) {

            $oldQuantity = $product->stock_quantity;

            $validated['status'] = $request->has('status');
            $validated['is_featured'] = $request->has('is_featured');

            if ($request->hasFile('thumbnail')) {
                Storage::disk('public')->delete($product->thumbnail);
                $validated['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
            }

            $product->update($validated);

            // Nayi gallery images add karna (purani delete nahi kar rahe, sirf naye add)
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $image) {
                    $path = $image->store('products/gallery', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                    ]);
                }
            }

            // Agar stock quantity change hui to log banao
            if ($oldQuantity != $product->stock_quantity) {
                $difference = $product->stock_quantity - $oldQuantity;
                StockLog::create([
                    'product_id' => $product->id,
                    'type' => $difference > 0 ? 'in' : 'out',
                    'quantity' => abs($difference),
                    'note' => 'Stock updated via product edit',
                ]);
            }

        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    // Delete karna
    public function destroy(Product $product)
    {
        Storage::disk('public')->delete($product->thumbnail);

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    // Ek gallery image delete karna (AJAX ke liye, edit page se)
    public function deleteImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json(['success' => true]);
    }
}