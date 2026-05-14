<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $fillable = [
        'email',
        'code_hash',
        'attempts',
        'expires_at',
        'used_at',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    public $timestamps = false;
    protected $dates = ['created_at'];

    // Scopes
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now())
            ->whereNull('used_at')
            ->where('attempts', '<', 5);
    }

    public function scopeForEmail($query, string $email)
    {
        return $query->where('email', $email);
    }

    // Methods
    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    public function isUsed(): bool
    {
        return !is_null($this->used_at);
    }

    public function canAttempt(): bool
    {
        return $this->attempts < 5 && !$this->isExpired() && !$this->isUsed();
    }
}