<x-app-layout>

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-pencil-square me-2"></i> Edit Product
        </h2>

        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <!-- Edit Product Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <!-- Product Update Form -->
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <!-- Category -->
                    <div class="col-12 col-md-6">
                        <label for="category_id" class="form-label">
                            Category <span class="text-danger">*</span>
                        </label>

                        <select name="category_id"
                                id="category_id"
                                class="form-select @error('category_id') is-invalid @enderror"
                                required>

                            <option value="">Select Category</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                        <label for="brand_id" class="form-label">
                            Brand (Optional)
                        </label>

                        <select name="brand_id"
                                id="brand_id"
                                class="form-select @error('brand_id') is-invalid @enderror">

                            <option value="">Select Brand</option>

                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('brand_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Product Name -->
                    <div class="col-12">
                        <label for="name" class="form-label">
                            Product Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $product->name) }}"
                               required>

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Product Description -->
                    <div class="col-12">
                        <label for="description" class="form-label">
                            Description <span class="text-danger">*</span>
                        </label>

                        <textarea name="description"
                                  id="description"
                                  rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  required>{{ old('description', $product->description) }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Product Specifications -->
                    <div class="col-12">
                        <label for="specifications" class="form-label">
                            Specifications (Optional)
                        </label>

                        <textarea name="specifications"
                                  id="specifications"
                                  rows="3"
                                  class="form-control @error('specifications') is-invalid @enderror">{{ old('specifications', $product->specifications) }}</textarea>

                        @error('specifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Product Price -->
                    <div class="col-6 col-md-3">
                        <label for="price" class="form-label">
                            Price (Rs) <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               step="0.01"
                               name="price"
                               id="price"
                               class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price', $product->price) }}"
                               required>

                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Discount Price -->
                    <div class="col-6 col-md-3">
                        <label for="discount_price" class="form-label">
                            Discount Price
                        </label>

                        <input type="number"
                               step="0.01"
                               name="discount_price"
                               id="discount_price"
                               class="form-control @error('discount_price') is-invalid @enderror"
                               value="{{ old('discount_price', $product->discount_price) }}">

                        @error('discount_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Stock Quantity -->
                    <div class="col-6 col-md-3">
                        <label for="stock_quantity" class="form-label">
                            Stock Quantity <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="stock_quantity"
                               id="stock_quantity"
                               class="form-control @error('stock_quantity') is-invalid @enderror"
                               value="{{ old('stock_quantity', $product->stock_quantity) }}"
                               required>

                        @error('stock_quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Product Status -->
                    <div class="col-6 col-md-3">
                        <label class="form-label d-block">Status</label>

                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input"
                                   type="checkbox"
                                   role="switch"
                                   name="status"
                                   id="status"
                                   value="1"
                                   {{ old('status', $product->status) ? 'checked' : '' }}>

                            <label class="form-check-label" for="status">
                                Active
                            </label>
                        </div>
                    </div>

                    <!-- Featured Product -->
                    <div class="col-6 col-md-3">
                        <label class="form-label d-block">Featured</label>

                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input"
                                   type="checkbox"
                                   role="switch"
                                   name="is_featured"
                                   id="is_featured"
                                   value="1"
                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>

                            <label class="form-check-label" for="is_featured">
                                Show on Home
                            </label>
                        </div>
                    </div>

                    <!-- Current Thumbnail -->
                    <div class="col-12 col-md-6">
                        <label class="form-label">Current Thumbnail</label><br>

                        <img src="{{ asset('storage/' . $product->thumbnail) }}"
                             class="rounded mb-2"
                             style="width: 80px; height: 80px; object-fit: cover;"><br>

                        <label for="thumbnail" class="form-label">
                            Change Thumbnail (Optional)
                        </label>

                        <input type="file"
                               name="thumbnail"
                               id="thumbnail"
                               class="form-control @error('thumbnail') is-invalid @enderror"
                               accept="image/*">

                        @error('thumbnail')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Current Gallery Images -->
                    <div class="col-12 col-md-6">
                        <label class="form-label">Current Gallery</label>

                        <div class="d-flex flex-wrap gap-2 mb-2">

                            <!-- Gallery Images Show Karna -->
                            @forelse ($product->images as $image)
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                         class="rounded"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                </div>
                            @empty
                                <span class="text-muted small">
                                    No gallery images found.
                                </span>
                            @endforelse

                        </div>

                        <!-- Nai Gallery Images Upload Karna -->
                        <label for="gallery" class="form-label">
                            Add More Images (Optional)
                        </label>

                        <input type="file"
                               name="gallery[]"
                               id="gallery"
                               class="form-control @error('gallery.*') is-invalid @enderror"
                               accept="image/*"
                               multiple>

                        @error('gallery.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Update Product
                    </button>

                    <a href="{{ route('admin.products.index') }}" class="btn btn-light">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>

</x-app-layout>