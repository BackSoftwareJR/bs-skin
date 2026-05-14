<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-neutral-900">Metodo di pagamento</h1>
        <p class="text-neutral-600 mt-1">Scegli come vuoi pagare il tuo ordine</p>
    </div>

    <div class="space-y-3">
        <!-- Bonifico bancario -->
        <label class="flex items-start justify-between p-4 border rounded-xl cursor-pointer transition-colors hover:bg-neutral-50 {{ $selectedPaymentMethod == 'bank_transfer' ? 'border-brand-primary bg-brand-primary/5' : 'border-neutral-300' }}">
            <div class="flex items-start gap-4">
                <input type="radio" 
                       value="bank_transfer"
                       wire:click="selectPaymentMethod('bank_transfer')"
                       @checked($selectedPaymentMethod == 'bank_transfer')
                       class="mt-1 h-4 w-4 border-neutral-300 text-brand-primary focus:ring-brand-primary/20">
                
                <div>
                    <div class="font-medium text-neutral-900 flex items-center gap-2">
                        <x-heroicon-o-building-library class="h-5 w-5 text-neutral-600" />
                        Bonifico bancario
                    </div>
                    <div class="text-sm text-neutral-600 mt-1">
                        Paga tramite bonifico bancario. Riceverai le istruzioni via email.
                    </div>
                </div>
            </div>
        </label>

        <!-- Carta di credito (placeholder) -->
        <label class="flex items-start justify-between p-4 border border-neutral-200 rounded-xl opacity-50 cursor-not-allowed">
            <div class="flex items-start gap-4">
                <input type="radio" disabled class="mt-1 h-4 w-4 border-neutral-300 cursor-not-allowed">
                
                <div>
                    <div class="font-medium text-neutral-500 flex items-center gap-2">
                        <x-heroicon-o-credit-card class="h-5 w-5 text-neutral-400" />
                        Carta di credito
                        <span class="text-xs bg-neutral-100 text-neutral-500 px-2 py-1 rounded-full">Presto disponibile</span>
                    </div>
                    <div class="text-sm text-neutral-400 mt-1">
                        Visa, Mastercard, American Express
                    </div>
                </div>
            </div>
        </label>

        <!-- PayPal (placeholder) -->
        <label class="flex items-start justify-between p-4 border border-neutral-200 rounded-xl opacity-50 cursor-not-allowed">
            <div class="flex items-start gap-4">
                <input type="radio" disabled class="mt-1 h-4 w-4 border-neutral-300 cursor-not-allowed">
                
                <div>
                    <div class="font-medium text-neutral-500 flex items-center gap-2">
                        <svg class="h-5 w-5 text-neutral-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h8.418c2.508 0 4.684.739 5.943 2.014 1.264 1.284 1.866 3.138 1.866 5.71 0 2.572-.602 4.426-1.866 5.71-1.259 1.275-3.435 2.014-5.943 2.014h-1.904c-.524 0-.972.382-1.054.901l-1.685 4.988z"/>
                        </svg>
                        PayPal
                        <span class="text-xs bg-neutral-100 text-neutral-500 px-2 py-1 rounded-full">Presto disponibile</span>
                    </div>
                    <div class="text-sm text-neutral-400 mt-1">
                        Paga in sicurezza con PayPal
                    </div>
                </div>
            </div>
        </label>
    </div>

    @if($selectedPaymentMethod == 'bank_transfer')
        <div class="bg-neutral-50 border border-neutral-200 rounded-xl p-4">
            <h4 class="font-medium text-neutral-900 mb-2">Istruzioni per il bonifico</h4>
            <div class="text-sm text-neutral-600 space-y-1">
                <p>Dopo aver completato l'ordine riceverai via email i dettagli per effettuare il bonifico:</p>
                <ul class="list-disc list-inside space-y-1 mt-2">
                    <li>IBAN del conto SkinTemple</li>
                    <li>Causale con il numero d'ordine</li>
                    <li>Importo esatto da versare</li>
                </ul>
                <p class="mt-3 text-xs font-medium text-neutral-700">
                    Il tuo ordine sarà evaso dopo la ricezione del pagamento (1-2 giorni lavorativi).
                </p>
            </div>
        </div>
    @endif

    <!-- Trust signals -->
    <div class="bg-neutral-50 rounded-xl p-4">
        <div class="flex items-center justify-center gap-6 text-sm text-neutral-600">
            <div class="flex items-center gap-2">
                <x-heroicon-m-lock-closed class="h-4 w-4 text-success" />
                <span>Pagamento sicuro</span>
            </div>
            <div class="flex items-center gap-2">
                <x-heroicon-m-shield-check class="h-4 w-4 text-success" />
                <span>SSL 256-bit</span>
            </div>
            <div class="flex items-center gap-2">
                <x-heroicon-m-check-badge class="h-4 w-4 text-success" />
                <span>Dati protetti</span>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-between pt-6 border-t border-neutral-200">
        <button wire:click="previousStep" class="btn-secondary">
            <x-heroicon-m-arrow-left class="h-4 w-4 mr-2" />
            Torna alla spedizione
        </button>
        
        <button wire:click="nextStep" class="btn-primary" {{ !$selectedPaymentMethod ? 'disabled' : '' }}>
            Rivedi ordine
            <x-heroicon-m-arrow-right class="h-4 w-4 ml-2" />
        </button>
    </div>
</div>