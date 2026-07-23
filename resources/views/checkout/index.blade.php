<x-app-layout>

    {{-- Page ka heading, cart/shopping bag icon ke sath --}}
    <h2 class="fw-bold mb-4"><i class="bi bi-bag-check"></i> Checkout</h2>

    {{-- Agar koi error session mai aya hai to usay dismissible alert mai show karain --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Checkout form, jo order place karne ke route par POST karega --}}
    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf

        <div class="row g-4">

            <!-- Left: Address + Payment -->
            <div class="col-12 col-lg-8">

                <!-- Address Selection -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt"></i> Delivery Address</h5>

                        {{-- Agar user ke pehle se saved addresses hain to unhain radio list ki tarah dikhain --}}
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

                            {{-- Naya address add karne ka option, click karne par new address form show hoga --}}
                            <div class="form-check border rounded p-3 mb-2">
                                <input class="form-check-input" type="radio" name="address_id"
                                       id="newAddress" value="" onclick="document.getElementById('newAddressForm').classList.remove('d-none')">
                                <label class="form-check-label" for="newAddress">
                                    <strong><i class="bi bi-plus-circle"></i> Add New Address</strong>
                                </label>
                            </div>
                        @endif

                        <!-- New Address Form -->
                        {{-- Agar koi saved address nahi hai to yeh form directly show hoga, warna hidden rahega --}}
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

                <!-- Payment Method -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3"><i class="bi bi-credit-card"></i> Payment Method</h5>

                        {{-- Cash on delivery, ye default selected option hai --}}
                        <div class="form-check border rounded p-3 mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                            <label class="form-check-label" for="cod">
                                <i class="bi bi-cash"></i> Cash on Delivery (COD)
                            </label>
                        </div>

                        {{-- Mobile wallet options --}}
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

                        {{-- Card payment via Stripe --}}
                        <div class="form-check border rounded p-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe">
                            <label class="form-check-label" for="stripe">
                                <i class="bi bi-credit-card"></i> Credit/Debit Card (Stripe)
                            </label>
                        </div>

                        {{-- Payment method ki validation error yahan show hogi --}}
                        @error('payment_method')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>

            <!-- Right: Order Summary -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Order Summary</h5>

                        {{-- Cart mai jitne items hain, un sab ko loop kar ke price ke sath list karain --}}
                        @foreach ($cartItems as $item)
                            @php $price = $item->product->discount_price ?? $item->product->price; @endphp
                            <div class="d-flex justify-content-between mb-2 small">
                                <span>{{ Str::limit($item->product->name, 20) }} × {{ $item->quantity }}</span>
                                <span>Rs {{ number_format($price * $item->quantity) }}</span>
                            </div>
                        @endforeach

                        <hr>
                        {{-- Grand total show karain --}}
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold fs-5">Rs {{ number_format($total) }}</span>
                        </div>

                        {{-- Order place karne ka submit button --}}
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Place Order
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>

</x-app-layout>