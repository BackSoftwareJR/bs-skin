<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-soft-md p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-neutral-900">Informazioni personali</h2>
            <p class="text-sm text-neutral-500 mt-1">
                Aggiorna le tue informazioni di contatto
            </p>
        </div>

        <form wire:submit="save" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Nome -->
                <div>
                    <label for="name" class="block text-sm font-medium text-neutral-700 mb-1">
                        Nome <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           wire:model="name"
                           placeholder="Mario"
                           class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20 @error('name') border-danger @enderror">
                    @error('name')
                        <p class="text-xs text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cognome -->
                <div>
                    <label for="surname" class="block text-sm font-medium text-neutral-700 mb-1">
                        Cognome <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           id="surname"
                           wire:model="surname"
                           placeholder="Rossi"
                           class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20 @error('surname') border-danger @enderror">
                    @error('surname')
                        <p class="text-xs text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email (readonly) -->
            <div>
                <label for="email" class="block text-sm font-medium text-neutral-700 mb-1">
                    Email
                </label>
                <input type="email"
                       id="email"
                       value="{{ $email }}"
                       readonly
                       class="w-full rounded-xl border-neutral-300 bg-neutral-50 text-neutral-500 cursor-not-allowed">
                <p class="text-xs text-neutral-500 mt-1">
                    Per modificare l'email contatta il nostro supporto
                </p>
            </div>

            <!-- Telefono -->
            <div>
                <label for="phone" class="block text-sm font-medium text-neutral-700 mb-1">
                    Telefono
                </label>
                <input type="tel"
                       id="phone"
                       wire:model="phone"
                       placeholder="+39 123 456 7890"
                       class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20 @error('phone') border-danger @enderror">
                @error('phone')
                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Marketing consent -->
            <div class="pt-4 border-t border-neutral-200">
                <label class="inline-flex items-start gap-3 cursor-pointer">
                    <input type="checkbox"
                           wire:model="marketingConsent"
                           class="mt-1 h-4 w-4 rounded-md border-neutral-300 text-brand-primary focus:ring-brand-primary/20">
                    <div>
                        <span class="text-sm font-medium text-neutral-700">Comunicazioni marketing</span>
                        <p class="text-xs text-neutral-500 mt-0.5">
                            Accetto di ricevere newsletter, offerte speciali e comunicazioni promozionali via email
                        </p>
                    </div>
                </label>
            </div>

            <!-- Submit -->
            <div class="pt-4">
                <button type="submit"
                        class="btn-primary"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>Salva modifiche</span>
                    <span wire:loading class="flex items-center gap-2">
                        <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                        Salvataggio...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
