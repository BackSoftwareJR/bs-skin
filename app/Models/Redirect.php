<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}