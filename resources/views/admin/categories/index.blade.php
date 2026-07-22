<x-app-layout>

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-folder2-open me-2"></i> Categories
        </h2>

        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Category
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Category List Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <!-- Category Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Parent Category</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <!-- Agar categories mojood hon -->
                        @forelse ($categories as $category)
                            <tr>

                                <!-- Category Image -->
                                <td>
                                    @if ($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}"
                                             alt="{{ $category->name }}"
                                             class="rounded"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>

                                <!-- Category Name -->
                                <td class="fw-semibold">
                                    {{ $category->name }}
                                </td>

                                <!-- Parent Category -->
                                <td>
                                    @if ($category->parent)
                                        <span class="badge bg-info-subtle text-info-emphasis">
                                            {{ $category->parent->name }}
                                        </span>
                                    @else
                                        <span class="text-muted small">— Main Category —</span>
                                    @endif
                                </td>

                                <!-- Category Status -->
                                <td>
                                    @if ($category->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>

                                <!-- Action Buttons -->
                                <td class="text-end">

                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Delete Form -->
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this category?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <!-- Agar koi category na ho -->
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No categories found. Click "Add New Category" to create one.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $categories->links() }}
            </div>

        </div>
    </div>

</x-app-layout>