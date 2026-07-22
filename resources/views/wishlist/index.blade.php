<x-app-layout>

    <!-- Page Heading -->
    <h2 class="fw-bold mb-4"><i class="bi bi-heart-fill text-danger"></i> My Wishlist</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Wishlist Items Grid (agar wishlist mein products maujood hain) -->
    @if ($wishlistItems->count() > 0)
        <div class="row g-3">
            @foreach ($wishlistItems as $item)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm product-card">
                        <a href="{{ route('products.show', $item->product->slug) }}">
                            <img src="{{ asset('storage/' . $item->product->thumbnail) }}"
                                 class="card-img-top" style="height: 180px; object-fit: cover;"
                                 alt="{{ $item->product->name }}">
                        </a>
                        <div class="card-body">
                            <p class="text-muted small mb-1">{{ $item->product->category->name }}</p>
                            <h6 class="card-title mb-2">
                                <a href="{{ route('products.show', $item->product->slug) }}" class="text-decoration-none text-dark">
                                    {{ Str::limit($item->product->name, 30) }}
                                </a>
                            </h6>
                            <span class="fw-bold d-block mb-2">
                                Rs {{ number_format($item->product->discount_price ?? $item->product->price) }}
                            </span>

                            <!-- Wishlist se product remove karne ka form -->
                            <form action="{{ route('wishlist.destroy', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Remove from wishlist?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State (jab wishlist khali ho) -->
        <div class="text-center py-5">
            <i class="bi bi-heart fs-1 text-muted"></i>
            <p class="text-muted mt-3">Your wishlist is empty.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">Browse Products</a>
        </div>
    @endif

</x-app-layout>