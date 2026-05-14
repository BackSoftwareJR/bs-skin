<?php

namespace App\Models;

use App\Enums\NewsletterStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'name', 'status', 'source', 'locale', 'external_provider',
        'external_subscriber_id', 'synced_at', 'subscribed_at', 'unsubscribed_at',
        'double_opt_in_token', 'double_opt_in_expires_at', 'ip_address'
    ];

    protected function casts(): array
    {
        return [
            'status' => NewsletterStatus::class,
            'synced_at' => 'datetime',
            'subscribed_at' => 'datetime', 
            'unsubscribed_at' => 'datetime',
            'double_opt_in_expires_at' => 'datetime',
        ];
    }

    public function scopeSubscribed($query)
    {
        return $query->where('status', NewsletterStatus::SUBSCRIBED);
    }

    public function scopePending($query)
    {
        return $query->where('status', NewsletterStatus::PENDING);
    }

    public function scopeNeedsSync($query)
    {
        return $query->whereNull('synced_at')
            ->where('status', NewsletterStatus::SUBSCRIBED);
    }

    public function generateDoubleOptInToken(): string
    {
        $token = Str::random(60);
        
        $this->update([
            'double_opt_in_token' => $token,
            'double_opt_in_expires_at' => now()->addHours(48),
        ]);

        return $token;
    }
}