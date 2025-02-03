@include('components.bootstrap')
@include('components.head')

<x-app-layout>
    <x-slot name="header">
        <h2>
            {{ __('Edit Vehicle') }}
        </h2>
    </x-slot>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="make" class="form-label">Make</label>
                                <input type="text" class="form-control" id="make" name="make"
                                    value="{{ $vehicle->make }}">
                            </div>
                            <div class="mb-3">
                                <label for="model" class="form-label">Model</label>
                                <input type="text" class="form-control" id="model" name="model"
                                    value="{{ $vehicle->model }}">
                            </div>
                            <div class="mb-3">
                                <label for="year" class="form-label">Year</label>
                                <input type="number" class="form-control" id="year" name="year"
                                    value="{{ $vehicle->year }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="engine" class="form-label">Engine</label>
                                <input type="text" class="form-control" id="engine" name="engine"
                                    value="{{ $vehicle->engine }}">
                            </div>
                            <div class="mb-3">
                                <label for="traction" class="form-label">Traction</label>
                                <input type="text" class="form-control" id="traction" name="traction"
                                    value="{{ $vehicle->traction }}">
                            </div>
                            <div class="mb-3">
                                <label for="seats" class="form-label">Seats</label>
                                <input type="number" class="form-control" id="seats" name="seats"
                                    value="{{ $vehicle->seats }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="luggage" class="form-label">Luggage</label>
                                <input type="text" class="form-control" id="luggage" name="luggage"
                                    value="{{ $vehicle->luggage }}">
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" class="form-control" id="price" name="price"
                                    value="{{ $vehicle->price }}">
                            </div>
                            <div class="mb-3">
                                <label for="transmission" class="form-label">Transmission</label>
                                <select class="form-control" id="transmission" name="transmission">
                                    <option value="manual" {{ $vehicle->transmission == 'manual' ? 'selected' : '' }}>
                                        Manual</option>
                                    <option value="automatic"
                                        {{ $vehicle->transmission == 'automatic' ? 'selected' : '' }}>Automatic
                                    </option>
                                    <option value="semi-automatic"
                                        {{ $vehicle->transmission == 'semi-automatic' ? 'selected' : '' }}>
                                        Semi-Automatic</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-control" id="type" name="type">
                                    <option value="premium" {{ $vehicle->type == 'premium' ? 'selected' : '' }}>Premium
                                    </option>
                                    <option value="luxury" {{ $vehicle->type == 'luxury' ? 'selected' : '' }}>Luxury
                                    </option>
                                    <option value="supercar" {{ $vehicle->type == 'supercar' ? 'selected' : '' }}>
                                        Supercar</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-danger" href="{{ route('vehicles.adminIndex') }}">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
