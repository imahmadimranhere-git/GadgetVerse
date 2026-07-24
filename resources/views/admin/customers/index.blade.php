<x-app-layout>

    <h2 class="fw-bold mb-4">
        <i class="bi bi-people"></i> Customer Management
    </h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search box --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.customers.index') }}" class="d-flex gap-2">
                <input type="text" name="search" class="form-control"
                       placeholder="Search by name or email..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Search
                </button>
                @if (request('search'))
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-light">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Total Orders</th>
                            <th>Joined</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <td class="fw-semibold">{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $customer->orders_count }}</span>
                                </td>
                                <td>{{ $customer->created_at->format('d M, Y') }}</td>
                                <td>
                                    @if ($customer->is_blocked)
                                        <span class="badge bg-danger">Blocked</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('admin.customers.toggleBlock', $customer->id) }}" method="POST"
                                          onsubmit="return confirm('{{ $customer->is_blocked ? 'Unblock this customer?' : 'Block this customer?' }}');">
                                        @csrf
                                        @method('PATCH')
                                        @if ($customer->is_blocked)
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-unlock"></i> Unblock
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-lock"></i> Block
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No customers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $customers->links() }}
            </div>
        </div>
    </div>

</x-app-layout>