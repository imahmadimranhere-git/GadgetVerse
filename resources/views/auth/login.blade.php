<x-guest-layout>

    <h4 class="text-center mb-4 fw-bold">Login to Your Account</h4>

    <!-- Session Status (jaise "password reset link sent") -->
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}"
                   required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" name="remember" id="remember">
            <label class="form-check-label" for="remember">Remember Me</label>
        </div>

        <!-- Submit + Links -->
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Log in</button>
        </div>

        <div class="text-center mt-3">
            @if (Route::has('password.request'))
                <a class="d-block small text-decoration-none mb-2" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            @endif
            <span class="small">Don't have an account?</span>
            <a href="{{ route('register') }}" class="small text-decoration-none">Register</a>
        </div>
    </form>

</x-guest-layout>