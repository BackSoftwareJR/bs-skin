<div>
    @if($pagination && $products->count() > 0)
        <!-- Header con conteggio e ordinamento -->
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-neutral-500">
                Mostrando 
                <span class="font-medium text-neutral-900">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</span> 
                di 
                <span class="font-medium text-neutral-900">{{ $products->total() }}</span> 
                risultati
            </p>
        </div>
    @endif

    @if($products->count() > 0)
        <!-- Griglia prodotti -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @if($limit)
                @foreach($products as $product)
                    <x-public.product-card :product="$product" />
                @endforeach
            @else
                @foreach($products as $product)
                    <x-public.product-card :product="$product" />
                @endforeach
            @endif
        </div>

        @if(!$limit && $pagination)
            <!-- Paginazione -->
            <div class="mt-12 flex justify-center">
                {{ $products->links() }}
            </div>
        @endif

        @if($limit && $products->count() >= $limit)
            <!-- Link "Vedi tutti" per sezioni homepage -->
            <div class="mt-8 text-center">
                <a href="/shop" 
                   class="inline-flex items-center gap-2 text-brand-primary hover:text-brand-primary-hover font-medium transition-colors">
                    Vedi tutti i prodotti
                    <x-heroicon-m-arrow-right class="h-4 w-4" />
                </a>
            </div>
        @endif
    @else
        <!-- Stato vuoto -->
        <div class="text-center py-16">
            <x-heroicon-o-magnifying-glass class="h-16 w-16 text-neutral-300 mx-auto mb-4" />
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">Nessun prodotto trovato</h3>
            <p class="text-neutral-500 mb-6">
                @if($search)
                    Non abbiamo trovato prodotti per "<strong>{{ $search }}</strong>".
                @else
                    Non ci sono prodotti che corrispondono ai filtri selezionati.
                @endif
            </p>
            <div class="space-x-4">
                @if($search)
                    <button wire:click="$set('search', '')" 
                            class="btn-primary">
                        Rimuovi ricerca
                    </button>
                @endif
                @if(count($this->selectedCategories) > 0 || count($this->selectedBrands) > 0 || $this->priceMin || $this->priceMax || $this->inStockOnly || $this->isNewOnly)
                    <button wire:click="$dispatch('filters-reset')" 
                            class="btn-secondary">
                        Rimuovi filtri
                    </button>
                @endif
                <a href="/shop" class="btn-secondary">
                    Vedi tutti i prodotti
                </a>
            </div>
        </div>
    @endif

    <!-- Loading skeleton -->
    <div wire:loading.delay class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 mt-6">
        @for($i = 0; $i < 12; $i++)
            <div class="animate-pulse">
                <div class="aspect-square bg-neutral-200 rounded-2xl mb-4"></div>
                <div class="space-y-2">
                    <div class="h-4 bg-neutral-200 rounded w-3/4"></div>
                    <div class="h-3 bg-neutral-200 rounded w-1/2"></div>
                    <div class="h-4 bg-neutral-200 rounded w-1/4"></div>
                </div>
            </div>
        @endfor
    </div>
</div>