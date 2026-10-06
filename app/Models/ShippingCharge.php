<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'key',
        'charge',
        'description',
        'status',
    ];

    protected $casts = [
        'charge' => 'decimal:2',
        'status' => 'boolean',
    ];
}
