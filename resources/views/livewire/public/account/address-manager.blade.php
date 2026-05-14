<div class="max-w-4xl">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-neutral-900">I miei indirizzi</h2>
            <p class="text-sm text-neutral-500 mt-1">Gestisci i tuoi indirizzi di spedizione e fatturazione</p>
        </div>
        @if(!$showForm)
            <button wire:click="create" class="btn-primary">
                <x-heroicon-m-plus class="h-4 w-4 mr-2" />
                Aggiungi indirizzo
            </button>
        @endif
    </div>

    @if($showForm)
        <!-- Form indirizzo -->
        <div class="bg-white rounded-2xl shadow-soft-md p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-neutral-900">
                    {{ $editingAddressId ? 'Modifica indirizzo' : 'Nuovo indirizzo' }}
                </h3>
                <button wire:click="cancelForm" class="text-neutral-500 hover:text-neutral-700 transition-colors">
                    <x-heroicon-o-x-mark class="h-5 w-5" />
                </button>
            </div>

            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nome -->
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-neutral-700 mb-1">
                            Nome <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="firstName" wire:model="firstName"
                               class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20 @error('firstName') border-danger @enderror">
                        @error('firstName')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Cognome -->
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-neutral-700 mb-1">
                            Cognome <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="lastName" wire:model="lastName"
                               class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20 @error('lastName') border-danger @enderror">
                        @error('lastName')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Indirizzo -->
                <div>
                    <label for="address" class="block text-sm font-medium text-neutral-700 mb-1">
                        Indirizzo <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="address" wire:model="address" placeholder="Via Roma 123"
                           class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20 @error('address') border-danger @enderror">
                    @error('address')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Indirizzo 2 (opzionale) -->
                <div>
                    <label for="addressLine2" class="block text-sm font-medium text-neutral-700 mb-1">
                        Appartamento, scala, ecc. (opzionale)
                    </label>
                    <input type="text" id="addressLine2" wire:model="addressLine2" placeholder="Scala A, Interno 5"
                           class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Città -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-neutral-700 mb-1">
                            Città <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="city" wire:model="city"
                               class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20 @error('city') border-danger @enderror">
                        @error('city')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- CAP -->
                    <div>
                        <label for="postalCode" class="block text-sm font-medium text-neutral-700 mb-1">
                            CAP <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="postalCode" wire:model="postalCode" placeholder="10100" maxlength="5"
                               class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20 @error('postalCode') border-danger @enderror">
                        @error('postalCode')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Provincia -->
                    <div>
                        <label for="province" class="block text-sm font-medium text-neutral-700 mb-1">
                            Provincia <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="province" wire:model="province" placeholder="TO" maxlength="2"
                               class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20 @error('province') border-danger @enderror">
                        @error('province')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Telefono -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-neutral-700 mb-1">
                        Telefono <span class="text-danger">*</span>
                    </label>
                    <input type="tel" id="phone" wire:model="phone" placeholder="+39 123 456 7890"
                           class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20 @error('phone') border-danger @enderror">
                    @error('phone')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Default -->
                <div class="pt-4 border-t border-neutral-200">
                    <label class="inline-flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="isDefault"
                               class="h-4 w-4 rounded-md border-neutral-300 text-brand-primary focus:ring-brand-primary/20">
                        <span class="text-sm text-neutral-700">Imposta come indirizzo predefinito</span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-4">
                    <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ $editingAddressId ? 'Aggiorna' : 'Salva' }} indirizzo</span>
                        <span wire:loading>Salvando...</span>
                    </button>
                    <button type="button" wire:click="cancelForm" class="btn-secondary">
                        Annulla
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Lista indirizzi -->
    @if($addresses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($addresses as $address)
                <div class="bg-white rounded-2xl shadow-soft-md p-6 relative">
                    @if($address->is_default)
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-brand-primary-soft text-brand-primary-700">
                                Predefinito
                            </span>
                        </div>
                    @endif

                    <div class="pr-16 mb-4">
                        <h4 class="font-semibold text-neutral-900">{{ $address->first_name }} {{ $address->last_name }}</h4>
                        <div class="text-sm text-neutral-600 mt-2 space-y-1">
                            <p>{{ $address->address }}</p>
                            @if($address->address_line_2)
                                <p>{{ $address->address_line_2 }}</p>
                            @endif
                            <p>{{ $address->postal_code }} {{ $address->city }} ({{ $address->province }})</p>
                            <p>{{ $address->phone }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button wire:click="edit({{ $address->id }})" class="text-sm text-brand-primary hover:text-brand-primary-hover transition-colors">
                            Modifica
                        </button>
                        @if(!$address->is_default)
                            <span class="text-neutral-300">•</span>
                            <button wire:click="setDefault({{ $address->id }})" class="text-sm text-neutral-600 hover:text-neutral-900 transition-colors">
                                Predefinito
                            </button>
                        @endif
                        @if($addresses->count() > 1)
                            <span class="text-neutral-300">•</span>
                            <button wire:click="delete({{ $address->id }})" 
                                    wire:confirm="Sei sicuro di voler eliminare questo indirizzo?"
                                    class="text-sm text-danger hover:text-red-700 transition-colors">
                                Elimina
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Stato vuoto -->
        <div class="bg-white rounded-2xl shadow-soft-md p-12 text-center">
            <x-heroicon-o-map-pin class="h-12 w-12 text-neutral-300 mx-auto mb-4" />
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">Nessun indirizzo salvato</h3>
            <p class="text-neutral-500 mb-6">Aggiungi il tuo primo indirizzo per velocizzare i futuri ordini</p>
            <button wire:click="create" class="btn-primary">
                Aggiungi indirizzo
            </button>
        </div>
    @endif
</div>