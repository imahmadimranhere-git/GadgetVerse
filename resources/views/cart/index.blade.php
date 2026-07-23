<x-app-layout>

    <h2 class="fw-bold mb-4">
        <i class="bi bi-cart3 me-2"></i>My Shopping Cart
    </h2>

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

    @if ($cartItems->count() > 0)

        <div class="row g-4">

            <!-- Cart items ki table -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItems as $item)
                                        @php
                                            $price = $item->product->discount_price ?? $item->product->price;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <img src="{{ asset('storage/' . $item->product->thumbnail) }}"
                                                        class="rounded"
                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                    <span class="small">{{ Str::limit($item->product->name, 25) }}</span>
                                                </div>
                                            </td>

                                            <td>Rs {{ number_format($price) }}</td>

                                            <td style="width: 140px;">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                                    class="d-flex gap-1">
                                                    @csrf
                                                    @method('PATCH')

                                                    <input type="number" name="quantity"
                                                        value="{{ $item->quantity }}" min="1"
                                                        max="{{ $item->product->stock_quantity }}"
                                                        class="form-control form-control-sm"
                                                        style="width: 70px;">

                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-arrow-repeat"></i>
                                                    </button>
                                                </form>
                                            </td>

                                            <td class="fw-semibold">
                                                Rs {{ number_format($price * $item->quantity) }}
                                            </td>

                                            <td>
                                                <form action="{{ route('cart.destroy', $item->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Remove this item?');">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order summary card -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-receipt-cutoff me-2"></i>Order Summary
                        </h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-semibold">Rs {{ number_format($total) }}</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold fs-5">Rs {{ number_format($total) }}</span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100">
    <i class="bi bi-bag-check"></i> Proceed to Checkout
</a>

                        <a href="{{ route('products.index') }}" class="btn btn-light w-100 mt-2">
                            <i class="bi bi-arrow-left-circle me-1"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>

        </div>

    @else

        <!-- Empty cart ka section -->
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>

            <p class="text-muted mt-3">
                Your shopping cart is empty.
            </p>

            <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">
                <i class="bi bi-bag-plus me-1"></i>Start Shopping
            </a>
        </div>

    @endif

</x-app-layout>