<div>
    @if($state === 'success')
        <div class="text-center py-6">
            <div class="w-12 h-12 bg-success-bg rounded-full flex items-center justify-center mx-auto mb-4">
                <x-heroicon-s-check-circle class="h-6 w-6 text-success" />
            </div>
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">Iscrizione completata!</h3>
            <p class="text-sm text-neutral-600">{{ $message }}</p>
            
            <button wire:click="reset" class="mt-4 text-sm link-teal">
                Iscriviti con un'altra email
            </button>
        </div>
    @else
        <form wire:submit="subscribe" class="space-y-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Email -->
                <div class="flex-1">
                    <label for="newsletter-email" class="sr-only">Email</label>
                    <input type="email" 
                           id="newsletter-email"
                           wire:model="email" 
                           placeholder="La tua email"
                           class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20 @error('email') border-danger @enderror"
                           required>
                </div>

                <!-- Nome (opzionale) -->
                <div class="flex-1 sm:flex-initial sm:w-48">
                    <label for="newsletter-name" class="sr-only">Nome (opzionale)</label>
                    <input type="text" 
                           id="newsletter-name"
                           wire:model="name" 
                           placeholder="Nome (opzionale)"
                           class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20">
                </div>

                <!-- Submit button -->
                <button type="submit" 
                        class="btn-primary whitespace-nowrap"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="subscribe">Iscriviti</span>
                    <span wire:loading wire:target="subscribe" class="flex items-center gap-2">
                        <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                        Iscrizione...
                    </span>
                </button>
            </div>

            <!-- Errori -->
            @if($errors->any())
                <div class="text-sm text-danger space-y-1">
                    @foreach($errors->all() as $error)
                        <p class="flex items-center gap-1">
                            <x-heroicon-m-x-circle class="h-3 w-3 flex-shrink-0" />
                            {{ $error }}
                        </p>
                    @endforeach
                </div>
            @endif

            @if($state === 'error' && $message)
                <p class="text-sm text-danger flex items-center gap-1">
                    <x-heroicon-m-x-circle class="h-3 w-3" />
                    {{ $message }}
                </p>
            @endif

            <!-- Privacy note -->
            <p class="text-xs text-neutral-500 leading-relaxed">
                Iscrivendoti accetti di ricevere le nostre newsletter. 
                Puoi cancellarti in qualsiasi momento. 
                Leggi la nostra <a href="/privacy-policy" class="link-teal">Privacy Policy</a>.
            </p>
        </form>
    @endif
</div>