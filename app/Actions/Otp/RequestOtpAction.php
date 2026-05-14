<?php

declare(strict_types=1);

namespace App\Actions\Otp;

use App\Events\OtpRequested;
use App\Exceptions\OtpRateLimitException;
use App\Models\OtpCode;

class RequestOtpAction
{
    public function execute(string $email, string $ip, string $userAgent): OtpCode
    {
        $this->checkRateLimit($email);
        
        $code = $this->generateCode();
        $hashedCode = hash('sha256', $code);
        
        $otpCode = OtpCode::create([
            'email' => $email,
            'code_hash' => $hashedCode,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'expires_at' => now()->addMinutes(10),
            'attempts' => 0,
            'used_at' => null,
        ]);
        
        event(new OtpRequested($otpCode, $code));
        
        return $otpCode;
    }
    
    private function checkRateLimit(string $email): void
    {
        $recentCount = OtpCode::where('email', $email)
            ->where('created_at', '>', now()->subMinutes(15))
            ->count();
            
        if ($recentCount >= 3) {
            throw new OtpRateLimitException(15);
        }
    }
    
    private function generateCode(): string
    {
        return (string) random_int(100000, 999999);
    }
}