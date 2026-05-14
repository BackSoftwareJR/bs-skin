<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'config_json' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}