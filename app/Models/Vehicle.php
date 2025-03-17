<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo', 'make', 'model', 'year', 'seats', 'transmission', 'color', 'plateNumber',
        'bodyType', 'location', 'dailyPrice', 'status', 'odometer', 'fuel_type', 'km_per_day',
        'extra_km_price', 'basic_daily_price', 'franchise', 'imageUrl'
    ];

    public function damages()
    {
        return $this->hasMany(Damage::class);
    }
}
