<?php

namespace App\Models;

use App\Enums\CustomerAddressType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'type',
        'is_default',
        'full_name',
        'company',
        'vat_number',
        'sdi_code',
        'pec',
        'street',
        'civic',
        'postal_code',
        'city',
        'province',
        'country',
        'phone',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'type' => CustomerAddressType::class,
        ];
    }

    // Relations
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    // Scopes
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}