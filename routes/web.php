<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\VehiclesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index']);

Route::get('/vehicles/list', [VehiclesController::class, 'list'])->name('vehicles.list');
Route::get('/vehicles/{id}', [VehiclesController::class, 'show'])->name('vehicles.show');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/vehicles', [VehiclesController::class, 'adminIndex'])->name('vehicles.adminIndex');
    Route::post('/vehicles/store', [VehiclesController::class, 'store'])->name('vehicles.store');
    Route::delete('/vehicles/delete/{id}', [VehiclesController::class, 'delete'])->name('vehicles.delete');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/vehicles/{id}/edit', [VehiclesController::class, 'edit'])->name('vehicles.edit');
    Route::post('/vehicles/{id}', [VehiclesController::class, 'update'])->name('vehicles.update');
});

require __DIR__.'/auth.php';
