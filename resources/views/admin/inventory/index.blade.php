<x-app-layout>

    <h2 class="fw-bold mb-4">
        <i class="bi bi-clipboard-data"></i> Inventory Management
    </h2>

    {{-- Low stock warning section --}}
    @if ($lowStockProducts->count() > 0)
        <div class="card border-0 shadow-sm mb-4 border-start border-4 border-danger">
            <div class="card-body">
                <h5 class="fw-bold mb-3 text-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> Low Stock Alert
                </h5>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Remaining Stock</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lowStockProducts as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                                 class="rounded" style="width: 35px; height: 35px; object-fit: cover;">
                                            <span class="small">{{ $product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="small">{{ $product->category->name }}</td>
                                    <td>
                                        @if ($product->stock_quantity == 0)
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ $product->stock_quantity }} left</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Restock
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- Stock history section --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inventory.index') }}" class="row g-2">
                <div class="col-12 col-md-7">
                    <input type="text" name="search" class="form-control"
                           placeholder="Search by product name..." value="{{ request('search') }}">
                </div>
                <div class="col-8 col-md-3">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Stock In</option>
                        <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Stock Out</option>
                    </select>
                </div>
                <div class="col-4 col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="fw-bold mb-3">
                <i class="bi bi-clock-history"></i> Stock Movement History
            </h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Note</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stockLogs as $log)
                            <tr>
                                <td class="fw-semibold">{{ $log->product->name }}</td>
                                <td>
                                    @if ($log->type === 'in')
                                        <span class="badge bg-success">
                                            <i class="bi bi-arrow-down-circle"></i> Stock In
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-arrow-up-circle"></i> Stock Out
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $log->quantity }}</td>
                                <td class="small text-muted">{{ $log->note }}</td>
                                <td class="small">{{ $log->created_at->format('d M, Y - h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No stock movement records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $stockLogs->links() }}
            </div>
        </div>
    </div>

</x-app-layout>