<x-app-layout>

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-plus-circle me-2"></i> Add New Category
        </h2>

        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <!-- Add Category Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <!-- Category Add Form -->
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    <!-- Category Name -->
                    <div class="col-12 col-md-6">
                        <label for="name" class="form-label">
                            Category Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               required>

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Parent Category -->
                    <div class="col-12 col-md-6">
                        <label for="parent_id" class="form-label">
                            Parent Category (Optional)
                        </label>

                        <select name="parent_id"
                                id="parent_id"
                                class="form-select @error('parent_id') is-invalid @enderror">

                            <option value="">— None (Main Category) —</option>

                            @foreach ($parentCategories as $parent)
                                <option value="{{ $parent->id }}"
                                    {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category Image -->
                    <div class="col-12 col-md-6">
                        <label for="image" class="form-label">Category Image</label>

                        <input type="file"
                               name="image"
                               id="image"
                               class="form-control @error('image') is-invalid @enderror"
                               accept="image/*">

                        <small class="text-muted">JPG, PNG, WEBP — Max 2MB</small>

                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category Status -->
                    <div class="col-12 col-md-6">
                        <label class="form-label d-block">Status</label>

                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input"
                                   type="checkbox"
                                   role="switch"
                                   name="status"
                                   id="status"
                                   value="1"
                                   checked>

                            <label class="form-check-label" for="status">
                                Active
                            </label>
                        </div>
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Save Category
                    </button>

                    <a href="{{ route('admin.categories.index') }}" class="btn btn-light">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>

</x-app-layout>