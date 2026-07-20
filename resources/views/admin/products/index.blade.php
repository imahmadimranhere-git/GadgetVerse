<x-app-layout>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="fw-bold mb-0">📦 Products</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Product
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
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                         alt="{{ $product->name }}"
                                         class="rounded"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td class="fw-semibold">{{ $product->name }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td>
                                    @if ($product->discount_price)
                                        <span class="text-decoration-line-through text-muted small">
                                            Rs {{ number_format($product->price) }}
                                        </span><br>
                                        <span class="text-danger fw-bold">Rs {{ number_format($product->discount_price) }}</span>
                                    @else
                                        Rs {{ number_format($product->price) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($product->stock_quantity <= 5)
                                        <span class="badge bg-danger">{{ $product->stock_quantity }} left</span>
                                    @else
                                        <span class="badge bg-light text-dark">{{ $product->stock_quantity }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($product->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Kya aap is product ko delete karna chahte hain?');">
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
                                <td colspan="7" class="text-center text-muted py-4">
                                    Koi product nahi mila. "Add New Product" pe click karein.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>

</x-app-layout>