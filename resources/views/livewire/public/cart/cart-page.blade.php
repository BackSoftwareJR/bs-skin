<div>
    @if($items->isEmpty())
        {{-- Empty cart --}}
        <div class="text-center py-20">
            <div class="w-20 h-20 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293A1 1 0 004 16v0a1 1 0 001 1h10"></path>
                </svg>
            </div>
            <h1 class="font-display text-3xl text-brand-ink mb-3">Il tuo carrello è vuoto</h1>
            <p class="text-brand-ink-soft mb-8">Aggiungi prodotti dal nostro shop per iniziare il tuo ordine.</p>
            <a href="{{ route('shop.index') }}" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                Vai allo shop
            </a>
        </div>

    @else
        <h1 class="font-display text-3xl text-brand-ink mb-8">
            Il tuo carrello
            <span class="text-lg font-normal text-brand-ink-soft ml-2">({{ $items->count() }} {{ $items->count() === 1 ? 'articolo' : 'articoli' }})</span>
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            {{-- Cart items --}}
            <div class="lg:col-span-2 space-y-4">
                @foreach($items as $item)
                    <div class="bg-white rounded-2xl shadow-soft-sm p-5 flex gap-4 group" wire:key="item-{{ $item->id }}">
                        {{-- Product image --}}
                        <a href="{{ route('product.show', $item->product->slug) }}" class="flex-shrink-0">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-neutral-50 rounded-xl overflow-hidden">
                                @if($item->product->featured_image)
                                    <img src="{{ $item->product->featured_image }}"
                                         alt="{{ $item->product->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </a>

                        {{-- Product info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    @if($item->product->brand)
                                        <p class="text-xs font-medium uppercase tracking-wide text-brand-ink-soft mb-0.5">{{ $item->product->brand->name }}</p>
                                    @endif
                                    <h3 class="font-semibold text-brand-ink leading-snug">
                                        <a href="{{ route('product.show', $item->product->slug) }}" class="hover:text-brand-primary transition-colors">
                                            {{ $item->product->name }}
                                        </a>
                                    </h3>
                                    @if($item->variant)
                                        <p class="text-xs text-brand-ink-soft mt-0.5">{{ $item->variant->name }}</p>
                                    @endif
                                </div>
                                <button wire:click="removeItem({{ $item->id }})"
                                        wire:confirm="Rimuovere questo articolo dal carrello?"
                                        aria-label="Rimuovi"
                                        class="p-1.5 text-neutral-300 hover:text-danger transition-colors flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                {{-- Quantity stepper --}}
                                <div class="flex items-center border border-brand-border rounded-xl overflow-hidden">
                                    <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                            class="px-3 py-2 text-brand-ink-soft hover:bg-neutral-50 hover:text-brand-primary transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <span class="px-4 py-2 text-sm font-semibold text-brand-ink min-w-[3rem] text-center border-x border-brand-border">
                                        {{ $item->quantity }}
                                    </span>
                                    <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                            class="px-3 py-2 text-brand-ink-soft hover:bg-neutral-50 hover:text-brand-primary transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </div>

                                {{-- Price --}}
                                <div class="text-right">
                                    <p class="font-semibold text-brand-ink">€{{ number_format($item->price * $item->quantity, 2, ',', '.') }}</p>
                                    @if($item->quantity > 1)
                                        <p class="text-xs text-brand-ink-soft">€{{ number_format($item->price, 2, ',', '.') }} cad.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Continue shopping --}}
                <div class="pt-2">
                    <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 text-sm text-brand-primary hover:text-brand-primary/80 font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Continua a fare shopping
                    </a>
                </div>
            </div>

            {{-- Order summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-soft-sm p-6 sticky top-24 space-y-6">
                    <h2 class="font-semibold text-brand-ink text-lg">Riepilogo ordine</h2>

                    {{-- Coupon --}}
                    <div>
                        @if(!$couponApplied)
                            <label class="block text-sm font-medium text-brand-ink mb-2">Codice sconto</label>
                            <div class="flex gap-2">
                                <input type="text"
                                       wire:model="couponCode"
                                       wire:keydown.enter="applyCoupon"
                                       placeholder="Inserisci codice"
                                       class="flex-1 rounded-xl border border-brand-border px-3 py-2.5 text-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none transition-all">
                                <button wire:click="applyCoupon"
                                        class="px-4 py-2.5 bg-brand-primary text-white text-sm font-semibold rounded-xl hover:bg-brand-primary/90 transition-colors whitespace-nowrap">
                                    Applica
                                </button>
                            </div>
                            @if($couponMessage && !$couponApplied)
                                <p class="text-xs text-danger mt-2">{{ $couponMessage }}</p>
                            @endif
                        @else
                            <div class="flex items-center justify-between bg-success/10 border border-success/20 rounded-xl px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm text-success font-medium">{{ $couponMessage }}</span>
                                </div>
                                <button wire:click="removeCoupon" class="text-xs text-success hover:underline">Rimuovi</button>
                            </div>
                        @endif
                    </div>

                    {{-- Totals --}}
                    <div class="space-y-3 text-sm border-t border-neutral-100 pt-4">
                        <div class="flex justify-between">
                            <span class="text-brand-ink-soft">Subtotale</span>
                            <span class="font-medium text-brand-ink">€{{ number_format($subtotal, 2, ',', '.') }}</span>
                        </div>

                        @if($discountTotal > 0)
                            <div class="flex justify-between text-success">
                                <span>Sconto applicato</span>
                                <span class="font-semibold">-€{{ number_format($discountTotal, 2, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-brand-ink-soft">
                            <span>Spedizione</span>
                            <span class="text-xs italic">Calcolata al checkout</span>
                        </div>

                        <div class="flex justify-between border-t border-neutral-200 pt-3 font-bold text-base text-brand-ink">
                            <span>Totale</span>
                            <span>€{{ number_format($total, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Checkout CTA --}}
                    <a href="{{ route('checkout.index') }}"
                       class="block w-full text-center bg-brand-primary text-white font-semibold py-4 px-6 rounded-xl hover:bg-brand-primary/90 transition-colors">
                        Procedi al checkout
                        <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    {{-- Trust badges --}}
                    <div class="grid grid-cols-3 gap-3 pt-2 border-t border-neutral-100">
                        <div class="text-center">
                            <svg class="w-5 h-5 text-brand-primary mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <p class="text-2xs text-brand-ink-soft leading-tight">Pagamento sicuro</p>
                        </div>
                        <div class="text-center">
                            <svg class="w-5 h-5 text-brand-primary mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path>
                            </svg>
                            <p class="text-2xs text-brand-ink-soft leading-tight">Reso gratuito</p>
                        </div>
                        <div class="text-center">
                            <svg class="w-5 h-5 text-brand-primary mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-2xs text-brand-ink-soft leading-tight">Supporto 24/7</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
