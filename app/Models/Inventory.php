<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'product_variant_id',
        'quantity',
        'threshold_low',
        'threshold_critical',
        'allow_backorder',
        'location',
        'last_movement_at',
    ];

    protected function casts(): array
    {
        return [
            'allow_backorder' => 'boolean',
            'last_movement_at' => 'datetime',
        ];
    }

    // Relations
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    // Scopes
    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'threshold_low');
    }

    public function scopeCriticalStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'threshold_critical');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0)
            ->where('allow_backorder', false);
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('quantity', '>', 0)
                ->orWhere('allow_backorder', true);
        });
    }

    // Accessors
    public function getIsLowAttribute(): bool
    {
        return $this->quantity <= $this->threshold_low;
    }

    public function getIsCriticalAttribute(): bool
    {
        return $this->quantity <= $this->threshold_critical;
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return $this->quantity <= 0 && !$this->allow_backorder;
    }

    // Methods
    public function decrementSafe(int $quantity): bool
    {
        return DB::transaction(function () use ($quantity) {
            // Lock per evitare race conditions
            $inventory = static::lockForUpdate()->find($this->id);
            
            if (!$inventory) {
                return false;
            }

            if ($inventory->quantity < $quantity && !$inventory->allow_backorder) {
                return false;
            }

            $inventory->update([
                'quantity' => $inventory->quantity - $quantity,
                'last_movement_at' => now(),
            ]);

            return true;
        });
    }

    public function addStock(int $quantity): void
    {
        $this->update([
            'quantity' => $this->quantity + $quantity,
            'last_movement_at' => now(),
        ]);
    }
}