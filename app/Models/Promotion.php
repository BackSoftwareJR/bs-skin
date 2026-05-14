<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'rules_json' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        return $query->where(function ($query) {
            $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
        })->where(function ($query) {
            $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
        });
    }
}