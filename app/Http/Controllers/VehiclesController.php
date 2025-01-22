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
            'year' => 'required|integer',
        ]);

        Vehicles::create([
            'make' => $request->make,
            'model' => $request->model,
            'year' => $request->year,
        ]);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully.');
    }
}
