<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function getVehicles(Request $request)
    {
        try {
            // Ottieni tutti i veicoli con i relativi danni
            $vehicles = Vehicle::with('damages')->get();
            return response()->json($vehicles);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Errore nel recupero dei veicoli'], 500);
        }
    }
}
