<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstName', 'lastName', 'address', 'email', 'phone', 'licenseNumber',
        'licenseValidUntil', 'nationalID', 'identityCardValidUntil', 'birthDate',
        'cardNumber', 'expirationDate', 'cvv', 'cardHolder', 'language', 'status',
    ];
}
