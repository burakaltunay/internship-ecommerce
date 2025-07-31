<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    protected $fillable = [
        'email',
        'code',
        'basket_id',
        'expires_at',
    ];

    protected $casts = [
        'is_used' => 'integer',
        'expires_at' => 'datetime',
    ];

    protected $attributes = [
        'is_used' => 0,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
