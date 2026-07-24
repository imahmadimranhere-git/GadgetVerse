<x-app-layout>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-ticket-perforated"></i> Coupons
        </h2>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Coupon
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Discount</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($coupons as $coupon)
                            <tr>
                                <td>
                                    <span class="badge bg-dark">{{ $coupon->code }}</span>
                                </td>
                                <td class="fw-semibold">
                                    @if ($coupon->discount_type === 'fixed')
                                        Rs {{ number_format($coupon->discount_value) }} Off
                                    @else
                                        {{ $coupon->discount_value }}% Off
                                    @endif
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($coupon->expiry_date)->format('d M, Y') }}
                                    @if (\Carbon\Carbon::parse($coupon->expiry_date)->isPast())
                                        <span class="badge bg-danger">Expired</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($coupon->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this coupon?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No coupons found. Click "Add New Coupon" to create one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $coupons->links() }}
            </div>
        </div>
    </div>

</x-app-layout>