<!DOCTYPE html>
    <html lang="en">
        @include('components.head')
    <body data-bs-theme="{{ $theme }}">
        @include('components.navbar')

        <div class="container text-center" style="margin-top:10%;margin-bottom:5%;">
            <div class="row">
                @foreach($vehicles as $index => $vehicle)
                    <div class="col">
                        <div class="card" style="width: 18rem;">
                        @php
                            $imagePath = asset('images/' . $vehicle->image);
                        @endphp
                        <img src="{{ $imagePath }}" class="card-img-top" alt="{{ $vehicle->make }} {{ $vehicle->model}}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $vehicle->make }} {{ $vehicle->model}}</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <a href="{{ route('vehicles.show', $vehicle->id) }}" style="background-color: {{$color}}; border:none;" class="btn btn-primary">View Details</a>
                        </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @include('components.footer')
    </body>
</html>
