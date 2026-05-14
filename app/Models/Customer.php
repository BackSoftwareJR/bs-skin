<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'email',
        'name',
        'surname',
        'phone',
        'locale',
        'is_active',
        'marketing_consent',
        'marketing_consent_at',
        'last_login_at',
        'total_orders',
        'total_spent',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'marketing_consent' => 'boolean',
            'marketing_consent_at' => 'datetime',
            'last_login_at' => 'datetime',
            'total_spent' => 'decimal:2',
            'deleted_at' => 'datetime',
        ];
    }

    // Spatie Activity Log
    protected static $logAttributes = ['email', 'name', 'surname', 'is_active'];
    protected static $logName = 'customers';
    protected static $logOnlyDirty = true;

    // Relations
    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function defaultShippingAddress(): HasOne
    {
        return $this->hasOne(CustomerAddress::class)
            ->where('is_default', true)
            ->where('type', '!=', 'billing');
    }

    public function defaultBillingAddress(): HasOne
    {
        return $this->hasOne(CustomerAddress::class)
            ->where('is_default', true)
            ->where('type', '!=', 'shipping');
    }

    public function termsAcceptances(): HasMany
    {
        return $this->hasMany(TermsAcceptance::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    public function couponRedemptions(): HasMany
    {
        return $this->hasMany(CouponRedemption::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithMarketing($query)
    {
        return $query->where('marketing_consent', true);
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->name . ' ' . $this->surname;
    }
}