<x-app-layout>

    <h2 class="fw-bold mb-4">
        <i class="bi bi-bag-check"></i> Checkout
    </h2>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf

        <div class="row g-4">

            <div class="col-12 col-lg-8">

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-geo-alt"></i> Delivery Address
                        </h5>

                        @if ($addresses->count() > 0)
                            @foreach ($addresses as $address)
                                <div class="form-check border rounded p-3 mb-2">
                                    <input class="form-check-input" type="radio" name="address_id"
                                           id="address{{ $address->id }}" value="{{ $address->id }}"
                                           {{ $loop->first ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="address{{ $address->id }}">
                                        <strong>{{ $address->full_name }}</strong> — {{ $address->phone }}<br>
                                        <span class="text-muted small">{{ $address->address_line }}, {{ $address->city }} {{ $address->postal_code }}</span>
                                    </label>
                                </div>
                            @endforeach

                            <div class="form-check border rounded p-3 mb-2">
                                <input class="form-check-input" type="radio" name="address_id"
                                       id="newAddress" value="" onclick="document.getElementById('newAddressForm').classList.remove('d-none')">
                                <label class="form-check-label" for="newAddress">
                                    <strong>+ Add New Address</strong>
                                </label>
                            </div>
                        @endif

                        <div id="newAddressForm" class="{{ $addresses->count() > 0 ? 'd-none' : '' }} mt-3 border-top pt-3">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}">
                                    @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <input type="text" name="address_line" class="form-control @error('address_line') is-invalid @enderror" value="{{ old('address_line') }}">
                                    @error('address_line')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}">
                                    @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Postal Code (Optional)</label>
                                    <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-credit-card"></i> Payment Method
                        </h5>

                        <div class="form-check border rounded p-3 mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                            <label class="form-check-label" for="cod">
                                <i class="bi bi-cash"></i> Cash on Delivery (COD)
                            </label>
                        </div>
                        <div class="form-check border rounded p-3 mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="jazzcash" value="jazzcash">
                            <label class="form-check-label" for="jazzcash">
                                <i class="bi bi-phone"></i> JazzCash
                            </label>
                        </div>
                        <div class="form-check border rounded p-3 mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="easypaisa" value="easypaisa">
                            <label class="form-check-label" for="easypaisa">
                                <i class="bi bi-phone"></i> EasyPaisa
                            </label>
                        </div>
                        <div class="form-check border rounded p-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe">
                            <label class="form-check-label" for="stripe">
                                <i class="bi bi-credit-card-2-front"></i> Credit/Debit Card (Stripe)
                            </label>
                        </div>
                        @error('payment_method')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Order Summary</h5>

                        @foreach ($cartItems as $item)
                            @php $price = $item->product->discount_price ?? $item->product->price; @endphp
                            <div class="d-flex justify-content-between mb-2 small">
                                <span>{{ Str::limit($item->product->name, 20) }} × {{ $item->quantity }}</span>
                                <span>Rs {{ number_format($price * $item->quantity) }}</span>
                            </div>
                        @endforeach

                        <hr>

                        {{-- Coupon apply karne ka section --}}
                        @if ($appliedCoupon)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small">
                                    <i class="bi bi-ticket-perforated-fill text-success"></i>
                                    <strong>{{ $appliedCoupon['code'] }}</strong> applied
                                </span>
                                <form action="{{ route('checkout.removeCoupon') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0">Remove</button>
                                </form>
                            </div>
                        @else
                            <div class="mb-2">
                                <label class="form-label small">Have a coupon code?</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" id="couponCodeField" class="form-control" placeholder="Enter coupon code">
                                    <button type="button" id="applyCouponBtn" class="btn btn-outline-primary">Apply</button>
                                </div>
                            </div>
                        @endif

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Subtotal</span>
                            <span class="small">Rs {{ number_format($subtotal) }}</span>
                        </div>

                        @if ($discount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Discount</span>
                                <span class="small text-success">- Rs {{ number_format($discount) }}</span>
                            </div>
                        @endif

                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold fs-5">Rs {{ number_format($total) }}</span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Place Order
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>

    {{-- Coupon apply karne ke liye ek chota hidden form, JS se submit hoga --}}
    <form id="applyCouponForm" action="{{ route('checkout.applyCoupon') }}" method="POST" class="d-none">
        @csrf
        <input type="hidden" name="coupon_code" id="hiddenCouponCode">
    </form>

    <script>
        // Apply button click hone pe hidden form submit karna
        document.getElementById('applyCouponBtn')?.addEventListener('click', function () {
            const code = document.getElementById('couponCodeField').value;
            document.getElementById('hiddenCouponCode').value = code;
            document.getElementById('applyCouponForm').submit();
        });
    </script>

</x-app-layout>