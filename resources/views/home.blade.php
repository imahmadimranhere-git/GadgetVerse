<x-app-layout>

    <!-- Banner Slider (top ka carousel) -->
    @if ($banners->count() > 0)
        <div id="bannerCarousel" class="carousel slide mb-4 rounded overflow-hidden shadow-sm" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach ($banners as $index => $banner)
                    <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="{{ $index }}"
                            class="{{ $index === 0 ? 'active' : '' }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach ($banners as $index => $banner)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <a href="{{ $banner->link ?? '#' }}">
                            <img src="{{ asset('storage/' . $banner->image) }}"
                                 class="d-block w-100" alt="{{ $banner->title }}"
                                 style="max-height: 400px; object-fit: cover;">
                        </a>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    @endif

    <!-- Featured Products (pasandeeda products) -->
    @if ($featuredProducts->count() > 0)
        <div class="mb-5">
            <h4 class="fw-bold mb-3"><i class="bi bi-star-fill text-warning"></i> Featured Products</h4>
            <div class="row g-3">
                @foreach ($featuredProducts as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm product-card">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                     class="card-img-top" style="height: 180px; object-fit: cover;"
                                     alt="{{ $product->name }}">
                            </a>
                            <div class="card-body">
                                <p class="text-muted small mb-1">{{ $product->category->name }}</p>
                                <h6 class="card-title mb-2">
                                    <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
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
                                    <span class="fw-bold">Rs {{ number_format($product->price) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- New Arrivals (naye products) -->
    @if ($newArrivals->count() > 0)
        <div class="mb-5">
            <h4 class="fw-bold mb-3"><i class="bi bi-bag-plus-fill text-primary"></i> New Arrivals</h4>
            <div class="row g-3">
                @foreach ($newArrivals as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm product-card">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                     class="card-img-top" style="height: 180px; object-fit: cover;"
                                     alt="{{ $product->name }}">
                            </a>
                            <div class="card-body">
                                <p class="text-muted small mb-1">{{ $product->category->name }}</p>
                                <h6 class="card-title mb-2">
                                    <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
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
                                    <span class="fw-bold">Rs {{ number_format($product->price) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Empty State (jab banners aur products dono na hon) -->
    @if ($banners->count() === 0 && $featuredProducts->count() === 0 && $newArrivals->count() === 0)
        <div class="text-center py-5">
            <i class="bi bi-shop fs-1 text-muted"></i>
            <p class="text-muted mt-3">No products or banners are available right now.</p>
        </div>
    @endif

</x-app-layout>