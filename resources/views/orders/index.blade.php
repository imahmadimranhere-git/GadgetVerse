<x-app-layout>

    {{-- Page ka title aur heading --}}
    <h2 class="fw-bold mb-4">
        <i class="bi bi-bag-check"></i> My Orders
    </h2>

    {{-- Agar orders maujood hain to table dikhao --}}
    @if ($orders->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Order Number</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="fw-semibold">{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('d M, Y') }}</td>
                                    <td>Rs {{ number_format($order->total_amount) }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ strtoupper($order->payment_method) }}</span>
                                    </td>
                                    <td>
                                        {{-- Status ke hisab se alag alag color badge --}}
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'processing' => 'info',
                                                'shipped' => 'primary',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger',
                                            ];
                                            $color = $statusColors[$order->order_status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ ucfirst($order->order_status) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    @else
        {{-- Agar koi order nahi hai to empty state dikhao --}}
        <div class="text-center py-5">
            <i class="bi bi-bag-x fs-1 text-muted"></i>
            <p class="text-muted mt-3">You haven't placed any orders yet.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">Start Shopping</a>
        </div>
    @endif

</x-app-layout>