<x-app-layout>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">✏️ Edit Brand</h2>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    @if ($brand->logo)
                        <div class="col-12">
                            <label class="form-label">Current Logo</label><br>
                            <img src="{{ asset('storage/' . $brand->logo) }}"
                                 class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                    @endif

                    <div class="col-12 col-md-6">
                        <label for="name" class="form-label">Brand Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $brand->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="logo" class="form-label">Change Logo (Optional)</label>
                        <input type="file" name="logo" id="logo"
                               class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                        @error('logo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="status" id="status" value="1"
                                   {{ old('status', $brand->status) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                    </div>

                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Update Brand
                    </button>
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-light">Cancel</a>
                </div>

            </form>

        </div>
    </div>

</x-app-layout>