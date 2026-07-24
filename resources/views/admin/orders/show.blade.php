<x-app-layout>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}" class="text-decoration-none">Orders</a></li>
            <li class="breadcrumb-item active">{{ $order->order_number }}</li>
        </ol>
    </nav>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-receipt"></i> Order {{ $order->order_number }}
        </h2>
        <span class="text-muted small">Placed on {{ $order->created_at->format('d M, Y - h:i A') }}</span>
    </div>

    <div class="row g-4">

        {{-- Order items --}}
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
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

            {{-- Customer info --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-person"></i> Customer Details
                    </h5>
                    <p class="mb-1"><strong>Name:</strong> {{ $order->user->name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                    <p class="mb-0"><strong>Phone:</strong> {{ $order->user->phone ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Status update controls --}}
        <div class="col-12 col-lg-4">

            {{-- Order status update form --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-truck"></i> Update Order Status
                    </h6>

                    @if ($order->order_status === 'cancelled')
                        <span class="badge bg-danger">This order is cancelled</span>
                    @else
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="order_status" class="form-select mb-2">
                                <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-lg"></i> Update Status
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Payment status update form --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-credit-card"></i> Update Payment Status
                    </h6>
                    <form action="{{ route('admin.orders.updatePaymentStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="payment_status" class="form-select mb-2">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="bi bi-check-lg"></i> Update Payment
                        </button>
                    </form>
                </div>
            </div>

            {{-- Delivery address --}}
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

            {{-- Order summary --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Payment Method</span>
                        <span class="fw-semibold small">{{ strtoupper($order->payment_method) }}</span>
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