<x-app-layout>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">➕ Add New Product</h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    <!-- Category -->
                    <div class="col-12 col-md-6">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Brand -->
                    <div class="col-12 col-md-6">
                        <label for="brand_id" class="form-label">Brand (Optional)</label>
                        <select name="brand_id" id="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                            <option value="">Select Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div class="col-12">
                        <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Specifications -->
                    <div class="col-12">
                        <label for="specifications" class="form-label">Specifications (Optional)</label>
                        <textarea name="specifications" id="specifications" rows="3"
                                  class="form-control @error('specifications') is-invalid @enderror"
                                  placeholder="e.g., RAM: 8GB, Storage: 128GB">{{ old('specifications') }}</textarea>
                        @error('specifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="col-6 col-md-3">
                        <label for="price" class="form-label">Price (Rs) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="price" id="price"
                               class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price') }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Discount Price -->
                    <div class="col-6 col-md-3">
                        <label for="discount_price" class="form-label">Discount Price (Optional)</label>
                        <input type="number" step="0.01" name="discount_price" id="discount_price"
                               class="form-control @error('discount_price') is-invalid @enderror"
                               value="{{ old('discount_price') }}">
                        @error('discount_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Stock Quantity -->
                    <div class="col-6 col-md-3">
                        <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="stock_quantity" id="stock_quantity"
                               class="form-control @error('stock_quantity') is-invalid @enderror"
                               value="{{ old('stock_quantity', 0) }}" required>
                        @error('stock_quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-6 col-md-3">
                        <label class="form-label d-block">Status</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="status" id="status" value="1" checked>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                    </div>

                    <!-- Featured -->
         <div class="col-6 col-md-3">
    <label class="form-label d-block">Featured</label>
    <div class="form-check form-switch mt-2">
        <input class="form-check-input" type="checkbox" role="switch"
               name="is_featured" id="is_featured" value="1">
        <label class="form-check-label" for="is_featured">Show on Home</label>
    </div>
</div>

                    <!-- Thumbnail -->
                    <div class="col-12 col-md-6">
                        <label for="thumbnail" class="form-label">Main Image (Thumbnail) <span class="text-danger">*</span></label>
                        <input type="file" name="thumbnail" id="thumbnail"
                               class="form-control @error('thumbnail') is-invalid @enderror"
                               accept="image/*" required>
                        <small class="text-muted">JPG, PNG, WEBP — Max 2MB</small>
                        @error('thumbnail')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Gallery -->
                    <div class="col-12 col-md-6">
                        <label for="gallery" class="form-label">Gallery Images (Optional, Multiple)</label>
                        <input type="file" name="gallery[]" id="gallery"
                               class="form-control @error('gallery.*') is-invalid @enderror"
                               accept="image/*" multiple>
                        <small class="text-muted">Ek se zyada images select kar sakte hain</small>
                        @error('gallery.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Save Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light">Cancel</a>
                </div>

            </form>

        </div>
    </div>

</x-app-layout>