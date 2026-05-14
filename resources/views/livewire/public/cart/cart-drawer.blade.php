<div x-data="{ open: @entangle('isOpen') }" x-trap.noscroll="open">
    <!-- Backdrop -->
    <div x-show="open" 
         @click="$wire.close()" 
         x-transition:enter="transition ease-apple duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-apple duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-brand-ink/40 backdrop-blur-sm z-40"
         aria-hidden="true"></div>

    <!-- Drawer Desktop: slide da destra -->
    <div x-show="open"
         x-transition:enter="transition ease-apple duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-apple duration-250"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed right-0 top-0 h-full w-full sm:w-96 bg-white z-50 shadow-soft-xl flex flex-col"
         role="dialog" 
         aria-modal="true" 
         aria-label="Carrello">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-100">
            <h2 class="text-lg font-semibold text-neutral-900">
                Carrello 
                @if($items->count() > 0)
                    <span class="inline-flex items-center justify-center w-6 h-6 bg-brand-primary text-white text-xs font-semibold rounded-full ml-2">
                        {{ $items->count() }}
                    </span>
                @endif
            </h2>
            <button @click="$wire.close()" 
                    aria-label="Chiudi carrello" 
                    class="p-2 text-neutral-500 hover:text-neutral-900 transition-colors">
                <x-heroicon-o-x-mark class="h-5 w-5" />
            </button>
        </div>

        <!-- Body scrollabile -->
        <div class="flex-1 overflow-y-auto px-6 py-4">
            @if($items->isEmpty())
                <!-- Stato vuoto -->
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <x-heroicon-o-shopping-bag class="h-16 w-16 text-neutral-300 mb-4" />
                    <h3 class="text-lg font-semibold text-neutral-900 mb-1">Il tuo carrello è vuoto</h3>
                    <p class="text-sm text-neutral-500 mb-6">Scopri i nostri prodotti e aggiungi qualcosa al carrello.</p>
                    <a href="/shop" 
                       @click="$wire.close()"
                       class="btn-primary">
                        Vai allo shop
                    </a>
                </div>
            @else
                <!-- Lista items -->
                <div class="space-y-4">
                    @foreach($items as $item)
                        <div class="flex gap-4 p-4 bg-neutral-50 rounded-xl">
                            <!-- Immagine prodotto -->
                            <div class="w-16 h-16 bg-white rounded-lg overflow-hidden flex-shrink-0">
                                @if($item->product->featured_image)
                                    <img src="{{ $item->product->featured_image }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-neutral-100 flex items-center justify-center">
                                        <x-heroicon-o-photo class="h-6 w-6 text-neutral-400" />
                                    </div>
                                @endif
                            </div>

                            <!-- Info prodotto -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-neutral-900 line-clamp-1">
                                    {{ $item->product->name }}
                                </h4>
                                @if($item->variant)
                                    <p class="text-xs text-neutral-500 mt-0.5">
                                        {{ $item->variant->name }}
                                    </p>
                                @endif
                                <div class="flex items-center justify-between mt-2">
                                    <!-- Quantity stepper -->
                                    <div class="flex items-center border border-neutral-300 rounded-lg">
                                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                class="p-1 hover:bg-neutral-100 transition-colors">
                                            <x-heroicon-m-minus class="h-4 w-4 text-neutral-600" />
                                        </button>
                                        <span class="px-3 py-1 text-sm font-medium text-neutral-900">
                                            {{ $item->quantity }}
                                        </span>
                                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                class="p-1 hover:bg-neutral-100 transition-colors">
                                            <x-heroicon-m-plus class="h-4 w-4 text-neutral-600" />
                                        </button>
                                    </div>

                                    <!-- Prezzo -->
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-neutral-900">
                                            €{{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                                        </p>
                                        @if($item->quantity > 1)
                                            <p class="text-xs text-neutral-500">
                                                €{{ number_format($item->price, 2, ',', '.') }} cad.
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Rimuovi -->
                                <button wire:click="removeItem({{ $item->id }})" 
                                        class="text-xs text-danger hover:underline mt-2">
                                    Rimuovi
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Coupon section -->
                <div class="mt-6 pt-4 border-t border-neutral-200">
                    @if(!$couponApplied)
                        <div class="space-y-2">
                            <label for="coupon" class="block text-sm font-medium text-neutral-700">
                                Codice sconto
                            </label>
                            <div class="flex gap-2">
                                <input type="text" 
                                       id="coupon"
                                       wire:model="couponCode" 
                                       placeholder="Inserisci codice"
                                       class="flex-1 rounded-lg border-neutral-300 text-sm focus:border-brand-primary focus:ring-brand-primary/20">
                                <button wire:click="applyCoupon" 
                                        class="px-4 py-2 bg-brand-primary text-white text-sm font-medium rounded-lg hover:bg-brand-primary-hover transition-colors">
                                    Applica
                                </button>
                            </div>
                            @if($couponMessage && !$couponApplied)
                                <p class="text-xs text-danger">{{ $couponMessage }}</p>
                            @endif
                        </div>
                    @else
                        <div class="flex items-center justify-between p-3 bg-success-bg rounded-lg">
                            <p class="text-sm text-success font-medium">{{ $couponMessage }}</p>
                            <button wire:click="removeCoupon" 
                                    class="text-xs text-success hover:underline">
                                Rimuovi
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Footer con riepilogo e CTA -->
        @if(!$items->isEmpty())
            <div class="border-t border-neutral-100 px-6 py-4 space-y-4 bg-white">
                <!-- Riepilogo -->
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-neutral-600">Subtotale</span>
                        <span class="font-medium">€{{ number_format($subtotal, 2, ',', '.') }}</span>
                    </div>
                    @if($discountTotal > 0)
                        <div class="flex justify-between text-success">
                            <span>Sconto</span>
                            <span class="font-medium">-€{{ number_format($discountTotal, 2, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between border-t border-neutral-200 pt-2 font-semibold text-base">
                        <span>Totale</span>
                        <span>€{{ number_format($total, 2, ',', '.') }}</span>
                    </div>
                    <p class="text-xs text-neutral-500">Spedizione calcolata al checkout</p>
                </div>

                <!-- CTA -->
                <button wire:click="goToCheckout" 
                        class="w-full btn-primary text-center">
                    Procedi al checkout
                </button>
            </div>
        @endif
    </div>
</div>