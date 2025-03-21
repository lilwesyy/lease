<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'make', 'model', 'year', 'seats', 'transmission', 'color', 'plateNumber',
        'bodyType', 'location_id', 'dailyPrice', 'status', 'odometer', 'fuel_type', 'km_per_day',
        'extra_km_price', 'basic_daily_price', 'franchise', 'imageUrl'
    ];

    public function damages()
    {
        return $this->hasMany(Damage::class);
    }

    public function make()
    {
        return $this->belongsTo(VehicleMake::class, 'make_id');
    }

    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    public function images()
    {
        return $this->hasMany(VehicleImage::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
