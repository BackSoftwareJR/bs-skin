<?php

declare(strict_types=1);

namespace App\Services\Maintenance;

use App\Models\Cart;
use App\Models\OtpCode;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class CleanupService
{
    public function cleanExpiredOtps(): int
    {
        return OtpCode::where('expires_at', '<', now())->delete();
    }
    
    public function cleanAbandonedCarts(int $days = 30): int
    {
        return Cart::where('updated_at', '<', now()->subDays($days))
            ->whereDoesntHave('customer') // Solo carrelli guest
            ->delete();
    }
    
    public function cleanOldActivityLogs(int $days = 90): int
    {
        if (!class_exists(Activity::class)) {
            return 0;
        }
        
        return Activity::where('created_at', '<', now()->subDays($days))->delete();
    }
    
    public function cleanExpiredSessions(): int
    {
        $sessionLifetime = config('session.lifetime', 120); // minuti
        
        return DB::table('sessions')
            ->where('last_activity', '<', now()->subMinutes($sessionLifetime)->timestamp)
            ->delete();
    }
    
    public function runAllCleanups(): array
    {
        return [
            'expired_otps' => $this->cleanExpiredOtps(),
            'abandoned_carts' => $this->cleanAbandonedCarts(),
            'old_activity_logs' => $this->cleanOldActivityLogs(),
            'expired_sessions' => $this->cleanExpiredSessions(),
        ];
    }
}