<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    public function getVehicles(Request $request)
    {
        try {
            $vehicles = Vehicle::with([
                'damages',
                'make:id,name,icon',
                'model'
            ])->get();

            return response()->json($vehicles);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Errore nel recupero dei veicoli: ' . $e->getMessage()], 500);
        }
    }

    public function getVehicleMakes()
    {
        $makes = VehicleMake::with('models')->get();
        return response()->json($makes);
    }

    public function createVehicle(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'make_id' => 'required|exists:vehicle_makes,id',
                'model_id' => 'required|exists:vehicle_models,id',
                'bodyType' => 'required|string',
                'year' => 'required|integer',
                'location' => 'required|string',
                'plateNumber' => 'required|string|unique:vehicles,plateNumber',
                'odometer' => 'required|numeric',
                'color' => 'required|string',
                'seats' => 'required|integer',
                'fuel_type' => 'required|string',
                'transmission' => 'required|string',
                'km_per_day' => 'required|numeric',
                'extra_km_price' => 'required|numeric',
                'basic_daily_price' => 'required|numeric',
                'dailyPrice' => 'required|numeric',
                'franchise' => 'required|numeric',
                'deposit' => 'required|numeric',
                'status' => 'required|string',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $vehicle = new Vehicle();
            $vehicle->make_id = $validatedData['make_id'];
            $vehicle->model_id = $validatedData['model_id'];
            $vehicle->bodyType = $validatedData['bodyType'];
            $vehicle->year = $validatedData['year'];
            $vehicle->location = $validatedData['location'];
            $vehicle->plateNumber = $validatedData['plateNumber'];
            $vehicle->odometer = $validatedData['odometer'];
            $vehicle->color = $validatedData['color'];
            $vehicle->seats = $validatedData['seats'];
            $vehicle->fuel_type = $validatedData['fuel_type'];
            $vehicle->transmission = $validatedData['transmission'];
            $vehicle->km_per_day = $validatedData['km_per_day'];
            $vehicle->extra_km_price = $validatedData['extra_km_price'];
            $vehicle->basic_daily_price = $validatedData['basic_daily_price'];
            $vehicle->dailyPrice = $validatedData['dailyPrice'];
            $vehicle->franchise = $validatedData['franchise'];
            $vehicle->deposit = $validatedData['deposit'];
            $vehicle->status = $validatedData['status'];
            $vehicle->imageUrl = 'default-vehicle.jpg'; // Valore predefinito per imageUrl

            $vehicle->save();

            if ($request->hasFile('photos')) {
                $photos = [];
                foreach ($request->file('photos') as $index => $photo) {
                    $path = $photo->store('vehicle_photos', 'public');
                    $photos[] = $path;
                }
                $vehicle->photos = json_encode($photos);

                // Se ci sono foto caricate, usa la prima come imageUrl
                if (count($photos) > 0) {
                    $vehicle->imageUrl = $photos[0];
                }

                $vehicle->save();
            }

            $createdVehicle = Vehicle::with([
                'damages',
                'make:id,name,icon',
                'model'
            ])->findOrFail($vehicle->id);

            return response()->json([
                'message' => 'Veicolo creato con successo',
                'vehicle' => $createdVehicle
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Dati non validi', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Errore nella creazione del veicolo: ' . $e->getMessage());
            return response()->json(['error' => 'Errore nella creazione del veicolo: ' . $e->getMessage()], 500);
        }
    }

    public function editVehicle(Request $request, $id)
    {
        try {
            $vehicle = Vehicle::findOrFail($id);

            $validatedData = $request->validate([
                'make_id' => 'required|exists:vehicle_makes,id',
                'model_id' => 'required|exists:vehicle_models,id',
                'bodyType' => 'required|string',
                'year' => 'required|integer',
                'location' => 'required|string',
                'plateNumber' => 'required|string|unique:vehicles,plateNumber,' . $id,
                'odometer' => 'required|numeric',
                'color' => 'required|string',
                'seats' => 'required|integer',
                'fuel_type' => 'required|string',
                'transmission' => 'required|string',
                'km_per_day' => 'required|numeric',
                'extra_km_price' => 'required|numeric',
                'basic_daily_price' => 'required|numeric',
                'dailyPrice' => 'required|numeric',
                'franchise' => 'required|numeric',
                'status' => 'required|string'
            ]);

            $originalData = $vehicle->toArray();

            $vehicle->make_id = $validatedData['make_id'];
            $vehicle->model_id = $validatedData['model_id'];
            $vehicle->bodyType = $validatedData['bodyType'];
            $vehicle->year = $validatedData['year'];
            $vehicle->location = $validatedData['location'];
            $vehicle->plateNumber = $validatedData['plateNumber'];
            $vehicle->odometer = $validatedData['odometer'];
            $vehicle->color = $validatedData['color'];
            $vehicle->seats = $validatedData['seats'];
            $vehicle->fuel_type = $validatedData['fuel_type'];
            $vehicle->transmission = $validatedData['transmission'];
            $vehicle->km_per_day = $validatedData['km_per_day'];
            $vehicle->extra_km_price = $validatedData['extra_km_price'];
            $vehicle->basic_daily_price = $validatedData['basic_daily_price'];
            $vehicle->dailyPrice = $validatedData['dailyPrice'];
            $vehicle->franchise = $validatedData['franchise'];
            $vehicle->status = $validatedData['status'];

            // Se non c'è imageUrl, imposta un valore predefinito
            if (empty($vehicle->imageUrl)) {
                $vehicle->imageUrl = 'default-vehicle.jpg';
            }

            $vehicle->save();

            // Gestione delle foto se presenti nella richiesta
            if ($request->hasFile('photos')) {
                $photos = [];
                foreach ($request->file('photos') as $index => $photo) {
                    $path = $photo->store('vehicle_photos', 'public');
                    $photos[] = $path;
                }
                $vehicle->photos = json_encode($photos);

                // Se ci sono nuove foto, usa la prima come imageUrl
                if (count($photos) > 0) {
                    $vehicle->imageUrl = $photos[0];
                }

                $vehicle->save();
            }

            $updatedVehicle = Vehicle::with([
                'damages',
                'make:id,name,icon',
                'model'
            ])->findOrFail($id);

            return response()->json([
                'message' => 'Veicolo aggiornato con successo',
                'vehicle' => $updatedVehicle
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Veicolo non trovato'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Dati non validi', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Errore nell\'aggiornamento del veicolo: ' . $e->getMessage());
            return response()->json(['error' => 'Errore nell\'aggiornamento del veicolo: ' . $e->getMessage()], 500);
        }
    }

    public function deleteVehicle($id)
    {
        try {
            $vehicle = Vehicle::findOrFail($id);

            // Elimina le foto associate se necessario
            if (!empty($vehicle->photos)) {
                $photos = json_decode($vehicle->photos, true);
                foreach ($photos as $photo) {
                    Storage::disk('public')->delete($photo);
                }
            }

            $vehicle->delete();

            return response()->json(['message' => 'Veicolo eliminato con successo']);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Veicolo non trovato'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Errore nell\'eliminazione del veicolo: ' . $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $vehicle = Vehicle::findOrFail($id);

            $validatedData = $request->validate([
                'status' => 'required|string|in:available,unavailable,maintenance',
            ]);

            $vehicle->status = $validatedData['status'];
            $vehicle->save();

            return response()->json([
                'message' => 'Stato del veicolo aggiornato con successo',
                'vehicle' => $vehicle
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Veicolo non trovato'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Dati non validi', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Errore nell\'aggiornamento dello stato: ' . $e->getMessage()], 500);
        }
    }
}
