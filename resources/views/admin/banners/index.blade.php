<x-app-layout>

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-images me-2"></i> Banners
        </h2>

        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Banner
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Banner List Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <!-- Banner Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <!-- Agar banners mojood hon -->
                        @forelse ($banners as $banner)
                            <tr>

                                <!-- Banner Image -->
                                <td>
                                    <img src="{{ asset('storage/' . $banner->image) }}"
                                         class="rounded"
                                         style="width: 80px; height: 45px; object-fit: cover;">
                                </td>

                                <!-- Banner Title -->
                                <td class="fw-semibold">
                                    {{ $banner->title }}
                                </td>

                                <!-- Banner Status -->
                                <td>
                                    @if ($banner->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>

                                <!-- Action Buttons -->
                                <td class="text-end">

                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.banners.edit', $banner->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Delete Form -->
                                    <form action="{{ route('admin.banners.destroy', $banner->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this banner?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <!-- Agar koi banner na ho -->
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No banners found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $banners->links() }}
            </div>

        </div>
    </div>

</x-app-layout>