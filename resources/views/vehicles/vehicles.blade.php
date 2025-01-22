@include('components.bootstrap')

<x-app-layout>
    <x-slot name="header">
        <h2 >
            {{ __('Vehicles') }}
        </h2>
    </x-slot>

    <div class="py-12">
    <div class="container">
        <div class="card shadow-sm">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <a href="" class="btn btn-success mb-4">Add New Vehicle</a>
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table-auto w-full">
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
                            @foreach($vehicles as $vehicle)
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
                                <td class="border px-4 py-2">{{ $vehicle->transmission }}</td>
                                <td class="border px-4 py-2">{{ $vehicle->type }}</td>
                                <td class="border px-4 py-2 inline-flex space-x-1">
                                    <a href="{{ route('vehicles.show', $vehicle->id) }}" class="btn btn-primary">View</a>
                                    <a href="" class="btn btn-secondary">Edit</a>
                                    <form action="" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
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
</div>
</x-app-layout>
