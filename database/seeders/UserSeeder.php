<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        // User::create([
        //     'name' => 'Mirco Carp',
        //     'email' => 'mirco.carp@icloud.com',
        //     'email_verified_at' => now(),
        //     'password' => bcrypt('123456'),
        //     'remember_token' => Str::random(10),
        // ]);

        // Creazione degli utenti aggiuntivi dal JSON
        $users = [
            ['name' => 'John Smith', 'email' => 'john.smith@lease.com'],
            ['name' => 'Michael Green', 'email' => 'michael.green@lease.com'],
            ['name' => 'David White', 'email' => 'david.white@lease.com'],
            ['name' => 'Emma Black', 'email' => 'emma.black@lease.com',],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'email_verified_at' => now(),
                'password' => bcrypt('123456'),
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
