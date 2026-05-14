<div class="max-w-2xl mx-auto text-center py-12">
    <!-- Success icon -->
    <div class="w-20 h-20 bg-success-bg rounded-full flex items-center justify-center mx-auto mb-6">
        <x-heroicon-s-check-circle class="h-12 w-12 text-success" />
    </div>

    <!-- Titolo -->
    <h1 class="text-3xl font-semibold text-neutral-900 mb-2">Ordine completato!</h1>
    <p class="text-lg text-neutral-600 mb-8">
        Grazie per il tuo acquisto. Il tuo ordine è stato ricevuto e sarà elaborato a breve.
    </p>

    @if($completedOrder)
        <!-- Dettagli ordine -->
        <div class="bg-neutral-50 rounded-2xl p-6 mb-8 text-left">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-neutral-500">Numero ordine</span>
                    <p class="font-mono font-semibold text-neutral-900">{{ $completedOrder->order_number }}</p>
                </div>
                <div>
                    <span class="text-neutral-500">Data</span>
                    <p class="font-semibold text-neutral-900">{{ $completedOrder->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <span class="text-neutral-500">Totale</span>
                    <p class="font-semibold text-neutral-900">€{{ number_format($completedOrder->total, 2, ',', '.') }}</p>
                </div>
                <div>
                    <span class="text-neutral-500">Pagamento</span>
                    <p class="font-semibold text-neutral-900">
                        @if($completedOrder->payment_method == 'bank_transfer')
                            Bonifico bancario
                        @elseif($completedOrder->payment_method == 'stripe')
                            Carta di credito
                        @elseif($completedOrder->payment_method == 'paypal')
                            PayPal
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Prossimi passi -->
        <div class="bg-info-bg border border-info/20 rounded-2xl p-6 mb-8 text-left">
            <h3 class="font-semibold text-info mb-3 flex items-center gap-2">
                <x-heroicon-m-information-circle class="h-5 w-5" />
                Prossimi passi
            </h3>
            <div class="text-sm text-info space-y-2">
                @if($completedOrder->payment_method == 'bank_transfer')
                    <p>• <strong>Email di conferma:</strong> Controlla la tua casella email per le istruzioni di pagamento</p>
                    <p>• <strong>Bonifico:</strong> Effettua il bonifico utilizzando i dati forniti nell'email</p>
                    <p>• <strong>Spedizione:</strong> Il tuo ordine sarà spedito dopo la ricezione del pagamento (1-2 giorni lavorativi)</p>
                @else
                    <p>• <strong>Email di conferma:</strong> Ti abbiamo inviato tutti i dettagli via email</p>
                    <p>• <strong>Elaborazione:</strong> Prepareremo il tuo ordine entro 24 ore</p>
                    <p>• <strong>Spedizione:</strong> Riceverai il tracking non appena spedito</p>
                @endif
                <p>• <strong>Tracking:</strong> Potrai seguire la spedizione dalla tua area personale</p>
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @auth('customer')
                <a href="/account/orders" class="btn-primary">
                    <x-heroicon-m-list-bullet class="h-4 w-4 mr-2" />
                    Visualizza i miei ordini
                </a>
            @endauth
            <a href="/shop" class="btn-secondary">
                <x-heroicon-m-arrow-left class="h-4 w-4 mr-2" />
                Continua a fare shopping
            </a>
        </div>

        <!-- Support info -->
        <div class="pt-6 border-t border-neutral-200">
            <p class="text-sm text-neutral-600">
                Hai domande sul tuo ordine? 
                <a href="/contatti" class="link-teal">Contatta il nostro supporto</a> 
                o scrivi a 
                <a href="mailto:ordini@skintemple.it" class="link-teal">ordini@skintemple.it</a>
            </p>
        </div>
    </div>
</div>