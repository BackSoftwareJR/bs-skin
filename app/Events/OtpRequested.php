<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\OtpCode;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OtpRequested
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public OtpCode $otpCode,
        public string $plainCode
    ) {}
}