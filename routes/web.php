<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/user', [AuthController::class, 'getUser']);
Route::post('/vehicles', [VehicleController::class, 'getVehicles']); // Aggiungi questa linea

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/user', function (Request $request) {
//         return response()->json(['user' => $request->user()]);
//     });
// });
