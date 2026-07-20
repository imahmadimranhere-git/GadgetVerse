<x-app-layout>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">➕ Add New Banner</h2>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">

                    <div class="col-12">
                        <label for="title" class="form-label">Banner Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="image" class="form-label">Banner Image <span class="text-danger">*</span></label>
                        <input type="file" name="image" id="image"
                               class="form-control @error('image') is-invalid @enderror" accept="image/*" required>
                        <small class="text-muted">Recommended size: 1200x400px — Max 2MB</small>
                        @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="link" class="form-label">Link URL (Optional)</label>
                        <input type="text" name="link" id="link"
                               class="form-control @error('link') is-invalid @enderror"
                               value="{{ old('link') }}" placeholder="https://example.com">
                        @error('link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="status" id="status" value="1" checked>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                    </div>

                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Banner</button>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>