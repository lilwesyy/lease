<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome');  // Assicurati che questa vista sia quella che include il tuo file Vue
})->where('any', '.*');  // Permette di gestire tutte le rotte, ad esempio /dashboard, /settings, ecc.
