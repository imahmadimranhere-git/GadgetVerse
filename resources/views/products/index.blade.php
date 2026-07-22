<x-app-layout>

    <div class="row g-4">

        <!-- Sidebar filters -->
        <div class="col-12 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <form method="GET" action="{{ route('products.index') }}">

                        <!-- Search field -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Search</label>
                            <input type="text" name="search" class="form-control"
                                   placeholder="Search products..." value="{{ request('search') }}">
                        </div>

                        <hr>

                        <!-- Category filter -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <div class="d-flex flex-column gap-1">
                                @foreach ($categories as $category)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category"
                                               id="cat{{ $category->id }}" value="{{ $category->id }}"
                                               {{ request('category') == $category->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cat{{ $category->id }}">
                                            {{ $category->name }}
                                        </label>
                                    </div>

                                    @foreach ($category->children as $child)
                                        <div class="form-check ms-3">
                                            <input class="form-check-input" type="radio" name="category"
                                                   id="cat{{ $child->id }}" value="{{ $child->id }}"
                                                   {{ request('category') == $child->id ? 'checked' : '' }}>
                                            <label class="form-check-label small text-muted" for="cat{{ $child->id }}">
                                                — {{ $child->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>

                        <hr>

                        <!-- Brand filter -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Brand</label>
                            <select name="brand" class="form-select">
                                <option value="">All Brands</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <hr>

                        <!-- Price range -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Price Range (Rs)</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" name="min_price" class="form-control form-control-sm"
                                           placeholder="Min" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="max_price" class="form-control form-control-sm"
                                           placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel"></i> Apply Filters
                        </button>

                        <a href="{{ route('products.index') }}" class="btn btn-light w-100 mt-2">
                            Clear Filters
                        </a>

                    </form>

                </div>
            </div>
        </div>

        <!-- Products grid -->
        <div class="col-12 col-lg-9">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <p class="text-muted mb-0">{{ $products->total() }} products found</p>

                <form method="GET" action="{{ route('products.index') }}" class="d-flex align-items-center gap-2">

                    <!-- Existing filters ko maintain karne ke liye hidden fields -->
                    @foreach (request()->except('sort', 'page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <label class="small text-muted mb-0">Sort:</label>

                    <select name="sort" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>

                </form>
            </div>

            <div class="row g-3">
                @forelse ($products as $product)

                    <div class="col-6 col-md-4">

                        <div class="card h-100 border-0 shadow-sm product-card">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                     class="card-img-top"
                                     style="height: 180px; object-fit: cover;"
                                     alt="{{ $product->name }}">
                            </a>
                        </div>

                        <div class="card h-100 border-0 shadow-sm product-card position-relative">

                            @auth

                                @php
                                    $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())
                                        ->where('product_id', $product->id)
                                        ->exists();
                                @endphp

                                <!-- Wishlist button -->
                                <form action="{{ route('wishlist.toggle') }}" method="POST"
                                      class="position-absolute top-0 end-0 m-2"
                                      style="z-index: 10;">
                                    @csrf

                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <button type="submit"
                                            class="btn btn-sm {{ $isWishlisted ? 'btn-danger' : 'btn-light' }} rounded-circle">
                                        <i class="bi bi-heart{{ $isWishlisted ? '-fill' : '' }}"></i>
                                    </button>
                                </form>

                            @endauth

                            <a href="{{ route('products.show', $product->slug) }}">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                     class="card-img-top"
                                     style="height: 180px; object-fit: cover;"
                                     alt="{{ $product->name }}">
                            </a>

                            <div class="card-body">

                                <p class="text-muted small mb-1">{{ $product->category->name }}</p>

                                <h6 class="card-title mb-2">
                                    <a href="{{ route('products.show', $product->slug) }}"
                                       class="text-decoration-none text-dark">
                                        {{ Str::limit($product->name, 30) }}
                                    </a>
                                </h6>

                                @if ($product->discount_price)

                                    <span class="text-decoration-line-through text-muted small">
                                        Rs {{ number_format($product->price) }}
                                    </span>

                                    <span class="text-danger fw-bold d-block">
                                        Rs {{ number_format($product->discount_price) }}
                                    </span>

                                @else

                                    <span class="fw-bold">
                                        Rs {{ number_format($product->price) }}
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                @empty

                    <!-- Empty products section -->
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-search fs-1 text-muted"></i>
                        <p class="text-muted mt-3">
                            No products found. Try changing the filters.
                        </p>
                    </div>

                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $products->links() }}
            </div>

        </div>

    </div>

</x-app-layout>