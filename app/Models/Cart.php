<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'session_token', 'currency', 'subtotal', 'discount_total',
        'tax_total', 'total', 'coupon_id', 'notes', 'expires_at'
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_total' => 'decimal:2', 
            'tax_total' => 'decimal:2',
            'total' => 'decimal:2',
            'expires_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function scopeActive($query)
    {
        return $query->where('updated_at', '>', now()->subDays(7));
    }

    public function scopeBySession($query, string $token)
    {
        return $query->where('session_token', $token);
    }

    public function scopeByCustomer($query, int $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function recalculate(): void
    {
        $subtotal = $this->items->sum('subtotal_snapshot');
        $this->update(['subtotal' => $subtotal, 'total' => $subtotal]);
    }
}