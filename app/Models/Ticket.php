<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'description',
        'status',
        'user_id',
    ];

    public function responses()
    {
        return $this->hasMany(\App\Models\TicketResponse::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}