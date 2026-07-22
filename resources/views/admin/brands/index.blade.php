<x-app-layout>

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-tags me-2"></i> Brands
        </h2>

        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Brand
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Brand List Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <!-- Brand Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <!-- Agar brands mojood hon -->
                        @forelse ($brands as $brand)
                            <tr>

                                <!-- Brand Logo -->
                                <td>
                                    @if ($brand->logo)
                                        <img src="{{ asset('storage/' . $brand->logo) }}"
                                             alt="{{ $brand->name }}"
                                             class="rounded"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-tag text-muted"></i>
                                        </div>
                                    @endif
                                </td>

                                <!-- Brand Name -->
                                <td class="fw-semibold">
                                    {{ $brand->name }}
                                </td>

                                <!-- Brand Status -->
                                <td>
                                    @if ($brand->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>

                                <!-- Action Buttons -->
                                <td class="text-end">

                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.brands.edit', $brand->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Delete Form -->
                                    <form action="{{ route('admin.brands.destroy', $brand->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this brand?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <!-- Agar koi brand na ho -->
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No brands found. Click "Add New Brand" to create one.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $brands->links() }}
            </div>

        </div>
    </div>

</x-app-layout>