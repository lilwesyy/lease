<!DOCTYPE html>
<html lang="en">
@include('components.head')

<body data-bs-theme="{{ $theme }}">
    @include('components.navbar')

    <div class="container text-center" style="margin-top:5%;">
        <div class="row">
            @foreach ($vehicles as $index => $vehicle)
                <div class="col-sm">
                    <div class="card">
                        @php
                            $imagePath = asset('images/' . $vehicle->image);
                        @endphp
                        <img src="{{ $imagePath }}" class="card-img-top fixed-height"
                            alt="{{ $vehicle->make }} {{ $vehicle->model }}">
                        <div class="card-body large-card">
                            <h5 class="card-title">{{ $vehicle->make }} {{ $vehicle->model }}</h5>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-gauge"></i> Engine: {{ $vehicle->engine }}</li>
                                <li><i class="fas fa-cog"></i> Transmission: {{ $vehicle->transmission }}</li>
                                <li><i class="fas fa-chair"></i> Seats: {{ $vehicle->seats }}</li>
                            </ul>
                            <a href="{{ route('vehicles.show', $vehicle->id) }}"
                                style="background-color: {{ $color }}; border:none;"
                                class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @include('components.footer')
</body>

</html>
