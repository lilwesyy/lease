<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customers = [
            [
                'firstName' => 'John', 'lastName' => 'Doe', 'address' => 'Strada Altessano, 96/B, 10151 Torino TO',
                'email' => 'john.doe@example.com', 'phone' => '123-456-7890', 'licenseNumber' => 'D1234567',
                'licenseValidUntil' => '2032-10-24', 'nationalID' => 'N1234567', 'identityCardValidUntil' => '2054-10-19',
                'birthDate' => '1990-05-12', 'cardNumber' => '1234567890123456', 'expirationDate' => '12/25',
                'cvv' => '123', 'cardHolder' => 'John Doe', 'language' => 'en', 'status' => 'Active',
            ],
            [
                'firstName' => 'Jane', 'lastName' => 'Smith', 'address' => 'Via Bologna, 32, 10152 Torino TO',
                'email' => 'jane.smith@example.com', 'phone' => '987-654-3210', 'licenseNumber' => 'S7654321',
                'licenseValidUntil' => '2032-10-24', 'nationalID' => 'N7654321', 'identityCardValidUntil' => '2054-10-19',
                'birthDate' => '1985-02-20', 'cardNumber' => '2345678901234567', 'expirationDate' => '11/24',
                'cvv' => '234', 'cardHolder' => 'Jane Smith', 'language' => 'it', 'status' => 'Inactive',
            ],
            [
                'firstName' => 'Michael', 'lastName' => 'Johnson', 'address' => 'Corso Tortona, 4/A, 10153 Torino TO',
                'email' => 'michael.johnson@example.com', 'phone' => '456-789-1234', 'licenseNumber' => 'J1234567',
                'licenseValidUntil' => '2032-10-24', 'nationalID' => 'N1234568', 'identityCardValidUntil' => '2054-10-19',
                'birthDate' => '1992-08-10', 'cardNumber' => '3456789012345678', 'expirationDate' => '10/24',
                'cvv' => '345', 'cardHolder' => 'Michael Johnson', 'language' => 'en', 'status' => 'Active',
            ],
            [
                'firstName' => 'Emily', 'lastName' => 'Davis', 'address' => 'Str. del Pascolo, 61, 10156 Torino TO',
                'email' => 'emily.davis@example.com', 'phone' => '321-654-9870', 'licenseNumber' => 'D7654321',
                'licenseValidUntil' => '2032-10-24', 'nationalID' => 'N7654322', 'identityCardValidUntil' => '2054-10-19',
                'birthDate' => '1988-03-25', 'cardNumber' => '4567 8901 2345 6789', 'expirationDate' => '09/23',
                'cvv' => '456', 'cardHolder' => 'Emily Davis', 'language' => 'ro', 'status' => 'Inactive',
            ]
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
