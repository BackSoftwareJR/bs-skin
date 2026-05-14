<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-neutral-900">Riepilogo ordine</h1>
        <p class="text-neutral-600 mt-1">Controlla i dettagli prima di completare l'ordine</p>
    </div>

    <!-- Indirizzo spedizione -->
    <div class="bg-white border border-neutral-200 rounded-xl p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-medium text-neutral-900">Indirizzo di spedizione</h3>
            <button wire:click="goToStep('address')" class="text-sm text-brand-primary hover:text-brand-primary-hover">
                Modifica
            </button>
        </div>
        <div class="text-sm text-neutral-600">
            @if($selectedAddressId)
                @php $address = auth('customer')->user()?->addresses->find($selectedAddressId); @endphp
                @if($address)
                    <p class="font-medium text-neutral-900">{{ $address->first_name }} {{ $address->last_name }}</p>
                    <p>{{ $address->address }}</p>
                    @if($address->address_line_2)<p>{{ $address->address_line_2 }}</p>@endif
                    <p>{{ $address->postal_code }} {{ $address->city }} ({{ $address->province }})</p>
                    <p>{{ $address->phone }}</p>
                @endif
            @else
                <p>Nuovo indirizzo (verrà salvato)</p>
            @endif
        </div>
    </div>

    <!-- Metodo spedizione -->
    <div class="bg-white border border-neutral-200 rounded-xl p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-medium text-neutral-900">Spedizione</h3>
            <button wire:click="goToStep('shipping')" class="text-sm text-brand-primary hover:text-brand-primary-hover">
                Modifica
            </button>
        </div>
        <div class="text-sm text-neutral-600">
            @php $shippingMethod = $availableShippingMethods->firstWhere('id', $selectedShippingMethodId); @endphp
            @if($shippingMethod)
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-neutral-900">{{ $shippingMethod->name }}</p>
                        <p>{{ $shippingMethod->description }}</p>
                    </div>
                    <div class="text-right">
                        @if($shippingMethod->price > 0)
                            <span class="font-medium">€{{ number_format($shippingMethod->price, 2, ',', '.') }}</span>
                        @else
                            <span class="font-medium text-success">Gratuita</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Metodo pagamento -->
    <div class="bg-white border border-neutral-200 rounded-xl p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-medium text-neutral-900">Pagamento</h3>
            <button wire:click="goToStep('payment')" class="text-sm text-brand-primary hover:text-brand-primary-hover">
                Modifica
            </button>
        </div>
        <div class="text-sm text-neutral-600">
            @if($selectedPaymentMethod == 'bank_transfer')
                <div class="flex items-center gap-2">
                    <x-heroicon-o-building-library class="h-4 w-4" />
                    <span class="font-medium text-neutral-900">Bonifico bancario</span>
                </div>
                <p class="mt-1">Riceverai le istruzioni via email</p>
            @elseif($selectedPaymentMethod == 'stripe')
                <div class="flex items-center gap-2">
                    <x-heroicon-o-credit-card class="h-4 w-4" />
                    <span class="font-medium text-neutral-900">Carta di credito</span>
                </div>
            @elseif($selectedPaymentMethod == 'paypal')
                <div class="flex items-center gap-2">
                    <span class="font-medium text-neutral-900">PayPal</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Termini e condizioni -->
    <div class="bg-neutral-50 border border-neutral-200 rounded-xl p-4">
        <label class="flex items-start gap-3 cursor-pointer">
            <input type="checkbox" 
                   wire:model="termsAccepted"
                   class="mt-1 h-4 w-4 rounded border-neutral-300 text-brand-primary focus:ring-brand-primary/20 @error('termsAccepted') border-danger @enderror">
            <div class="text-sm text-neutral-700 leading-relaxed">
                <span class="font-medium">Ho letto e accetto i </span>
                <a href="/termini-di-servizio" target="_blank" class="link-teal">Termini di servizio</a>, 
                <a href="/privacy-policy" target="_blank" class="link-teal">Privacy Policy</a> 
                <span class="font-medium"> e confermo che tutte le informazioni fornite sono corrette.</span>
                
                <p class="mt-2 text-xs text-neutral-600">
                    Completando l'ordine accetti le nostre condizioni di vendita e riconosci di aver compreso i termini di reso e rimborso.
                </p>
            </div>
        </label>
        @error('termsAccepted')
            <p class="text-xs text-danger mt-2 flex items-center gap-1">
                <x-heroicon-m-x-circle class="h-3 w-3" />
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Actions -->
    <div class="flex justify-between pt-6 border-t border-neutral-200">
        <button wire:click="previousStep" class="btn-secondary">
            <x-heroicon-m-arrow-left class="h-4 w-4 mr-2" />
            Torna al pagamento
        </button>
        
        <button wire:click="placeOrder" 
                class="btn-primary text-lg px-8 py-3"
                wire:loading.attr="disabled"
                {{ !$termsAccepted ? 'disabled' : '' }}>
            <span wire:loading.remove wire:target="placeOrder">
                Conferma ordine • €{{ number_format($total, 2, ',', '.') }}
            </span>
            <span wire:loading wire:target="placeOrder" class="flex items-center gap-2">
                <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                Elaborazione...
            </span>
        </button>
    </div>
</div>