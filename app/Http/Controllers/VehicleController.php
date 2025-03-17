<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\VehicleMake;
use App\Models\VehicleModel;

class VehicleController extends Controller
{
    public function getVehicles(Request $request)
    {
        try {
            $vehicles = Vehicle::with('damages')->get();
            return response()->json($vehicles);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Errore nel recupero dei veicoli'], 500);
        }
    }

    public function getVehicleMakes()
    {
        $makes = VehicleMake::with('models')->get();
        return response()->json($makes);
    }
}
