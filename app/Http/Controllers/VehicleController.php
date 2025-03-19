<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Models\VehicleImage; // Aggiungi questa importazione
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    public function getVehicles(Request $request)
    {
        try {
            $vehicles = Vehicle::with([
                'damages',
                'make:id,name,icon',
                'model',
                'images' // Aggiungi le immagini ai dati caricati
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
                'indices.*' => 'nullable|integer|min:0|max:2',
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

            // Gestisci il caricamento delle immagini come elementi separati
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $key => $photo) {
                    // Ottieni l'indice dalla richiesta o usa la chiave
                    $index = $request->indices[$key] ?? $key;

                    // Memorizza l'immagine nella cartella vehicle_photos
                    $path = $photo->store('vehicle_photos', 'public');

                    // Crea un nuovo record VehicleImage
                    $vehicleImage = new VehicleImage();
                    $vehicleImage->vehicle_id = $vehicle->id;
                    $vehicleImage->url = '/storage/' . $path;
                    $vehicleImage->position = $index;
                    $vehicleImage->save();

                    // Se è la prima immagine, imposta anche imageUrl del veicolo
                    if ($key === array_key_first($request->file('photos'))) {
                        $vehicle->imageUrl = $path;
                        $vehicle->save();
                    }
                }
            }

            $createdVehicle = Vehicle::with([
                'damages',
                'make:id,name,icon',
                'model',
                'images' // Carica anche le immagini
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
                foreach ($request->file('photos') as $key => $photo) {
                    // Ottieni l'indice dalla richiesta o usa la chiave
                    $index = $request->indices[$key] ?? $key;

                    // Controlla se esiste già un'immagine a questa posizione
                    $existingImage = VehicleImage::where('vehicle_id', $id)
                        ->where('position', $index)
                        ->first();

                    // Salva l'immagine
                    $path = $photo->store('vehicle_photos', 'public');

                    if ($existingImage) {
                        // Elimina il vecchio file
                        if (Storage::disk('public')->exists(str_replace('/storage/', '', $existingImage->url))) {
                            Storage::disk('public')->delete(str_replace('/storage/', '', $existingImage->url));
                        }

                        // Aggiorna il record esistente
                        $existingImage->url = '/storage/' . $path;
                        $existingImage->save();
                    } else {
                        // Crea un nuovo record
                        $vehicleImage = new VehicleImage();
                        $vehicleImage->vehicle_id = $id;
                        $vehicleImage->url = '/storage/' . $path;
                        $vehicleImage->position = $index;
                        $vehicleImage->save();
                    }

                    // Aggiorna l'imageUrl principale del veicolo se è la prima foto
                    if ($key === array_key_first($request->file('photos'))) {
                        $vehicle->imageUrl = $path;
                        $vehicle->save();
                    }
                }
            }

            $updatedVehicle = Vehicle::with([
                'damages',
                'make:id,name,icon',
                'model',
                'images' // Carica anche le immagini
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

            // Recupera le immagini associate
            $images = VehicleImage::where('vehicle_id', $id)->get();

            // Elimina i file fisici delle immagini
            foreach ($images as $image) {
                $path = str_replace('/storage/', '', $image->url);
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            // La cancellazione a cascata gestirà l'eliminazione dei record delle immagini
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

    public function uploadPhoto(Request $request)
    {
        try {
            $request->validate([
                'photo' => 'required|image|max:5048', // 5MB max
                'index' => 'required|integer|min:0|max:2',
                'vehicle_id' => 'nullable|exists:vehicles,id'
            ]);

            $vehicleId = $request->vehicle_id;
            $index = $request->index;

            // Salva l'immagine
            $path = $request->file('photo')->store('vehicle_photos', 'public');
            $imageUrl = '/storage/' . $path;

            // Se abbiamo un vehicle_id, collega la foto ad esso
            if ($vehicleId) {
                $vehicle = Vehicle::findOrFail($vehicleId);

                // Controlla se esiste già un'immagine in questa posizione
                $existingImage = VehicleImage::where('vehicle_id', $vehicleId)
                    ->where('position', $index)
                    ->first();

                if ($existingImage) {
                    // Elimina il vecchio file se esiste
                    $oldPath = str_replace('/storage/', '', $existingImage->url);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }

                    // Aggiorna il record esistente
                    $existingImage->url = $imageUrl;
                    $existingImage->save();
                    $photo_id = $existingImage->id;
                } else {
                    // Crea un nuovo record immagine
                    $image = new VehicleImage();
                    $image->vehicle_id = $vehicleId;
                    $image->url = $imageUrl;
                    $image->position = $index;
                    $image->save();
                    $photo_id = $image->id;
                }

                // Aggiorna l'imageUrl del veicolo se è la prima immagine (posizione 0)
                if ($index == 0) {
                    $vehicle->imageUrl = $path;
                    $vehicle->save();
                }

                return response()->json([
                    'success' => true,
                    'photo_id' => $photo_id,
                    'url' => $imageUrl
                ]);
            } else {
                // Restituisci solo il percorso per un uso temporaneo
                return response()->json([
                    'success' => true,
                    'url' => $imageUrl
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Errore nel caricamento dell\'immagine: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadMultiplePhotos(Request $request)
    {
        try {
            $request->validate([
                'vehicle_id' => 'required|exists:vehicles,id',
                'photos.*' => 'required|image|max:5048', // 5MB max
                'indices.*' => 'required|integer|min:0|max:2',
            ]);

            $vehicleId = $request->vehicle_id;
            $vehicle = Vehicle::findOrFail($vehicleId);

            // Elabora ogni foto
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $key => $photo) {
                    $index = $request->indices[$key];

                    // Salva l'immagine
                    $path = $photo->store('vehicle_photos', 'public');
                    $imageUrl = '/storage/' . $path;

                    // Controlla se esiste già un'immagine in questa posizione
                    $existingImage = VehicleImage::where('vehicle_id', $vehicleId)
                        ->where('position', $index)
                        ->first();

                    if ($existingImage) {
                        // Elimina il vecchio file se esiste
                        $oldPath = str_replace('/storage/', '', $existingImage->url);
                        if (Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }

                        // Aggiorna il record esistente
                        $existingImage->url = $imageUrl;
                        $existingImage->save();
                    } else {
                        // Crea un nuovo record
                        $image = new VehicleImage();
                        $image->vehicle_id = $vehicleId;
                        $image->url = $imageUrl;
                        $image->position = $index;
                        $image->save();
                    }

                    // Aggiorna l'imageUrl del veicolo se è la prima immagine (posizione 0)
                    if ($index == 0) {
                        $vehicle->imageUrl = $path;
                        $vehicle->save();
                    }
                }
            }

            // Ricarica il veicolo con le immagini
            $vehicle = Vehicle::with('images')->find($vehicleId);

            return response()->json([
                'success' => true,
                'vehicle' => $vehicle
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Errore nel caricamento delle immagini: ' . $e->getMessage()
            ], 500);
        }
    }
}
