<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('components.head')
<body data-bs-theme="{{ $theme }}">
    @include('components.navbar')

    <div class="container" style="margin-top: 100px; text-align:center;">
        <h1>{{ $vehicle->make }} {{ $vehicle->model }}</h1>
    </div>
    <!-- @include('components.footer') -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
