<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-neutral-900">Metodo di spedizione</h1>
        <p class="text-neutral-600 mt-1">Scegli come vuoi ricevere il tuo ordine</p>
    </div>

    <div class="space-y-3">
        @foreach($availableShippingMethods as $method)
            <label class="flex items-center justify-between p-4 border rounded-xl cursor-pointer transition-colors hover:bg-neutral-50 {{ $selectedShippingMethodId == $method->id ? 'border-brand-primary bg-brand-primary/5' : 'border-neutral-300' }}">
                <div class="flex items-center gap-4">
                    <input type="radio" 
                           value="{{ $method->id }}"
                           wire:click="selectShippingMethod({{ $method->id }})"
                           @checked($selectedShippingMethodId == $method->id)
                           class="h-4 w-4 border-neutral-300 text-brand-primary focus:ring-brand-primary/20">
                    
                    <div>
                        <div class="font-medium text-neutral-900">{{ $method->name }}</div>
                        <div class="text-sm text-neutral-600">{{ $method->description }}</div>
                    </div>
                </div>

                <div class="text-right">
                    <div class="font-semibold text-neutral-900">
                        @if($method->price > 0)
                            €{{ number_format($method->price, 2, ',', '.') }}
                        @else
                            <span class="text-success">Gratuita</span>
                        @endif
                    </div>
                </div>
            </label>
        @endforeach
    </div>

    @if($subtotal < 99 && $selectedShippingMethodId == 1)
        <div class="bg-info-bg border border-info/20 rounded-xl p-4">
            <div class="flex items-center gap-2">
                <x-heroicon-m-information-circle class="h-5 w-5 text-info flex-shrink-0" />
                <div class="text-sm text-info">
                    <p class="font-medium">Spedizione gratuita disponibile!</p>
                    <p>Aggiungi altri €{{ number_format(99 - $subtotal, 2, ',', '.') }} per ottenere la spedizione gratuita.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="flex justify-between pt-6 border-t border-neutral-200">
        <button wire:click="previousStep" class="btn-secondary">
            <x-heroicon-m-arrow-left class="h-4 w-4 mr-2" />
            Torna all'indirizzo
        </button>
        
        <button wire:click="nextStep" class="btn-primary">
            Continua al pagamento
            <x-heroicon-m-arrow-right class="h-4 w-4 ml-2" />
        </button>
    </div>
</div>