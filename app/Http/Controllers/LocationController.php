<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getLocation(Request $request)
    {
        $locations = Location::all();

        return response()->json($locations);
    }

    public function createLocation(Request $request){
        $data = $request->all();

        $concatenatedName = ucfirst($data['name']) . "," . ucfirst($data['address']) . "," . ucfirst($data['city']);

        $location = new Location();
        $location->name = $concatenatedName;
        $location->save();

        return response()->json(['message' => 'Location created successfully', 'location' => $location], 201);
    }
}
