<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'GadgetVerse') }} - @yield('title', 'Home')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: 700; }
    </style>
</head>
<body>

    <!-- Navbar Include -->
    @include('layouts.navigation')

    <!-- Page Heading (agar koi page set kare) -->
    @isset($header)
        <header class="bg-white shadow-sm py-3 mb-3">
            <div class="container">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Main Content -->
    <main class="container py-4">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light text-center py-3 mt-5">
        <div class="container">
            <small>&copy; {{ date('Y') }} GadgetVerse. All Rights Reserved.</small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>