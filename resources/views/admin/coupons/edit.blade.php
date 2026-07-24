<x-app-layout>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-pencil-square"></i> Edit Coupon
        </h2>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-12 col-md-6">
                        <label for="code" class="form-label">Coupon Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="code"
                               class="form-control @error('code') is-invalid @enderror"
                               value="{{ old('code', $coupon->code) }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="discount_type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                        <select name="discount_type" id="discount_type" class="form-select @error('discount_type') is-invalid @enderror" required>
                            <option value="fixed" {{ old('discount_type', $coupon->discount_type) === 'fixed' ? 'selected' : '' }}>Fixed Amount (Rs)</option>
                            <option value="percentage" {{ old('discount_type', $coupon->discount_type) === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        </select>
                        @error('discount_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="discount_value" class="form-label">Discount Value <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="discount_value" id="discount_value"
                               class="form-control @error('discount_value') is-invalid @enderror"
                               value="{{ old('discount_value', $coupon->discount_value) }}" required>
                        @error('discount_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="expiry_date" class="form-label">Expiry Date <span class="text-danger">*</span></label>
                        <input type="date" name="expiry_date" id="expiry_date"
                               class="form-control @error('expiry_date') is-invalid @enderror"
                               value="{{ old('expiry_date', \Carbon\Carbon::parse($coupon->expiry_date)->format('Y-m-d')) }}" required>
                        @error('expiry_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="status" id="status" value="1"
                                   {{ old('status', $coupon->status) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                    </div>

                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Update Coupon
                    </button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-light">Cancel</a>
                </div>

            </form>

        </div>
    </div>

</x-app-layout>