<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('components.head')
<body data-bs-theme="{{ $theme }}">
    @include('components.navbar')

    <div class="container-fluid">
    <div class="row position-relative" style="display: flex; justify-content: center; align-items: center; height: 500px; text-align: center;">
        <img src="{{ asset('images/' . $vehicle->image)}}" alt="{{ $vehicle->make }} {{ $vehicle->model}}" style="width: 100%; height: 100%; object-fit: cover; position: absolute; z-index: -1;">
        <div style="position: absolute; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 0;"></div>
        <h1 style="position: relative; color: white; z-index: 1;">{{ $vehicle->make }} {{ $vehicle->model }}</h1>
    </div>
</div>

    @include('components.footer')

</body>
</html>
