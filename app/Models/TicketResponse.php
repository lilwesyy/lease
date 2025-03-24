<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_name',
        'text',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}