@extends('layouts.app')

@section('title', 'Accedi - SkinTemple')

@push('meta')
    <meta name="description" content="Accedi al tuo account SkinTemple con un codice OTP sicuro e senza password.">
@endpush

<div class="min-h-screen bg-neutral-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-block">
                <span class="text-2xl font-display font-semibold text-brand-primary">SkinTemple</span>
            </a>
            <h2 class="mt-6 text-2xl font-semibold text-neutral-900">
                Benvenuto
            </h2>
            <p class="mt-2 text-sm text-neutral-600">
                Accedi o registrati con la tua email — nessuna password necessaria
            </p>
        </div>

        <!-- Login Form -->
        <livewire:public.account.otp-login-form />

        <!-- Footer links -->
        <div class="mt-8 text-center space-y-2">
            <p class="text-xs text-neutral-500">
                Continuando accetti i nostri 
                <a href="/termini-di-servizio" class="link-teal">Termini di servizio</a> 
                e la 
                <a href="/privacy-policy" class="link-teal">Privacy Policy</a>
            </p>
            <p class="text-xs text-neutral-500">
                Hai bisogno di aiuto? 
                <a href="/contatti" class="link-teal">Contattaci</a>
            </p>
        </div>
    </div>
</div>