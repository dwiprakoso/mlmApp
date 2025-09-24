<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration',
        'price',
        'type',
        'is_active',
        'presentase',
        'profit',
        'total_profit'
    ];

    // Cast attributes to appropriate types
    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    // Default values
    protected $attributes = [
        'is_active' => true,
    ];
}
