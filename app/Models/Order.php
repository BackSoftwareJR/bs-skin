<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Order extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'order_number',
        'customer_id',
        'customer_email',
        'customer_name',
        'shipping_address_json',
        'billing_address_json',
        'currency',
        'subtotal',
        'discount_total',
        'tax_total',
        'shipping_total',
        'total',
        'status',
        'payment_status',
        'payment_provider',
        'payment_method',
        'payment_external_id',
        'payment_metadata',
        'shipping_provider',
        'shipping_method',
        'coupon_id',
        'notes_customer',
        'notes_admin',
        'ip_address',
        'user_agent',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'invoice_number',
        'invoice_external_id',
        'invoice_provider',
        'invoice_pdf_path',
        'invoice_xml_path',
        'invoice_status',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_total' => 'decimal:2',
            'tax_total' => 'decimal:2',
            'shipping_total' => 'decimal:2',
            'total' => 'decimal:2',
            'shipping_address_json' => 'array',
            'billing_address_json' => 'array',
            'payment_metadata' => 'array',
            'paid_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'status' => OrderStatus::class,
            'payment_status' => PaymentStatus::class,
        ];
    }

    // Spatie Activity Log
    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logOnly(['status', 'payment_status', 'total'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relations
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function couponRedemption(): HasOne
    {
        return $this->hasOne(CouponRedemption::class);
    }

    // Scopes
    public function scopeByStatus($query, OrderStatus $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', OrderStatus::PENDING);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', PaymentStatus::CAPTURED);
    }

    public function scopeRecentFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeNeedsInvoice($query)
    {
        return $query->where('invoice_status', 'none')
            ->where('payment_status', PaymentStatus::CAPTURED);
    }

    // State machine methods
    public function canTransitionTo(OrderStatus $status): bool
    {
        return $this->status->canTransitionTo($status);
    }

    public function transitionTo(OrderStatus $status, ?string $reason = null, ?User $user = null): bool
    {
        if (!$this->canTransitionTo($status)) {
            return false;
        }

        $previousStatus = $this->status;
        
        $this->update(['status' => $status]);

        // Create status history record
        $this->statusHistory()->create([
            'from_status' => $previousStatus->value,
            'to_status' => $status->value,
            'reason' => $reason,
            'performed_by_user_id' => $user?->id,
        ]);

        return true;
    }
}