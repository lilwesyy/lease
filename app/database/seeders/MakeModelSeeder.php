<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VehicleMake as Make;
use App\Models\VehicleModel as ModelVehicle;

class MakeModelSeeder extends Seeder
{
    public function run()
    {
        $carData = [
            'Toyota' => ['Corolla', 'Camry', 'RAV4', 'Highlander', 'Prius', 'Yaris', 'Aygo'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'Pilot', 'Fit', 'Jazz'],
            'Ford' => ['Fiesta', 'Focus', 'Mustang', 'Explorer', 'F-150', 'Kuga', 'Puma'],
            'BMW' => ['Series 1', 'Series 2', 'Series 3', 'Series 4', 'Series 5', 'Series 6', 'Series 7', 'X1', 'X2', 'X3', 'X4', 'X5', 'X6', 'X7', 'i3', 'i8'],
            'Mercedes-Benz' => ['A-Class', 'B-Class', 'C-Class', 'E-Class', 'S-Class', 'CLA', 'CLS', 'GLA', 'GLB', 'GLC', 'GLE', 'GLS', 'G-Class'],
            'Audi' => ['A1', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'Q2', 'Q3', 'Q5', 'Q7', 'Q8', 'TT', 'R8'],
            'Volkswagen' => ['Golf', 'Passat', 'Tiguan', 'Polo', 'Jetta', 'T-Roc', 'T-Cross', 'Arteon', 'Touareg'],
            'Nissan' => ['Micra', 'Altima', 'Rogue', 'Pathfinder', 'GT-R', 'Qashqai', 'Juke', 'X-Trail'],
            'Chevrolet' => ['Spark', 'Malibu', 'Impala', 'Silverado', 'Tahoe', 'Camaro', 'Equinox', 'Traverse'],
            'Tesla' => ['Model S', 'Model 3', 'Model X', 'Model Y', 'Cybertruck'],
            'Hyundai' => ['i10', 'i20', 'i30', 'Elantra', 'Sonata', 'Tucson', 'Santa Fe', 'Kona'],
            'Kia' => ['Picanto', 'Rio', 'Ceed', 'Soul', 'Sportage', 'Sorento', 'Stinger'],
            'Mazda' => ['Mazda2', 'Mazda3', 'Mazda6', 'CX-3', 'CX-5', 'CX-30', 'MX-5'],
            'Subaru' => ['Impreza', 'Legacy', 'Outback', 'Forester', 'Crosstrek', 'XV'],
            'Volvo' => ['V40', 'V60', 'V90', 'S60', 'S90', 'XC40', 'XC60', 'XC90'],
            'Jaguar' => ['XE', 'XF', 'XJ', 'F-Pace', 'E-Pace', 'I-Pace'],
            'Land Rover' => ['Range Rover', 'Range Rover Sport', 'Range Rover Velar', 'Range Rover Evoque', 'Discovery', 'Discovery Sport', 'Defender'],
            'Porsche' => ['911', 'Cayenne', 'Macan', 'Panamera', 'Taycan', 'Boxster', 'Cayman'],
            'Fiat' => ['500', 'Panda', 'Tipo', 'Punto', '500X', '500L', 'Ducato'],
            'Alfa Romeo' => ['Giulietta', 'Giulia', 'Stelvio', 'MiTo'],
            'Lancia' => ['Ypsilon', 'Delta', 'Thema']
        ];

        foreach ($carData as $make => $models) {
            $makeEntry = Make::create(['name' => $make, 'icon' => null]);

            foreach ($models as $model) {
                ModelVehicle::create([
                    'name' => $model,
                    'make_id' => $makeEntry->id
                ]);
            }
        }
    }
}
