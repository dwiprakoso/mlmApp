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
        'is_active',
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

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
}
