<div x-data="{ open: @entangle('isOpen') }"
     x-trap.noscroll="open"
     @cart-open.window="open = true"
     @open-cart-drawer.window="$wire.openDrawer()">

    {{-- Backdrop --}}
    <div x-show="open"
         style="display:none"
         @click="$wire.close()"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-brand-ink/40 backdrop-blur-sm z-40"
         aria-hidden="true"></div>

    {{-- Drawer slide-over da destra --}}
    <div x-show="open"
         style="display:none"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed right-0 top-0 h-full w-full sm:w-96 bg-white z-50 shadow-2xl flex flex-col transform"
         role="dialog"
         aria-modal="true"
         aria-label="Carrello">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-100">
            <h2 class="text-lg font-semibold text-neutral-900">
                Il tuo carrello
                @if(count($items) > 0)
                    <span class="inline-flex items-center justify-center w-6 h-6 bg-brand-primary text-white text-xs font-semibold rounded-full ml-2">
                        {{ $count }}
                    </span>
                @endif
            </h2>
            <button @click="$wire.close()"
                    aria-label="Chiudi carrello"
                    class="p-2 text-neutral-500 hover:text-neutral-900 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Body scrollabile --}}
        <div class="flex-1 overflow-y-auto px-6 py-4">
            @if(empty($items))
                {{-- Stato vuoto --}}
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <svg class="h-16 w-16 text-neutral-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-neutral-900 mb-1">Il carrello è vuoto</h3>
                    <p class="text-sm text-neutral-500 mb-6">Scopri i nostri prodotti e aggiungi qualcosa al carrello.</p>
                    <a href="/shop"
                       @click="$wire.close()"
                       class="btn-primary">
                        Vai allo shop
                    </a>
                </div>
            @else
                {{-- Lista items --}}
                <div class="space-y-4">
                    @foreach($items as $item)
                        <div class="flex gap-4 p-4 bg-neutral-50 rounded-xl" wire:key="drawer-item-{{ $item['product_id'] }}">

                            {{-- Immagine prodotto --}}
                            <a href="/prodotti/{{ $item['slug'] }}" class="flex-shrink-0">
                                <div class="w-16 h-16 bg-white rounded-lg overflow-hidden">
                                    @if(!empty($item['image']))
                                        <img src="{{ $item['image'] }}"
                                             alt="{{ $item['name'] }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-neutral-100 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </a>

                            {{-- Info prodotto --}}
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-neutral-900 line-clamp-2 leading-snug">
                                    {{ $item['name'] }}
                                </h4>
                                @if(!empty($item['sku']))
                                    <p class="text-xs text-neutral-400 mt-0.5">SKU: {{ $item['sku'] }}</p>
                                @endif

                                <div class="flex items-center justify-between mt-3">
                                    {{-- Quantity stepper --}}
                                    <div class="flex items-center border border-neutral-200 rounded-lg overflow-hidden">
                                        <button wire:click="updateQuantity({{ $item['product_id'] }}, {{ $item['quantity'] - 1 }})"
                                                wire:loading.attr="disabled"
                                                class="px-2 py-1.5 hover:bg-neutral-100 transition-colors text-neutral-600 disabled:opacity-40">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <span class="px-3 py-1 text-sm font-semibold text-neutral-900 min-w-[2rem] text-center border-x border-neutral-200">
                                            {{ $item['quantity'] }}
                                        </span>
                                        <button wire:click="updateQuantity({{ $item['product_id'] }}, {{ $item['quantity'] + 1 }})"
                                                wire:loading.attr="disabled"
                                                class="px-2 py-1.5 hover:bg-neutral-100 transition-colors text-neutral-600 disabled:opacity-40">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Prezzo riga --}}
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-neutral-900">
                                            €{{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                                        </p>
                                        @if($item['quantity'] > 1)
                                            <p class="text-xs text-neutral-400">
                                                €{{ number_format($item['price'], 2, ',', '.') }} cad.
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Rimuovi --}}
                                <button wire:click="removeItem({{ $item['product_id'] }})"
                                        class="text-xs text-red-500 hover:text-red-700 hover:underline mt-2 transition-colors">
                                    × Rimuovi
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Coupon section --}}
                <div class="mt-6 pt-4 border-t border-neutral-200">
                    @if(!$couponApplied)
                        <div class="space-y-2">
                            <label for="drawer-coupon" class="block text-sm font-medium text-neutral-700">
                                Codice sconto
                            </label>
                            <div class="flex gap-2">
                                <input type="text"
                                       id="drawer-coupon"
                                       wire:model="couponCode"
                                       wire:keydown.enter="applyCoupon"
                                       placeholder="Inserisci codice"
                                       class="flex-1 rounded-lg border border-neutral-300 px-3 py-2 text-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none transition-all">
                                <button wire:click="applyCoupon"
                                        class="px-4 py-2 bg-brand-primary text-white text-sm font-semibold rounded-lg hover:bg-teal-700 transition-colors">
                                    Applica
                                </button>
                            </div>
                            @if($couponMessage && !$couponApplied)
                                <p class="text-xs text-red-500">{{ $couponMessage }}</p>
                            @endif
                        </div>
                    @else
                        <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm text-green-700 font-medium">{{ $couponMessage }}</p>
                            <button wire:click="removeCoupon"
                                    class="text-xs text-green-700 hover:underline">
                                Rimuovi
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Footer con riepilogo e CTA --}}
        @if(!empty($items))
            <div class="border-t border-neutral-100 px-6 py-5 space-y-4 bg-white">
                {{-- Totali --}}
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-neutral-500">Subtotale</span>
                        <span class="font-medium text-neutral-900">€{{ number_format($subtotal, 2, ',', '.') }}</span>
                    </div>
                    @if($discountTotal > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Sconto coupon</span>
                            <span class="font-semibold">-€{{ number_format($discountTotal, 2, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between border-t border-neutral-200 pt-2 font-bold text-base text-neutral-900">
                        <span>Totale</span>
                        <span>€{{ number_format($total, 2, ',', '.') }}</span>
                    </div>
                    <p class="text-xs text-neutral-400">Spedizione calcolata al checkout</p>
                </div>

                {{-- CTA buttons --}}
                <div class="space-y-2">
                    <a href="/checkout"
                       class="block w-full text-center bg-brand-primary text-white font-semibold py-3 px-6 rounded-xl hover:bg-teal-700 transition-colors">
                        Checkout
                    </a>
                    <a href="/carrello"
                       @click="$wire.close()"
                       class="block w-full text-center border border-brand-primary text-brand-primary font-medium py-3 px-6 rounded-xl hover:bg-brand-primary/5 transition-colors">
                        Vai al carrello
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
