<?php

declare(strict_types=1);

namespace App\Actions\Otp;

use App\Events\CustomerRegistered;
use App\Exceptions\OtpInvalidException;
use App\Models\Customer;
use App\Models\OtpCode;

class VerifyOtpAction
{
    public function execute(string $email, string $code, string $ip): Customer
    {
        $otp = OtpCode::where('email', $email)
            ->where('expires_at', '>', now())
            ->whereNull('used_at')
            ->where('attempts', '<', 5)
            ->first();
            
        if (!$otp) {
            throw new OtpInvalidException();
        }
        
        $hashedCode = hash('sha256', $code);
        
        if ($hashedCode !== $otp->code_hash) {
            $otp->increment('attempts');
            
            if ($otp->attempts >= 5) {
                $otp->update(['used_at' => now()]);
            }
            
            throw new OtpInvalidException();
        }
        
        $otp->update(['used_at' => now()]);
        
        $customer = Customer::where('email', $email)->first();
        $isNewCustomer = false;
        
        if (!$customer) {
            $customer = Customer::create([
                'email' => $email,
                'locale' => 'it',
                'is_active' => true,
                'marketing_consent' => false,
                'last_login_at' => now(),
                'total_orders' => 0,
                'total_spent' => 0,
            ]);
            $isNewCustomer = true;
        } else {
            $customer->update(['last_login_at' => now()]);
        }
        
        session([
            'skintemple_customer_id' => $customer->id,
            'skintemple_customer_email' => $customer->email,
        ]);
        
        if ($isNewCustomer) {
            event(new CustomerRegistered($customer));
        }
        
        return $customer;
    }
}