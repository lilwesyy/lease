<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Damage extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id', 'damagePosition', 'damagePositionLabel', 'damageSeverity',
        'damageSeverityLabel', 'damageType', 'damageTypeLabel', 'damageDescription'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
