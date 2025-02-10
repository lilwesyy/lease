@include('components.bootstrap')
@include('components.head')

<x-app-layout>
    <x-slot name="header">
        <h2>
            {{ __('Vehicles') }}
        </h2>
    </x-slot>
    <div class="container-fluid">
        @include('components.notifications')
        <div class="card">
            <div class="card-body">
                <!-- <a href="" class="btn btn-success mb-4">Add New Vehicle</a> -->
                <a class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#addVehicleModal">Add New
                    Vehicle</a>

                <!-- Modal -->
                <div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addVehicleModalLabel">Add New Vehicle</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('vehicles.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                            <div class="mb-3">
                                                <label for="make" class="form-label">Make</label>
                                                <input type="text" class="form-control" id="make"
                                                    name="make">
                                            </div>
                                            <div class="mb-3">
                                                <label for="model" class="form-label">Model</label>
                                                <input type="text" class="form-control" id="model"
                                                    name="model">
                                            </div>
                                            <div class="mb-3">
                                                <label for="year" class="form-label">Year</label>
                                                <input type="number" class="form-control" id="year"
                                                    name="year">
                                            </div>
                                            <div class="mb-3">
                                                <label for="engine" class="form-label">Engine</label>
                                                <input type="text" class="form-control" id="engine"
                                                    name="engine">
                                            </div>
                                            <div class="mb-3">
                                                <label for="traction" class="form-label">Traction</label>
                                                <input type="text" class="form-control" id="traction"
                                                    name="traction">
                                            </div>
                                            <div class="mb-3">
                                                <label for="seats" class="form-label">Seats</label>
                                                <input type="number" class="form-control" id="seats"
                                                    name="seats">
                                            </div>
                                            <div class="mb-3">
                                                <label for="luggage" class="form-label">Luggage</label>
                                                <input type="text" class="form-control" id="luggage"
                                                    name="luggage">
                                            </div>
                                            <div class="mb-3">
                                                <label for="price" class="form-label">Price</label>
                                                <input type="number" class="form-control" id="price"
                                                    name="price">
                                            </div>
                                            <div class="mb-3">
                                                <label for="transmission" class="form-label">Transmission</label>
                                                <select class="form-control" id="transmission" name="transmission">
                                                    <option value="manual">Manual</option>
                                                    <option value="automatic">Automatic</option>
                                                    <option value="semi-automatic">Semi-Automatic</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="type" class="form-label">Type</label>
                                                <select class="form-control" id="type" name="type">
                                                    <option value="premium">Premium</option>
                                                    <option value="luxury">Luxury</option>
                                                    <option value="supercar">Supercar</option>
                                                </select>
                                            </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Make</th>
                        <th class="px-4 py-2">Model</th>
                        <th class="px-4 py-2">Year</th>
                        <th class="px-4 py-2">Engine</th>
                        <th class="px-4 py-2">Traction</th>
                        <th class="px-4 py-2">Seats</th>
                        <th class="px-4 py-2">Luggage</th>
                        <th class="px-4 py-2">Price</th>
                        <th class="px-4 py-2">Transmission</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vehicles as $vehicle)
                        <tr>
                            <td class="border px-4 py-2">{{ $vehicle->id }}</td>
                            <td class="border px-4 py-2">{{ $vehicle->make }}</td>
                            <td class="border px-4 py-2">{{ $vehicle->model }}</td>
                            <td class="border px-4 py-2">{{ $vehicle->year }}</td>
                            <td class="border px-4 py-2">{{ $vehicle->engine }}</td>
                            <td class="border px-4 py-2">{{ $vehicle->traction }}</td>
                            <td class="border px-4 py-2">{{ $vehicle->seats }}</td>
                            <td class="border px-4 py-2">{{ $vehicle->luggage }}</td>
                            <td class="border px-4 py-2">{{ $vehicle->price }}</td>
                            <td class="border px-4 py-2 text-capitalize">{{ $vehicle->transmission }}</td>
                            <td class="border px-4 py-2 text-uppercase">{{ $vehicle->type }}</td>
                            <td class="border px-4 py-2 inline-flex space-x-1">
                                <a href="{{ route('vehicles.show', $vehicle->id) }}" target="_blank" type="button"
                                    class="btn btn-primary m-1">View</a>
                                <a href="{{ route('vehicles.edit', $vehicle->id) }}" type="button"
                                    class="btn btn-secondary m-1">Edit</a>
                                <a href="#"
                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $vehicle->id }}').submit();"
                                    class="btn btn-danger m-1">Delete</a>
                                <form id="delete-form-{{ $vehicle->id }}"
                                    action="{{ route('vehicles.delete', $vehicle->id) }}" method="POST"
                                    class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
    </div>
</x-app-layout>
