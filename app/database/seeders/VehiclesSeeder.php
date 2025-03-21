<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\Damage;

class VehiclesSeeder extends Seeder
{
    public function run()
    {
        // Dati per i veicoli
        $vehicles = [
            [
                'make' => 'Toyota',
                'model' => 'Corolla',
                'year' => 2020,
                'seats' => 5,
                'transmission' => 'automatic',
                'color' => 'white',
                'plateNumber' => 'ABC1234',
                'bodyType' => 'sedan',
                'location' => 'Torino',
                'dailyPrice' => 50,
                'status' => 'available',
                'odometer' => 120000,
                'fuel_type' => 'petrol',
                'km_per_day' => 100,
                'extra_km_price' => 0.5,
                'basic_daily_price' => 50,
                'franchise' => 500,
                'imageUrl' => 'https://media.ed.edmunds-media.com/toyota/corolla-hatchback/2020/oem/2020_toyota_corolla-hatchback_4dr-hatchback_nightshade-edition_fq_oem_1_1600.jpg',
                'damages' => [
                    [
                        'damagePosition' => 'front_bumper',
                        'damagePositionLabel' => 'Front Bumper',
                        'damageSeverity' => 'severe',
                        'damageSeverityLabel' => 'Severe',
                        'damageType' => 'dent',
                        'damageTypeLabel' => 'Dent',
                        'damageDescription' => 'Severe dent and scratches'
                    ]
                ]
            ],
            [
                'make' => 'Honda',
                'model' => 'Civic',
                'year' => 2019,
                'seats' => 5,
                'transmission' => 'manual',
                'color' => 'black',
                'plateNumber' => 'XYZ5678',
                'bodyType' => 'sedan',
                'location' => 'Milano',
                'dailyPrice' => 45,
                'status' => 'unavailable',
                'odometer' => 90000,
                'fuel_type' => 'petrol',
                'km_per_day' => 80,
                'extra_km_price' => 0.4,
                'basic_daily_price' => 45,
                'franchise' => 400,
                'imageUrl' => 'https://www.cnet.com/a/img/resize/df2edfd143035974957c05a677468f2fbd767569/hub/2019/05/20/95e44f16-4eda-427d-aae4-1df5309547f6/2019-honda-civic-touring-sedan-1.jpg?auto=webp&width=768',
                'damages' => [
                    [
                        'damagePosition' => 'rear_bumper',
                        'damagePositionLabel' => 'Rear Bumper',
                        'damageSeverity' => 'moderate',
                        'damageSeverityLabel' => 'Moderate',
                        'damageType' => 'scratch',
                        'damageTypeLabel' => 'Scratch',
                        'damageDescription' => 'Moderate scratches'
                    ]
                ]
            ],
            [
                'make' => 'Ford',
                'model' => 'Focus',
                'year' => 2018,
                'seats' => 5,
                'transmission' => 'automatic',
                'color' => 'blue',
                'plateNumber' => 'LMN8765',
                'bodyType' => 'sedan',
                'location' => 'Torino',
                'dailyPrice' => 40,
                'status' => 'maintenance',
                'odometer' => 100000,
                'fuel_type' => 'diesel',
                'km_per_day' => 120,
                'extra_km_price' => 0.6,
                'basic_daily_price' => 40,
                'franchise' => 450,
                'imageUrl' => 'https://rcs.cdn.publieditor.it/w640/M1273_01.jpg',
                'damages' => [
                    [
                        'damagePosition' => 'rear_bumper',
                        'damagePositionLabel' => 'Rear Bumper',
                        'damageSeverity' => 'moderate',
                        'damageSeverityLabel' => 'Moderate',
                        'damageType' => 'scratch',
                        'damageTypeLabel' => 'Scratch',
                        'damageDescription' => 'Moderate scratches'
                    ]
                ]
            ]
        ];

        // Inserisci i veicoli
        foreach ($vehicles as $vehicleData) {
            // Crea il veicolo
            $vehicle = Vehicle::create([
                'make' => $vehicleData['make'],
                'model' => $vehicleData['model'],
                'year' => $vehicleData['year'],
                'seats' => $vehicleData['seats'],
                'transmission' => $vehicleData['transmission'],
                'color' => $vehicleData['color'],
                'plateNumber' => $vehicleData['plateNumber'],
                'bodyType' => $vehicleData['bodyType'],
                'location' => $vehicleData['location'],
                'dailyPrice' => $vehicleData['dailyPrice'],
                'status' => $vehicleData['status'],
                'odometer' => $vehicleData['odometer'],
                'fuel_type' => $vehicleData['fuel_type'],
                'km_per_day' => $vehicleData['km_per_day'],
                'extra_km_price' => $vehicleData['extra_km_price'],
                'basic_daily_price' => $vehicleData['basic_daily_price'],
                'franchise' => $vehicleData['franchise'],
                'imageUrl' => $vehicleData['imageUrl'],
            ]);

            // Aggiungi danni associati al veicolo
            foreach ($vehicleData['damages'] as $damageData) {
                $vehicle->damages()->create($damageData);
            }
        }
    }
}
