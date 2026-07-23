<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container">

        <!-- Brand Logo -->
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="bi bi-shop"></i> GadgetVerse
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar" aria-controls="mainNavbar"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Collapsible Content -->
        <div class="collapse navbar-collapse" id="mainNavbar">

           <!-- Left Side Links -->
<ul class="navbar-nav me-auto mb-2 mb-lg-0">
    @if (auth()->user() && auth()->user()->hasRole('admin'))
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active fw-bold' : '' }}"
               href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active fw-bold' : '' }}"
               href="{{ route('admin.categories.index') }}">
                <i class="bi bi-folder"></i> Categories
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active fw-bold' : '' }}"
               href="{{ route('admin.brands.index') }}">
                <i class="bi bi-tags"></i> Brands
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active fw-bold' : '' }}"
               href="{{ route('admin.products.index') }}">
                <i class="bi bi-box-seam"></i> Products
            </a>
        </li>

        <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active fw-bold' : '' }}"
       href="{{ route('admin.banners.index') }}">
        <i class="bi bi-image"></i> Banners
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active fw-bold' : '' }}"
       href="{{ route('admin.reviews.index') }}">
        <i class="bi bi-star"></i> Reviews
    </a>
</li>

    @else
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}"
               href="{{ route('dashboard') }}">
                <i class="bi bi-house-door"></i> Home
            </a>
        </li>
       
        <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('products.*') ? 'active fw-bold' : '' }}"
       href="{{ route('products.index') }}">
        <i class="bi bi-grid"></i> Products
    </a>
</li>

        

       <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('wishlist.index') ? 'active fw-bold' : '' }}" href="{{ route('wishlist.index') }}">
        <i class="bi bi-heart"></i> Wishlist
        @auth
            @php
                $wishlistCount = \App\Models\Wishlist::where('user_id', auth()->id())->count();
            @endphp
            @if ($wishlistCount > 0)
                <span class="badge bg-danger rounded-pill">{{ $wishlistCount }}</span>
            @endif
        @endauth
    </a>
</li>
       <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('cart.index') ? 'active fw-bold' : '' }}" href="{{ route('cart.index') }}">
        <i class="bi bi-cart3"></i> Cart
        @auth
            @php
                $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
            @endphp
            @if ($cartCount > 0)
                <span class="badge bg-danger rounded-pill">{{ $cartCount }}</span>
            @endif
        @endauth
    </a>
</li>

{{-- Customer ke orders history ka link --}}
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('orders.*') ? 'active fw-bold' : '' }}" href="{{ route('orders.index') }}">
        <i class="bi bi-bag-check"></i> My Orders
    </a>
</li>
    @endif
</ul>

            <!-- Right Side: User Dropdown -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-gear"></i> Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</nav>