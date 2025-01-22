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
}
