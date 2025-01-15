<?php

namespace App\Http\Controllers;

use App\Models\Veicolo;
use Illuminate\Http\Request;

class VeicoloController extends Controller
{
    // Mostra la landing page con la lista dei veicoli
    public function index()
    {
        $veicoli = Veicolo::all(); // Recupera tutti i veicoli
        return view('welcome', compact('veicoli'));
    }

    // Mostra il dettaglio di un veicolo
    public function show($id)
    {
        $veicolo = Veicolo::findOrFail($id);
        return view('veicoli.show', compact('veicolo'));
    }
}
