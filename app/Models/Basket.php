<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use HasFactory;

    protected $fillable = [
        'items',
        'total_price',
        'email',
        'is_draft',
        'is_approved',
        'is_delivered'
    ];

    protected $casts = [
        'items' => 'array',
        'total_price' => 'decimal:2',
        'is_draft' => 'integer',
        'is_approved' => 'integer',
        'is_delivered' => 'integer'
    ];
} 