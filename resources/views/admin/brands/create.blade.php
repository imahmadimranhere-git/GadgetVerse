<x-app-layout>

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-plus-circle me-2"></i> Add New Brand
        </h2>

        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <!-- Add Brand Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <!-- Brand Add Form -->
            <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    <!-- Brand Name -->
                    <div class="col-12 col-md-6">
                        <label for="name" class="form-label">Brand Name <span class="text-danger">*</span></label>

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

                    <!-- Brand Logo -->
                    <div class="col-12 col-md-6">
                        <label for="logo" class="form-label">Brand Logo</label>

                        <input type="file"
                               name="logo"
                               id="logo"
                               class="form-control @error('logo') is-invalid @enderror"
                               accept="image/*">

                        <small class="text-muted">JPG, PNG, WEBP — Max 2MB</small>

                        @error('logo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Brand Status -->
                    <div class="col-12">
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
                        <i class="bi bi-check-lg"></i> Save Brand
                    </button>

                    <a href="{{ route('admin.brands.index') }}" class="btn btn-light">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>

</x-app-layout>