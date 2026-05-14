<?php

namespace App\Observers;

use App\Models\OtpCode;

class OtpCodeObserver
{
    public function creating(OtpCode $otpCode): void
    {
        // Lazy cleanup: rimuovi OTP scaduti per la stessa email
        // Probabilistico (20% delle volte) per evitare overhead
        if (rand(1, 100) <= 20) {
            OtpCode::where('email', $otpCode->email)
                ->where('expires_at', '<', now())
                ->delete();
        }
    }
}