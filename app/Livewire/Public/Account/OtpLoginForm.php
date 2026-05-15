<?php

declare(strict_types=1);

namespace App\Livewire\Public\Account;

use App\Actions\Otp\RequestOtpAction;
use App\Actions\Otp\VerifyOtpAction;
use App\Exceptions\OtpInvalidException;
use App\Exceptions\OtpRateLimitException;
use Livewire\Component;

class OtpLoginForm extends Component
{
    public string $step = 'email'; // email | otp | success
    public string $email = '';
    public bool $acceptTerms = false;
    public string $otpCode = '';
    public bool $canResend = false;
    public int $resendCooldown = 60;
    public ?string $errorMessage = null;

    protected function rules(): array
    {
        return match ($this->step) {
            'email' => [
                'email'       => 'required|email:rfc',
                'acceptTerms' => 'accepted',
            ],
            'otp' => [
                'otpCode' => 'required|string|size:6',
            ],
            default => [],
        };
    }

    protected $messages = [
        'email.required'       => "L'email è obbligatoria.",
        'email.email'          => 'Inserisci un indirizzo email valido.',
        'acceptTerms.accepted' => 'Devi accettare i termini di servizio.',
        'otpCode.required'     => 'Inserisci il codice ricevuto.',
        'otpCode.size'         => 'Il codice deve essere di 6 cifre.',
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
            app(RequestOtpAction::class)->execute(
                $this->email,
                request()->ip() ?? '127.0.0.1',
                request()->header('User-Agent', ''),
            );

            session(['otp_email' => $this->email]);

            $this->step = 'otp';
            $this->otpCode = '';

            $this->dispatch('start-countdown', seconds: 60);
            $this->dispatch('toast', [
                'type'    => 'success',
                'message' => 'Codice inviato! Controlla la tua email.',
            ]);
        } catch (OtpRateLimitException $e) {
            $this->errorMessage = $e->getMessage();
        } catch (\Exception $e) {
            $this->errorMessage = 'Si è verificato un errore. Riprova.';
            logger('OtpLoginForm requestOtp error: ' . $e->getMessage());
        }
    }

    public function verifyOtp(): void
    {
        $this->validate();
        $this->errorMessage = null;

        $otpEmail = session('otp_email') ?? $this->email;

        if (!$otpEmail) {
            $this->errorMessage = 'Sessione scaduta. Richiedi un nuovo codice.';
            $this->resetToEmail();
            return;
        }

        try {
            $customer = app(VerifyOtpAction::class)->execute(
                $otpEmail,
                $this->otpCode,
                request()->ip() ?? '127.0.0.1',
            );

            auth('customer')->login($customer);

            $this->step = 'success';
            session()->forget('otp_email');

            $this->dispatch('customer-logged-in');
            $this->dispatch('toast', [
                'type'    => 'success',
                'message' => 'Accesso effettuato con successo!',
            ]);
            $this->dispatch('redirect-after-login', url: '/account');
        } catch (OtpInvalidException $e) {
            $this->errorMessage = $e->getMessage();
        } catch (\Exception $e) {
            $this->errorMessage = 'Si è verificato un errore durante la verifica.';
            logger('OtpLoginForm verifyOtp error: ' . $e->getMessage());
        }
    }

    public function resendOtp(): void
    {
        if (!$this->canResend) {
            return;
        }

        $this->otpCode = '';
        $this->errorMessage = null;
        $this->canResend = false;
        $this->resendCooldown = 60;

        // Re-invoke request using existing email
        $this->email = session('otp_email') ?? $this->email;
        $this->requestOtp();
    }

    public function changeEmail(): void
    {
        $this->resetToEmail();
    }

    public function updatedOtpCode(): void
    {
        if (strlen($this->otpCode) === 6) {
            $this->verifyOtp();
        }
    }

    public function enableResend(): void
    {
        $this->canResend = true;
        $this->resendCooldown = 0;
    }

    protected function resetToEmail(): void
    {
        $this->step = 'email';
        $this->otpCode = '';
        $this->errorMessage = null;
        $this->canResend = false;
        $this->resendCooldown = 60;
        session()->forget('otp_email');
    }

    public function render()
    {
        return view('livewire.public.account.otp-login-form');
    }
}
