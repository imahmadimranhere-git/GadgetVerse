<x-app-layout>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

    <!-- Breadcrumb (path dikhane ke liye) -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Products</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="text-decoration-none">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($product->name, 30) }}</li>
        </ol>
    </nav>

    <div class="row g-4 mb-5">

        <!-- Image Gallery (product ki tasveerain) -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm mb-2">
                <img id="mainImage" src="{{ asset('storage/' . $product->thumbnail) }}"
                     class="card-img-top rounded" style="height: 350px; object-fit: cover;"
                     alt="{{ $product->name }}">
            </div>

            @if ($product->images->count() > 0)
                <div class="d-flex gap-2 flex-wrap">
                    <img src="{{ asset('storage/' . $product->thumbnail) }}"
                         class="rounded border thumbnail-img cursor-pointer"
                         style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                         onclick="document.getElementById('mainImage').src = this.src">
                    @foreach ($product->images as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                             class="rounded border thumbnail-img"
                             style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                             onclick="document.getElementById('mainImage').src = this.src">
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Info (product ki maloomat) -->
        <div class="col-12 col-lg-7">
            <p class="text-muted mb-1">
                {{ $product->category->name }} @if ($product->brand) • {{ $product->brand->name }} @endif
            </p>
            <h3 class="fw-bold mb-2">{{ $product->name }}</h3>

            <!-- Rating (sitaray) -->
            <div class="d-flex align-items-center gap-2 mb-3">
                <div>
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= round($product->averageRating()) ? '-fill text-warning' : ' text-muted' }}"></i>
                    @endfor
                </div>
                <span class="text-muted small">({{ $product->reviewsCount() }} reviews)</span>
            </div>

            <!-- Price (qeemat) -->
            <div class="mb-3">
                @if ($product->discount_price)
                    <span class="text-decoration-line-through text-muted fs-5">Rs {{ number_format($product->price) }}</span>
                    <span class="text-danger fw-bold fs-3 d-block">Rs {{ number_format($product->discount_price) }}</span>
                @else
                    <span class="fw-bold fs-3">Rs {{ number_format($product->price) }}</span>
                @endif
            </div>

            <!-- Stock Status (stock ki maujoodgi) -->
            <div class="mb-3">
                @if ($product->stock_quantity > 5)
                    <span class="badge bg-success">In Stock</span>
                @elseif ($product->stock_quantity > 0)
                    <span class="badge bg-warning text-dark">Only {{ $product->stock_quantity }} left!</span>
                @else
                    <span class="badge bg-danger">Out of Stock</span>
                @endif
            </div>

            <!-- Description (tafseel) -->
            <p class="text-muted">{{ $product->description }}</p>

            <!-- Action Buttons (cart aur wishlist ke buttons) -->
<div class="d-flex flex-wrap gap-2 mt-4">
    @auth
        <form action="{{ route('cart.store') }}" method="POST" class="d-flex gap-2">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}"
                   class="form-control" style="width: 80px;"
                   {{ $product->stock_quantity == 0 ? 'disabled' : '' }}>
            <button type="submit" class="btn btn-primary px-4" {{ $product->stock_quantity == 0 ? 'disabled' : '' }}>
                <i class="bi bi-cart-plus"></i> Add to Cart
            </button>
        </form>
    @else
        <a href="{{ route('login') }}" class="btn btn-primary px-4">
            <i class="bi bi-cart-plus"></i> Login to Add Cart
        </a>
    @endauth
    @auth
    @php
        // Pehle check karte hain ke yeh product wishlist mein pehle se maujood hai ya nahi
        $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->exists();
    @endphp
    <form action="{{ route('wishlist.toggle') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <button type="submit" class="btn {{ $isWishlisted ? 'btn-danger' : 'btn-outline-danger' }} px-4">
            <i class="bi bi-heart{{ $isWishlisted ? '-fill' : '' }}"></i>
            {{ $isWishlisted ? 'In Wishlist' : 'Wishlist' }}
        </button>
    </form>
@else
    <a href="{{ route('login') }}" class="btn btn-outline-danger px-4">
        <i class="bi bi-heart"></i> Wishlist
    </a>
@endauth
</div>


        </div>

    </div>

    <!-- Specifications (specifications ki tafseelat) -->
    @if ($product->specifications)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3"><i class="bi bi-clipboard-data"></i> Specifications</h5>
                <p class="text-muted mb-0" style="white-space: pre-line;">{{ $product->specifications }}</p>
            </div>
        </div>
    @endif

    <!-- Reviews Section (reviews ka hissa) -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3"><i class="bi bi-star-fill text-warning"></i> Customer Reviews</h5>

            @forelse ($product->approvedReviews as $review)
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>{{ $review->user->name }}</strong>
                        <span class="text-muted small">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="mb-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-muted' }} small"></i>
                        @endfor
                    </div>
                    @if ($review->comment)
                        <p class="mb-0 text-muted">{{ $review->comment }}</p>
                    @endif
                </div>
            @empty
                <p class="text-muted">No reviews yet. Be the first to write one!</p>
            @endforelse

            <!-- Review Form (review jama karne ka form) -->
            @auth
                <hr>
                <h6 class="fw-bold mt-4 mb-3">Write a Review</h6>
                <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Rating <span class="text-danger">*</span></label>
                        <select name="rating" class="form-select @error('rating') is-invalid @enderror" style="max-width: 200px;" required>
                            <option value="">Select Rating</option>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Good</option>
                            <option value="3">3 - Average</option>
                            <option value="2">2 - Poor</option>
                            <option value="1">1 - Very Poor</option>
                        </select>
                        @error('rating')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Comment (Optional)</label>
                        <textarea name="comment" rows="3" class="form-control @error('comment') is-invalid @enderror"
                                  placeholder="Share your experience...">{{ old('comment') }}</textarea>
                        @error('comment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
            @else
                <hr>
                <p class="text-muted">Please <a href="{{ route('login') }}">Login</a> to write a review.</p>
            @endauth

        </div>
    </div>

    <!-- Related Products (milte julte products) -->
    @if ($relatedProducts->count() > 0)
        <div class="mb-5">
            <h5 class="fw-bold mb-3"><i class="bi bi-link-45deg"></i> Related Products</h5>
            <div class="row g-3">
                @foreach ($relatedProducts as $related)
                    <div class="col-6 col-md-3">
                        <div class="card h-100 border-0 shadow-sm product-card">
                            <a href="{{ route('products.show', $related->slug) }}">
                                <img src="{{ asset('storage/' . $related->thumbnail) }}"
                                     class="card-img-top" style="height: 150px; object-fit: cover;"
                                     alt="{{ $related->name }}">
                            </a>
                            <div class="card-body">
                                <h6 class="card-title mb-2">
                                    <a href="{{ route('products.show', $related->slug) }}" class="text-decoration-none text-dark">
                                        {{ Str::limit($related->name, 25) }}
                                    </a>
                                </h6>
                                <span class="fw-bold">Rs {{ number_format($related->discount_price ?? $related->price) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</x-app-layout>