<x-app-layout>

    {{-- Breadcrumb navigation --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none">My Orders</a></li>
            <li class="breadcrumb-item active">{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-receipt"></i> Order {{ $order->order_number }}
        </h2>
        <span class="text-muted small">Placed on {{ $order->created_at->format('d M, Y - h:i A') }}</span>
    </div>

    {{-- Order Status Tracking Timeline --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-4">
                <i class="bi bi-truck"></i> Order Tracking
            </h5>

            @if ($order->order_status === 'cancelled')
                <div class="alert alert-danger mb-0">
                    <i class="bi bi-x-circle"></i> This order has been cancelled.
                </div>
            @else
                @php
                    // Tracking ke steps ka order fix hai
                    $steps = ['pending', 'processing', 'shipped', 'delivered'];
                    $currentIndex = array_search($order->order_status, $steps);
                @endphp

                <div class="d-flex justify-content-between position-relative tracking-timeline">
                    @foreach ($steps as $index => $step)
                        <div class="text-center flex-fill position-relative">
                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center
                                {{ $index <= $currentIndex ? 'bg-primary text-white' : 'bg-light text-muted' }}"
                                 style="width: 40px; height: 40px;">
                                @if ($index === 0)
                                    <i class="bi bi-clock"></i>
                                @elseif ($index === 1)
                                    <i class="bi bi-gear"></i>
                                @elseif ($index === 2)
                                    <i class="bi bi-truck"></i>
                                @else
                                    <i class="bi bi-check-circle"></i>
                                @endif
                            </div>
                            <p class="small mt-2 mb-0 {{ $index <= $currentIndex ? 'fw-bold' : 'text-muted' }}">
                                {{ ucfirst($step) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4">

        {{-- Order Items List --}}
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-box-seam"></i> Order Items
                    </h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ asset('storage/' . $item->product->thumbnail) }}"
                                                     class="rounded" style="width: 45px; height: 45px; object-fit: cover;">
                                                <span class="small">{{ $item->product->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rs {{ number_format($item->price) }}</td>
                                        <td>Rs {{ number_format($item->price * $item->quantity) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Summary + Address --}}
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">
                        <i class="bi bi-geo-alt"></i> Delivery Address
                    </h6>
                    <p class="small text-muted mb-0">
                        {{ $order->address->full_name }}<br>
                        {{ $order->address->phone }}<br>
                        {{ $order->address->address_line }}, {{ $order->address->city }}
                        {{ $order->address->postal_code }}
                    </p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">
                        <i class="bi bi-credit-card"></i> Payment Details
                    </h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Method</span>
                        <span class="fw-semibold small">{{ strtoupper($order->payment_method) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Payment Status</span>
                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    @if ($order->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Discount</span>
                            <span class="small">- Rs {{ number_format($order->discount_amount) }}</span>
                        </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold fs-5">Rs {{ number_format($order->total_amount) }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>