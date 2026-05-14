<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Actions\Otp\RequestOtpAction;
use App\Actions\Otp\VerifyOtpAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\RequestOtpRequest;
use App\Http\Requests\Public\VerifyOtpRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function requestOtp(RequestOtpRequest $request): JsonResponse|RedirectResponse
    {
        $otpCode = app(RequestOtpAction::class)->execute(
            $request->email,
            $request->ip(),
            $request->header('User-Agent', '')
        );
        
        if ($request->expectsJson() || $request->hasHeader('X-Livewire')) {
            return response()->json([
                'success' => true,
                'message' => 'Codice OTP inviato alla tua email',
                'otp_id' => $otpCode->id, // Per debugging, non il codice
            ]);
        }
        
        return back()->with([
            'success' => 'Codice OTP inviato alla tua email',
            'show_otp_form' => true,
            'otp_email' => $request->email,
        ]);
    }
    
    public function verifyOtp(VerifyOtpRequest $request): JsonResponse|RedirectResponse
    {
        $customer = app(VerifyOtpAction::class)->execute(
            $request->email,
            $request->code,
            $request->ip()
        );
        
        if ($request->expectsJson() || $request->hasHeader('X-Livewire')) {
            return response()->json([
                'success' => true,
                'message' => 'Accesso effettuato con successo',
                'customer' => [
                    'id' => $customer->id,
                    'email' => $customer->email,
                ],
            ]);
        }
        
        return redirect()
            ->intended(route('public.account.dashboard'))
            ->with('success', 'Accesso effettuato con successo');
    }
    
    public function logout(Request $request): JsonResponse|RedirectResponse
    {
        session()->forget([
            'skintemple_customer_id',
            'skintemple_customer_email',
        ]);
        
        if ($request->expectsJson() || $request->hasHeader('X-Livewire')) {
            return response()->json([
                'success' => true,
                'message' => 'Logout effettuato con successo',
            ]);
        }
        
        return redirect()
            ->route('public.home')
            ->with('success', 'Logout effettuato con successo');
    }
}