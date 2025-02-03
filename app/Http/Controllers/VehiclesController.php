<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Vehicles;

class VehiclesController extends Controller
{
    public function show($id)
    {
        $vehicle = Vehicles::find($id);

        if (!$vehicle) {
            return redirect('/')->with('error', 'Veicolo non trovato.');
        }

        $setting = Setting::first();
        $favicon = $setting ? $setting->favicon : null;
        $name = $setting ? $setting->name : null;
        $color = $setting ? $setting->primary_color : null;
        $theme = $setting ? $setting->theme : null;

        return view('vehicles.show', compact('vehicle', 'favicon', 'name', 'color', 'theme'));
    }

    public function list()
    {
        $setting = Setting::first();
        $favicon = $setting ? $setting->favicon : null;
        $name = $setting ? $setting->name : null;
        $color = $setting ? $setting->primary_color : null;
        $theme = $setting ? $setting->theme : null;
        $vehicles = Vehicles::all();

        return view('vehicles.list', compact('vehicles', 'favicon', 'name', 'color', 'theme'));
    }

    public function adminIndex()
    {
        $vehicles = Vehicles::all();
        $setting = Setting::first();
        $favicon = $setting ? $setting->favicon : null;
        $name = $setting ? $setting->name : null;
        $color = $setting ? $setting->primary_color : null;
        $theme = $setting ? $setting->theme : null;

        return view('vehicles.vehicles', compact('vehicles', 'favicon', 'name', 'color'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1886|max:' . date('Y'),
            'engine' => 'required|numeric|min:0',
            'traction' => 'required|string|max:255',
            'seats' => 'required|integer|min:1|max:50',
            'luggage' => 'required|integer|min:0|max:100',
            'price' => 'required|numeric|min:0',
            'transmission' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ], [
            'year.min' => 'The year must be at least 1886.',
            'year.max' => 'The year cannot be greater than the current year.',
            'engine.min' => 'The engine size must be a positive number.',
            'seats.min' => 'The vehicle must have at least one seat.',
            'seats.max' => 'The vehicle cannot have more than 50 seats.',
            'luggage.min' => 'The luggage capacity must be a positive number.',
            'luggage.max' => 'The luggage capacity cannot exceed 100.',
            'price.min' => 'The price must be a positive number.',
        ]);

        // Additional control: Check if the vehicle already exists
        $existingVehicle = Vehicles::where('make', $request->input('make'))
            ->where('model', $request->input('model'))
            ->where('year', $request->input('year'))
            ->first();

        if ($existingVehicle) {
            return redirect()->back()->withErrors(['Vehicle already exists.'])->withInput();
        }

        $vehicle = new Vehicles();
        $vehicle->make = $request->input('make');
        $vehicle->model = $request->input('model');
        $vehicle->year = $request->input('year');
        $vehicle->engine = $request->input('engine');
        $vehicle->traction = $request->input('traction');
        $vehicle->seats = $request->input('seats');
        $vehicle->luggage = $request->input('luggage');
        $vehicle->price = $request->input('price');
        $vehicle->transmission = $request->input('transmission');
        $vehicle->type = $request->input('type');
        $vehicle->save();

        session()->flash('notification', ['type' => 'success', 'message' => 'Vehicle created successfully.']);

        return redirect()->route('vehicles.adminIndex')->with('success', 'Vehicle created successfully.');
    }

    public function delete($id)
    {
        $vehicle = Vehicles::find($id);

        if (!$vehicle) {
            return redirect()->route('vehicles.adminIndex')->withErrors(['Vehicle not found.']);
        }

        $vehicle->delete();

        return redirect()->route('vehicles.adminIndex')->with('notification', ['type' => 'danger', 'message' => 'Vehicle deleted successfully.']);

    }

    public function edit($id)
    {
        $setting = Setting::first();
        $favicon = $setting ? $setting->favicon : null;
        $name = $setting ? $setting->name : null;
        $color = $setting ? $setting->primary_color : null;
        $theme = $setting ? $setting->theme : null;
        $vehicle = Vehicles::findOrFail($id);

        return view('vehicles.edit', compact('vehicle', 'favicon', 'name', 'color'));
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicles::findOrFail($id);
        $vehicle->update($request->all());
        return redirect()->route('vehicles.adminIndex')->with('notification', ['type' => 'success', 'message' => 'Vehicle updated successfully!']);
    }
}
