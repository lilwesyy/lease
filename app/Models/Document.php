<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'type',
        'size',
        'document_type', // 'identity' or 'card'
        'customer_id',
    ];

    /**
     * Get the customer that owns the document.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}