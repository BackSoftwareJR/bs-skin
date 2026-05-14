<div class="w-full max-w-md mx-auto">
    <div class="bg-white rounded-3xl shadow-soft-xl p-8">
        @if($step === 'email')
            <!-- Step 1: Email + Termini -->
            <div class="text-center mb-6">
                <h2 class="text-xl font-semibold text-neutral-900">Accedi o Registrati</h2>
                <p class="text-sm text-neutral-500 mt-1">
                    Inserisci la tua email per ricevere un codice di accesso
                </p>
            </div>

            <form wire:submit="requestOtp" class="space-y-4">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-neutral-700 mb-1">
                        Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           wire:model="email"
                           placeholder="il-tuo@email.it"
                           class="w-full rounded-xl border-neutral-300 text-sm focus:border-brand-primary focus:ring-brand-primary/20 @error('email') border-danger @enderror"
                           required>
                    @error('email')
                        <p class="text-xs text-danger mt-1 flex items-center gap-1">
                            <x-heroicon-m-x-circle class="h-3 w-3" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Termini -->
                <div>
                    <label class="inline-flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" 
                               wire:model="acceptTerms"
                               class="mt-0.5 h-4 w-4 rounded-md border-neutral-300 text-brand-primary focus:ring-brand-primary/20 @error('acceptTerms') border-danger @enderror">
                        <span class="text-sm text-neutral-600 leading-relaxed">
                            Ho letto e accetto i 
                            <a href="/termini-di-servizio" target="_blank" class="link-teal">Termini di servizio</a> 
                            e la 
                            <a href="/privacy-policy" target="_blank" class="link-teal">Privacy Policy</a>
                        </span>
                    </label>
                    @error('acceptTerms')
                        <p class="text-xs text-danger mt-1 flex items-center gap-1">
                            <x-heroicon-m-x-circle class="h-3 w-3" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                @if($errorMessage)
                    <div class="p-3 bg-danger-bg rounded-xl">
                        <p class="text-sm text-danger">{{ $errorMessage }}</p>
                    </div>
                @endif

                <!-- Submit -->
                <button type="submit" 
                        class="w-full btn-primary" 
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>Ricevi codice di accesso</span>
                    <span wire:loading class="flex items-center gap-2">
                        <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                        Invio in corso...
                    </span>
                </button>
            </form>

        @elseif($step === 'otp')
            <!-- Step 2: Verifica OTP -->
            <div class="text-center mb-6">
                <h2 class="text-xl font-semibold text-neutral-900">Inserisci il codice</h2>
                <p class="text-sm text-neutral-500 mt-1">
                    Abbiamo inviato un codice a <strong>{{ session('otp_email') ?? $email }}</strong>
                </p>
            </div>

            <form wire:submit="verifyOtp" class="space-y-6">
                <!-- OTP Input -->
                <div>
                    <label for="otp" class="block text-sm font-medium text-neutral-700 mb-3 text-center">
                        Codice di 6 cifre
                    </label>
                    <div class="flex justify-center">
                        <input type="text" 
                               id="otp"
                               wire:model.live="otpCode"
                               inputmode="numeric"
                               pattern="[0-9]*"
                               maxlength="6"
                               placeholder="000000"
                               class="w-32 text-center text-2xl font-mono tracking-widest rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20 @error('otpCode') border-danger @enderror"
                               autofocus>
                    </div>
                    @error('otpCode')
                        <p class="text-xs text-danger mt-2 text-center flex items-center justify-center gap-1">
                            <x-heroicon-m-x-circle class="h-3 w-3" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                @if($errorMessage)
                    <div class="p-3 bg-danger-bg rounded-xl">
                        <p class="text-sm text-danger text-center">{{ $errorMessage }}</p>
                    </div>
                @endif

                <!-- Submit -->
                <button type="submit" 
                        class="w-full btn-primary" 
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>Verifica codice</span>
                    <span wire:loading class="flex items-center justify-center gap-2">
                        <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                        Verifica...
                    </span>
                </button>

                <!-- Resend / Change email -->
                <div class="text-center space-y-2 text-sm text-neutral-500">
                    <p>Non hai ricevuto il codice?</p>
                    <div class="flex items-center justify-center gap-4">
                        @if($canResend)
                            <button type="button" 
                                    wire:click="resendOtp" 
                                    class="link-teal">
                                Rinvia codice
                            </button>
                        @else
                            <span class="text-neutral-400">
                                Rinvia tra {{ $resendCooldown }}s
                            </span>
                        @endif
                        <span class="text-neutral-300">•</span>
                        <button type="button" 
                                wire:click="changeEmail" 
                                class="link-teal">
                            Cambia email
                        </button>
                    </div>
                </div>
            </form>

        @elseif($step === 'success')
            <!-- Step 3: Successo -->
            <div class="text-center">
                <div class="w-16 h-16 bg-success-bg rounded-full flex items-center justify-center mx-auto mb-4">
                    <x-heroicon-s-check-circle class="h-8 w-8 text-success" />
                </div>
                <h2 class="text-xl font-semibold text-neutral-900 mb-2">Accesso completato!</h2>
                <p class="text-sm text-neutral-500 mb-6">
                    Benvenuto in SkinTemple. Verrai reindirizzato al tuo account.
                </p>
                <div class="flex justify-center">
                    <div class="w-6 h-6 border-2 border-brand-primary border-t-transparent rounded-full animate-spin"></div>
                </div>
            </div>
        @endif
    </div>

    <!-- Link utili -->
    @if($step !== 'success')
        <div class="mt-6 text-center text-xs text-neutral-500">
            <p>
                Hai bisogno di aiuto? <a href="/contatti" class="link-teal">Contattaci</a>
            </p>
        </div>
    @endif
</div>

@script
<script>
    // Gestione countdown resend
    $wire.on('update-cooldown', (data) => {
        // Il cooldown viene gestito lato server
    });

    $wire.on('enable-resend', () => {
        $wire.enableResend();
    });

    $wire.on('redirect-after-delay', (data) => {
        setTimeout(() => {
            window.location.href = data.url;
        }, 1500);
    });
</script>
@endscript