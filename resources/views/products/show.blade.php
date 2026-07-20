<x-app-layout>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Products</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="text-decoration-none">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($product->name, 30) }}</li>
        </ol>
    </nav>

    <div class="row g-4 mb-5">

        <!-- Image Gallery -->
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

        <!-- Product Info -->
        <div class="col-12 col-lg-7">
            <p class="text-muted mb-1">
                {{ $product->category->name }} @if ($product->brand) • {{ $product->brand->name }} @endif
            </p>
            <h3 class="fw-bold mb-2">{{ $product->name }}</h3>

            <!-- Rating -->
            <div class="d-flex align-items-center gap-2 mb-3">
                <div>
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= round($product->averageRating()) ? '-fill text-warning' : ' text-muted' }}"></i>
                    @endfor
                </div>
                <span class="text-muted small">({{ $product->reviewsCount() }} reviews)</span>
            </div>

            <!-- Price -->
            <div class="mb-3">
                @if ($product->discount_price)
                    <span class="text-decoration-line-through text-muted fs-5">Rs {{ number_format($product->price) }}</span>
                    <span class="text-danger fw-bold fs-3 d-block">Rs {{ number_format($product->discount_price) }}</span>
                @else
                    <span class="fw-bold fs-3">Rs {{ number_format($product->price) }}</span>
                @endif
            </div>

            <!-- Stock Status -->
            <div class="mb-3">
                @if ($product->stock_quantity > 5)
                    <span class="badge bg-success">In Stock</span>
                @elseif ($product->stock_quantity > 0)
                    <span class="badge bg-warning text-dark">Only {{ $product->stock_quantity }} left!</span>
                @else
                    <span class="badge bg-danger">Out of Stock</span>
                @endif
            </div>

            <!-- Description -->
            <p class="text-muted">{{ $product->description }}</p>

            <!-- Action Buttons (Cart/Wishlist agle steps mein functional honge) -->
            <div class="d-flex flex-wrap gap-2 mt-4">
                <button class="btn btn-primary px-4" {{ $product->stock_quantity == 0 ? 'disabled' : '' }}>
                    <i class="bi bi-cart-plus"></i> Add to Cart
                </button>
                <button class="btn btn-outline-danger px-4">
                    <i class="bi bi-heart"></i> Wishlist
                </button>
            </div>
        </div>

    </div>

    <!-- Specifications -->
    @if ($product->specifications)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">📋 Specifications</h5>
                <p class="text-muted mb-0" style="white-space: pre-line;">{{ $product->specifications }}</p>
            </div>
        </div>
    @endif

    <!-- Reviews Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">⭐ Customer Reviews</h5>

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
                <p class="text-muted">Abhi tak koi review nahi hai. Sabse pehle review dein!</p>
            @endforelse

            <!-- Review Form -->
            @auth
                <hr>
                <h6 class="fw-bold mt-4 mb-3">Apna Review Dein</h6>
                <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Rating <span class="text-danger">*</span></label>
                        <select name="rating" class="form-select @error('rating') is-invalid @enderror" style="max-width: 200px;" required>
                            <option value="">Select Rating</option>
                            <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                            <option value="4">⭐⭐⭐⭐ Good</option>
                            <option value="3">⭐⭐⭐ Average</option>
                            <option value="2">⭐⭐ Poor</option>
                            <option value="1">⭐ Very Poor</option>
                        </select>
                        @error('rating')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Comment (Optional)</label>
                        <textarea name="comment" rows="3" class="form-control @error('comment') is-invalid @enderror"
                                  placeholder="Apna tajurba share karein...">{{ old('comment') }}</textarea>
                        @error('comment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
            @else
                <hr>
                <p class="text-muted">Review dene ke liye <a href="{{ route('login') }}">Login</a> karein.</p>
            @endauth

        </div>
    </div>

    <!-- Related Products -->
    @if ($relatedProducts->count() > 0)
        <div class="mb-5">
            <h5 class="fw-bold mb-3">🔗 Related Products</h5>
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