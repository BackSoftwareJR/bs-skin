<div class="w-full max-w-sm mx-auto"
     x-data="{
         countdown: 0,
         timer: null,
         startCountdown(seconds) {
             this.countdown = seconds;
             clearInterval(this.timer);
             this.timer = setInterval(() => {
                 if (this.countdown > 0) {
                     this.countdown--;
                 } else {
                     clearInterval(this.timer);
                     $wire.enableResend();
                 }
             }, 1000);
         }
     }"
     @start-countdown.window="startCountdown($event.detail.seconds)">

    <div class="bg-white rounded-2xl shadow-soft-xl p-8">

        @if($step === 'email')
            {{-- Step 1: Email + Termini --}}
            <div class="text-center mb-8">
                <div class="w-12 h-12 bg-brand-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-neutral-900">Accedi o Registrati</h2>
                <p class="text-sm text-neutral-500 mt-1">
                    Inserisci la tua email per ricevere un codice di accesso
                </p>
            </div>

            <form wire:submit="requestOtp" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-neutral-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                           id="email"
                           wire:model="email"
                           placeholder="il-tuo@email.it"
                           autocomplete="email"
                           class="w-full rounded-xl border-neutral-300 text-sm focus:border-brand-primary focus:ring-brand-primary/20 @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="inline-flex items-start gap-3 cursor-pointer">
                        <input type="checkbox"
                               wire:model="acceptTerms"
                               class="mt-0.5 h-4 w-4 rounded border-neutral-300 text-brand-primary focus:ring-brand-primary/20 @error('acceptTerms') border-red-400 @enderror">
                        <span class="text-sm text-neutral-600 leading-relaxed">
                            Ho letto e accetto i
                            <a href="/termini-di-servizio" target="_blank" class="text-brand-primary hover:underline">Termini di servizio</a>
                            e la
                            <a href="/privacy-policy" target="_blank" class="text-brand-primary hover:underline">Privacy Policy</a>
                        </span>
                    </label>
                    @error('acceptTerms')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if($errorMessage)
                    <div class="p-3 bg-red-50 border border-red-100 rounded-xl">
                        <p class="text-sm text-red-600">{{ $errorMessage }}</p>
                    </div>
                @endif

                <button type="submit"
                        class="w-full bg-brand-primary hover:bg-brand-primary/90 text-white font-medium py-3 px-4 rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>Ricevi codice di accesso</span>
                    <span wire:loading class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Invio in corso...
                    </span>
                </button>
            </form>

        @elseif($step === 'otp')
            {{-- Step 2: Verifica OTP --}}
            <div class="text-center mb-8">
                <div class="w-12 h-12 bg-brand-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-neutral-900">Controlla la tua email</h2>
                <p class="text-sm text-neutral-500 mt-1">
                    Abbiamo inviato un codice a
                </p>
                @php
                    $maskedEmail = session('otp_email') ?? $email;
                    if ($maskedEmail) {
                        [$local, $domain] = explode('@', $maskedEmail, 2);
                        $maskedEmail = substr($local, 0, 1) . str_repeat('*', max(strlen($local) - 1, 2)) . '@' . $domain;
                    }
                @endphp
                <p class="text-sm font-semibold text-neutral-800 mt-1">{{ $maskedEmail }}</p>
            </div>

            <form wire:submit="verifyOtp" class="space-y-6">
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
                               autocomplete="one-time-code"
                               class="w-40 text-center text-3xl font-mono tracking-[0.3em] rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20 @error('otpCode') border-red-400 @enderror"
                               autofocus>
                    </div>
                    @error('otpCode')
                        <p class="text-xs text-red-500 mt-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                @if($errorMessage)
                    <div class="p-3 bg-red-50 border border-red-100 rounded-xl">
                        <p class="text-sm text-red-600 text-center">{{ $errorMessage }}</p>
                    </div>
                @endif

                <button type="submit"
                        class="w-full bg-brand-primary hover:bg-brand-primary/90 text-white font-medium py-3 px-4 rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>Verifica codice</span>
                    <span wire:loading class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Verifica...
                    </span>
                </button>

                <div class="text-center text-sm text-neutral-500 space-y-2">
                    <p>Non hai ricevuto il codice?</p>
                    <div class="flex items-center justify-center gap-4">
                        <template x-if="countdown > 0">
                            <span class="text-neutral-400">Rinvia tra <span x-text="countdown"></span>s</span>
                        </template>
                        <template x-if="countdown === 0 && {{ $canResend ? 'true' : 'false' }}">
                            <button type="button"
                                    wire:click="resendOtp"
                                    class="text-brand-primary hover:text-brand-primary/80 font-medium">
                                Rinvia codice
                            </button>
                        </template>
                        <span class="text-neutral-300">•</span>
                        <button type="button"
                                wire:click="changeEmail"
                                class="text-brand-primary hover:text-brand-primary/80 font-medium">
                            Cambia email
                        </button>
                    </div>
                </div>
            </form>

        @elseif($step === 'success')
            {{-- Step 3: Successo --}}
            <div class="text-center py-4">
                <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-neutral-900 mb-2">Accesso completato!</h2>
                <p class="text-sm text-neutral-500 mb-6">
                    Benvenuto in SkinTemple. Verrai reindirizzato al tuo account.
                </p>
                <div class="flex justify-center">
                    <svg class="w-6 h-6 animate-spin text-brand-primary" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </div>
            </div>
        @endif
    </div>

    @if($step !== 'success')
        <div class="mt-4 text-center text-xs text-neutral-400">
            Hai bisogno di aiuto? <a href="/contatti" class="text-brand-primary hover:underline">Contattaci</a>
        </div>
    @endif
</div>

@script
<script>
    $wire.on('redirect-after-login', (data) => {
        setTimeout(() => {
            window.location.href = data.url ?? '/account';
        }, 1500);
    });
</script>
@endscript
