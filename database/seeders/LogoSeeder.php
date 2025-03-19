<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicleMakes = [
            'Alfa Romeo' => 'https://www.carlogos.org/logo/Alfa-Romeo-logo-2015-640x550.jpg',
            'Audi' => 'https://www.carlogos.org/car-logos/audi-logo-2009-640.png',
            'BMW' => 'https://www.carlogos.org/car-logos/bmw-logo-1997-640.png',
            'Chevrolet' => 'https://www.carlogos.org/logo/Chevrolet-logo-2011-640x311.jpg',
            'Fiat' => 'https://www.carlogos.org/logo/Fiat-logo-2006-640x550.jpg',
            'Ford' => 'https://www.carlogos.org/car-logos/ford-logo-2017-640.png',
            'Honda' => 'https://www.carlogos.org/car-logos/honda-logo-2000-full-640.png',
            'Hyundai' => 'https://www.carlogos.org/car-logos/hyundai-logo-2011-640.png',
            'Jaguar' => 'https://www.carlogos.org/car-logos/jaguar-logo-2021-640.png',
            'Kia' => 'https://www.carlogos.org/logo/Kia-logo-640x321.jpg',
            'Mercedes-Benz' => 'https://www.carlogos.org/logo/Mercedes-Benz-logo-2009-640x452.jpg',
            'Nissan' => 'https://www.carlogos.org/car-logos/nissan-logo-2012-1300x1100-show.png',
            'Porsche' => 'https://www.carlogos.org/car-logos/porsche-logo-2014-full-640.png',
            'Subaru' => 'https://www.carlogos.org/car-logos/subaru-logo-2019-640.png',
            'Tesla' => 'https://www.carlogos.org/car-logos/tesla-logo-2007-full-640.png',
            'Lancia' => 'https://www.carlogos.org/logo/Lancia-logo-2007-640x550.jpg',
            'Land Rover' => 'https://www.carlogos.org/logo/Land-Rover-logo-2011-640x335.jpg',
            'Mazda' => 'https://www.carlogos.org/car-logos/mazda-logo-2018-vertical-640.png',
            'Toyota' => 'https://www.carlogos.org/car-logos/toyota-logo-2005-640.png',
            'Volkswagen' => 'https://www.carlogos.org/logo/Volkswagen-logo-2015-640x550.jpg',
            'Volvo' => 'https://www.carlogos.org/logo/Volvo-logo-2014-640x550.jpg',
        ];

        foreach ($vehicleMakes as $makeName => $iconFileName) {
            DB::table('vehicle_makes')
                ->where('name', $makeName)
                ->update(['icon' => $iconFileName]);
        }
    }
}
