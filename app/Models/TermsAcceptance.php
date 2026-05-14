<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TermsAcceptance extends Model
{
    protected $fillable = [
        'customer_id',
        'document_version',
        'document_type',
        'accepted_at',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'accepted_at' => 'datetime',
        ];
    }

    public $timestamps = false; // Immutable record

    // Relations
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}