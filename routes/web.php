<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\BookingController;

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');

// Authentication
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
// Route::post('/user', [AuthController::class, 'getUser']);

// Booking
Route::post('/booking', [BookingController::class, 'getBookings']);
Route::post('/booking/create', [BookingController::class, 'createBooking']);

// Location
Route::post('/location', [LocationController::class, 'getLocation']);
Route::post('/location/create', [LocationController::class, 'createLocation']);


// Vehicles
Route::post('/vehicles', [VehicleController::class, 'getVehicles']);
Route::post('/vehicle-makes', [VehicleController::class, 'getVehicleMakes']);

// Customers
Route::post('/customer', [CustomerController::class, 'getCustomers']);
Route::post('/customer/create', [CustomerController::class, 'createCustomer']);
Route::delete('/customer/delete/{id}', [CustomerController::class, 'deleteCustomer']);
Route::put('/customer/edit/{id}', [CustomerController::class, 'editCustomer']);

