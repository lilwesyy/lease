<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    protected $table = 'app';
    
    protected $fillable = ['settings'];
    
    protected $casts = [
        'settings' => 'array',
    ];
}