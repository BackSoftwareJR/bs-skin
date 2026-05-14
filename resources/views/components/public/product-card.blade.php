@props(['product', 'showPrice' => true])

@php
    $nameClass = $product->type === 'device'
        ? 'text-sm font-semibold uppercase tracking-wide text-neutral-900'
        : 'text-sm font-medium text-neutral-900';
@endphp

<article class="group relative flex flex-col rounded-2xl bg-white p-4 sm:p-5 shadow-soft-md hover:shadow-soft-lg hover:-translate-y-0.5 transition-all duration-300 ease-out">
    <!-- Immagine quadrata centrata -->
    <a href="/prodotti/{{ $product->slug }}" class="relative aspect-square overflow-hidden rounded-xl bg-neutral-50 mb-4">
        @if($product->featured_image)
            <img src="{{ $product->featured_image }}" 
                 alt="{{ $product->name }}"
                 class="h-full w-full object-contain p-4 transition-transform duration-500 ease-out group-hover:scale-105"
                 loading="lazy" decoding="async">
        @else
            <div class="h-full w-full flex items-center justify-center text-neutral-400">
                <x-heroicon-o-photo class="h-16 w-16" />
            </div>
        @endif

        <!-- Badge -->
        @if($product->is_new)
            <span class="absolute top-3 left-3">
                <x-public.badge variant="accent" size="sm">Novità</x-public.badge>
            </span>
        @elseif($product->is_promo)
            <span class="absolute top-3 left-3">
                <x-public.badge variant="danger" size="sm">{{ $product->promo_label ?? 'Promo' }}</x-public.badge>
            </span>
        @endif
        
        @if($product->is_b2b)
            <span class="absolute top-3 right-3">
                <x-public.badge variant="primary-soft" size="sm">B2B</x-public.badge>
            </span>
        @endif

        <!-- Wishlist -->
        <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
            <livewire:public.cart.wishlist-toggle :product-id="$product->id" />
        </div>
    </a>

    <!-- Info -->
    <div class="flex flex-col gap-1 flex-1">
        @if($product->brand)
            <p class="text-2xs font-medium uppercase tracking-widest text-neutral-500">
                {{ $product->brand->name }}
            </p>
        @endif
        
        <h3 class="{{ $nameClass }} leading-snug line-clamp-2">
            <a href="/prodotti/{{ $product->slug }}" class="hover:text-brand-primary transition-colors">
                {{ $product->name }}
            </a>
        </h3>
        
        @if($product->sku)
            <p class="font-mono text-xs text-neutral-500 line-clamp-1">{{ $product->sku }}</p>
        @endif

        @if($showPrice)
            <div class="flex items-center gap-2 mt-1">
                @if($product->compare_at_price)
                    <span class="text-sm text-neutral-500 line-through">€{{ number_format($product->compare_at_price, 2, ',', '.') }}</span>
                    <span class="text-base font-semibold text-brand-primary">€{{ number_format($product->price, 2, ',', '.') }}</span>
                @else
                    <span class="text-base font-semibold text-neutral-900">€{{ number_format($product->price, 2, ',', '.') }}</span>
                @endif
            </div>
        @endif

        <a href="/prodotti/{{ $product->slug }}"
           class="mt-3 inline-flex items-center gap-1 text-sm font-medium text-brand-primary hover:text-brand-primary-hover transition-colors">
            Scopri di più
            <x-heroicon-m-chevron-right class="h-3.5 w-3.5" />
        </a>
    </div>
</article>