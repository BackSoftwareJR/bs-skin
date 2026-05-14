<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-neutral-900">Indirizzo di spedizione</h1>
        <p class="text-neutral-600 mt-1">Seleziona o aggiungi un indirizzo per la spedizione</p>
    </div>

    @auth('customer')
        @php
            $addresses = auth('customer')->user()->addresses ?? collect();
        @endphp

        @if($addresses->count() > 0)
            <!-- Indirizzi salvati -->
            <div class="space-y-3">
                <h3 class="text-sm font-medium text-neutral-900">Indirizzi salvati</h3>
                @foreach($addresses as $address)
                    <label class="flex items-start gap-3 p-4 border rounded-xl cursor-pointer transition-colors hover:bg-neutral-50 {{ $selectedAddressId == $address->id ? 'border-brand-primary bg-brand-primary/5' : 'border-neutral-300' }}">
                        <input type="radio" 
                               value="{{ $address->id }}"
                               wire:click="selectAddress({{ $address->id }})"
                               @checked($selectedAddressId == $address->id)
                               class="mt-1 h-4 w-4 border-neutral-300 text-brand-primary focus:ring-brand-primary/20">
                        <div class="flex-1">
                            <div class="font-medium text-neutral-900">
                                {{ $address->first_name }} {{ $address->last_name }}
                                @if($address->is_default)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-brand-primary-soft text-brand-primary-700 ml-2">
                                        Predefinito
                                    </span>
                                @endif
                            </div>
                            <div class="text-sm text-neutral-600 mt-1">
                                <p>{{ $address->address }}</p>
                                @if($address->address_line_2)
                                    <p>{{ $address->address_line_2 }}</p>
                                @endif
                                <p>{{ $address->postal_code }} {{ $address->city }} ({{ $address->province }})</p>
                                <p>{{ $address->phone }}</p>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            <!-- Divider -->
            <div class="flex items-center">
                <div class="flex-1 border-t border-neutral-300"></div>
                <span class="px-3 text-sm text-neutral-500">oppure</span>
                <div class="flex-1 border-t border-neutral-300"></div>
            </div>
        @endif
    @endauth

    <!-- Nuovo indirizzo -->
    <div>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" 
                   wire:click="toggleNewAddress"
                   @checked($useNewAddress)
                   class="h-4 w-4 border-neutral-300 text-brand-primary focus:ring-brand-primary/20">
            <span class="text-sm font-medium text-neutral-900">Usa un nuovo indirizzo</span>
        </label>

        @if($useNewAddress)
            <div class="mt-4 p-4 border border-neutral-300 rounded-xl">
                <!-- Form nuovo indirizzo inline -->
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Nome *</label>
                            <input type="text" wire:model="shippingAddress.first_name"
                                   class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Cognome *</label>
                            <input type="text" wire:model="shippingAddress.last_name"
                                   class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Indirizzo *</label>
                        <input type="text" wire:model="shippingAddress.address"
                               class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Città *</label>
                            <input type="text" wire:model="shippingAddress.city"
                                   class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">CAP *</label>
                            <input type="text" wire:model="shippingAddress.postal_code" maxlength="5"
                                   class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Provincia *</label>
                            <input type="text" wire:model="shippingAddress.province" maxlength="2"
                                   class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Telefono *</label>
                        <input type="tel" wire:model="shippingAddress.phone"
                               class="w-full rounded-xl border-neutral-300 focus:border-brand-primary focus:ring-brand-primary/20">
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Fatturazione -->
    <div class="border-t border-neutral-200 pt-6">
        <h3 class="text-lg font-medium text-neutral-900 mb-4">Indirizzo di fatturazione</h3>
        
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" 
                   wire:model="sameAddressForBilling"
                   class="h-4 w-4 rounded border-neutral-300 text-brand-primary focus:ring-brand-primary/20">
            <span class="text-sm text-neutral-700">Stesso indirizzo della spedizione</span>
        </label>

        @if(!$sameAddressForBilling)
            <!-- Form billing address (simile a shipping) -->
            <div class="mt-4 p-4 border border-neutral-300 rounded-xl">
                <p class="text-sm text-neutral-600 mb-4">Inserisci un indirizzo di fatturazione diverso</p>
                <!-- Stesso form del shipping address ma per billingAddress -->
            </div>
        @endif
    </div>

    <!-- Actions -->
    <div class="flex justify-end pt-6 border-t border-neutral-200">
        <button wire:click="nextStep" class="btn-primary">
            Continua alla spedizione
            <x-heroicon-m-arrow-right class="h-4 w-4 ml-2" />
        </button>
    </div>
</div>