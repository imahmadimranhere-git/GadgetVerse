<x-app-layout>

    {{-- Success message ke sath big check icon show karain --}}
    <div class="text-center py-4">
        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
        <h3 class="fw-bold mt-3">Order Successfully Placed!</h3>
        <p class="text-muted">Order Number: <strong>{{ $order->order_number }}</strong></p>
    </div>

    <div class="row g-4">

        <!-- Left: Order Items -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Order Items</h5>

                    {{-- Order mai jitne items hain un ki table bana kar dikhain --}}
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
                                        <td>{{ $item->product->name }}</td>
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

        <!-- Right: Address + Payment Summary -->
        <div class="col-12 col-lg-4">

            {{-- Delivery address ki details show karain --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">Delivery Address</h6>
                    <p class="small text-muted mb-0">
                        {{ $order->address->full_name }}<br>
                        {{ $order->address->phone }}<br>
                        {{ $order->address->address_line }}, {{ $order->address->city }}
                    </p>
                </div>
            </div>

            {{-- Payment method, order status aur total amount ki summary --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Payment Method</span>
                        <span class="fw-semibold">{{ strtoupper($order->payment_method) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Order Status</span>
                        <span class="badge bg-warning text-dark">{{ ucfirst($order->order_status) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold fs-5">Rs {{ number_format($order->total_amount) }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Neeche action buttons: orders list ya shopping continue karne ke liye --}}
    <div class="text-center mt-4">
        <a href="{{ route('orders.index') }}" class="btn btn-primary">View My Orders</a>
        <a href="{{ route('products.index') }}" class="btn btn-light">Continue Shopping</a>
    </div>

</x-app-layout>