<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Veicolo;

class VeicoloSeeder extends Seeder
{
    public function run()
    {
        Veicolo::create([
            'modello' => 'Fiat Panda',
            'descrizione' => 'Citycar economica e affidabile, perfetta per la città.',
            'prezzo_giornaliero' => 29.99,
            'immagine' => 'images/fiat_panda.jpg',
        ]);

        Veicolo::create([
            'modello' => 'Audi A3',
            'descrizione' => 'Berlina compatta, elegante e confortevole.',
            'prezzo_giornaliero' => 49.99,
            'immagine' => 'images/audi_a3.jpg',
        ]);

        Veicolo::create([
            'modello' => 'BMW X5',
            'descrizione' => 'SUV di lusso, potente e spazioso.',
            'prezzo_giornaliero' => 89.99,
            'immagine' => 'images/bmw_x5.jpg',
        ]);
    }
}
