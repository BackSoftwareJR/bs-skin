<?php

namespace App\Livewire\Public\Account;

use Livewire\Component;
use App\Models\Customer;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpLoginForm extends Component
{
    public string $step = 'email'; // email | otp | success
    public string $email = '';
    public bool $acceptTerms = false;
    public string $otpCode = '';
    public bool $canResend = false;
    public int $resendCooldown = 60;
    public ?string $errorMessage = null;
    public int $attempts = 0;

    protected function rules(): array
    {
        return match($this->step) {
            'email' => [
                'email' => 'required|email:rfc',
                'acceptTerms' => 'accepted',
            ],
            'otp' => [
                'otpCode' => 'required|string|size:6',
            ],
            default => [],
        };
    }

    protected $messages = [
        'email.required' => 'L\'email è obbligatoria.',
        'email.email' => 'Inserisci un indirizzo email valido.',
        'acceptTerms.accepted' => 'Devi accettare i termini di servizio.',
        'otpCode.required' => 'Inserisci il codice ricevuto.',
        'otpCode.size' => 'Il codice deve essere di 6 cifre.',
    ];

    public function mount(): mixed
    {
        if (auth('customer')->check()) {
            return redirect('/account');
        }

        return null;
    }

    public function requestOtp(): void
    {
        $this->validate();
        $this->errorMessage = null;

        try {
            // TODO: delegare a RequestOtpAction quando disponibile
            // Per ora implemento la logica inline
            
            // Genera OTP
            $otpPlain = random_int(100000, 999999);
            $otpHash = hash('sha256', $otpPlain);

            // Trova o crea customer
            $customer = Customer::where('email', $this->email)->first();
            if (!$customer) {
                $customer = Customer::create([
                    'email' => $this->email,
                    'email_verified_at' => null,
                ]);
            }

            // Cancella OTP precedenti per questo customer
            OtpCode::where('customer_id', $customer->id)->delete();

            // Crea nuovo OTP
            OtpCode::create([
                'customer_id' => $customer->id,
                'code_hash' => $otpHash,
                'expires_at' => now()->addMinutes(10),
                'attempts' => 0,
            ]);

            // Salva email in session per il passo successivo
            session(['otp_email' => $this->email]);

            // TODO: invia email OTP (per ora log)
            logger('OTP per ' . $this->email . ': ' . $otpPlain);

            // Avanza al passo OTP
            $this->step = 'otp';
            $this->startResendCooldown();

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Codice inviato! Controlla la tua email.'
            ]);

        } catch (\Exception $e) {
            $this->errorMessage = 'Si è verificato un errore. Riprova.';
            logger('Errore OTP request: ' . $e->getMessage());
        }
    }

    public function verifyOtp(): void
    {
        $this->validate();
        $this->errorMessage = null;

        try {
            $customer = Customer::where('email', session('otp_email'))->first();
            
            if (!$customer) {
                $this->errorMessage = 'Sessione scaduta. Richiedi un nuovo codice.';
                $this->step = 'email';
                return;
            }

            $otpRecord = OtpCode::where('customer_id', $customer->id)
                ->where('expires_at', '>', now())
                ->first();

            if (!$otpRecord) {
                $this->errorMessage = 'Codice scaduto. Richiedi un nuovo codice.';
                $this->resetToEmail();
                return;
            }

            // Incrementa tentativi
            $otpRecord->increment('attempts');

            // Verifica codice
            $otpHash = hash('sha256', $this->otpCode);
            
            if ($otpRecord->code_hash !== $otpHash) {
                $this->attempts++;
                
                if ($otpRecord->attempts >= 3) {
                    $otpRecord->delete();
                    $this->errorMessage = 'Troppi tentativi. Richiedi un nuovo codice.';
                    $this->resetToEmail();
                } else {
                    $this->errorMessage = 'Codice non valido. Riprova.';
                }
                return;
            }

            // Codice corretto: login
            $otpRecord->delete();
            
            // Marca email come verificata se non lo era
            if (!$customer->email_verified_at) {
                $customer->update(['email_verified_at' => now()]);
            }

            // Login
            auth('customer')->login($customer);
            
            $this->step = 'success';
            session()->forget('otp_email');

            $this->dispatch('customer-logged-in');
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Accesso effettuato con successo!'
            ]);

            // Redirect dopo breve pausa
            $this->dispatch('redirect-after-delay', url: '/account')->delay(1500);

        } catch (\Exception $e) {
            $this->errorMessage = 'Si è verificato un errore durante la verifica.';
            logger('Errore OTP verify: ' . $e->getMessage());
        }
    }

    public function resendOtp(): void
    {
        if (!$this->canResend) {
            return;
        }

        // Reset email step e richiedi nuovo codice
        $this->step = 'email';
        $this->otpCode = '';
        $this->errorMessage = null;
        $this->attempts = 0;
        
        // Auto-trigger request se email è già stata inserita
        if ($this->email && session('otp_email') === $this->email) {
            $this->requestOtp();
        }
    }

    public function changeEmail(): void
    {
        $this->resetToEmail();
    }

    public function updatedOtpCode(): void
    {
        // Auto-submit quando 6 cifre sono inserite
        if (strlen($this->otpCode) === 6) {
            $this->verifyOtp();
        }
    }

    protected function resetToEmail(): void
    {
        $this->step = 'email';
        $this->otpCode = '';
        $this->errorMessage = null;
        $this->attempts = 0;
        session()->forget('otp_email');
    }

    protected function startResendCooldown(): void
    {
        $this->canResend = false;
        $this->resendCooldown = 60;
        
        // Countdown ogni secondo (simulato con polling)
        for ($i = 60; $i > 0; $i--) {
            $this->dispatch('update-cooldown', seconds: $i)->delay($i === 60 ? 0 : (60 - $i) * 1000);
        }
        
        $this->dispatch('enable-resend')->delay(60000);
    }

    public function updateCooldown(int $seconds): void
    {
        $this->resendCooldown = $seconds;
    }

    public function enableResend(): void
    {
        $this->canResend = true;
        $this->resendCooldown = 0;
    }

    public function render()
    {
        return view('livewire.public.account.otp-login-form');
    }
}