<x-app-layout>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-image-fill me-2"></i> Add New Banner
        </h2>

        <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    <!-- Banner Title -->
                    <div class="col-12">
                        <label for="title" class="form-label">
                            Banner Title <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            id="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}"
                            placeholder="Enter banner title"
                            required
                        >

                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Banner Image -->
                    <div class="col-12">
                        <label for="image" class="form-label">
                            Banner Image <span class="text-danger">*</span>
                        </label>

                        <input
                            type="file"
                            name="image"
                            id="image"
                            class="form-control @error('image') is-invalid @enderror"
                            accept="image/*"
                            required
                        >

                        <small class="text-muted">
                            Recommended Size: 1200 × 400 px (Maximum File Size: 2 MB)
                        </small>

                        @error('image')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Banner Link -->
                    <div class="col-12">
                        <label for="link" class="form-label">
                            Banner Link (Optional)
                        </label>

                        <input
                            type="url"
                            name="link"
                            id="link"
                            class="form-control @error('link') is-invalid @enderror"
                            value="{{ old('link') }}"
                            placeholder="https://example.com"
                        >

                        @error('link')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Banner Status -->
                    <div class="col-12">
                        <div class="form-check form-switch mt-2">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="status"
                                name="status"
                                value="1"
                                checked
                            >

                            <label class="form-check-label" for="status">
                                Active
                            </label>
                        </div>
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="mt-4 d-flex gap-2">

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-floppy-fill me-1"></i>
                        Save Banner
                    </button>

                    <a href="{{ route('admin.banners.index') }}" class="btn btn-light border">
                        <i class="bi bi-x-circle me-1"></i>
                        Cancel
                    </a>

                </div>

            </form>

        </div>
    </div>

</x-app-layout>