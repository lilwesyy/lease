@extends('layouts.app') @section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <img
            src="{{ asset($veicolo->immagine) }}"
            alt="{{ $veicolo->modello }}"
            class="w-full h-64 object-cover"
        />
        <div class="p-6">
            <h1 class="text-3xl font-semibold">{{ $veicolo->modello }}</h1>
            <p class="text-gray-600 mt-2">{{ $veicolo->descrizione }}</p>
            <p class="text-red-500 text-lg font-bold mt-4">
                €{{ $veicolo->prezzo_giornaliero }}/giorno
            </p>
            <a
                href="#"
                class="mt-4 inline-block px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-800"
            >
                Prenota Ora
            </a>
        </div>
    </div>
</div>
@endsection
