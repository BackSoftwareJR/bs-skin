<div x-data="{ open: @entangle('isOpen') }"
     x-trap.noscroll="open"
     @open-search.window="open = true">
    <!-- Overlay fullscreen -->
    <div x-show="open"
         x-transition:enter="transition ease-apple duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-apple duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="$wire.close()"
         class="fixed inset-0 z-50 bg-surface/95 backdrop-blur-xl"
         role="dialog" aria-modal="true" aria-label="Ricerca">

        <div class="mx-auto max-w-2xl px-4 pt-20 sm:pt-32">
            <!-- Input ricerca prominente -->
            <div class="relative mb-8">
                <x-heroicon-o-magnifying-glass class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-neutral-400" />
                <input type="search" 
                       wire:model.live.debounce.300ms="query"
                       placeholder="Cerca prodotti, categorie, articoli..."
                       class="w-full rounded-2xl border-0 bg-neutral-100 py-4 pl-12 pr-4 text-lg text-neutral-900 placeholder:text-neutral-400 focus:bg-surface focus:ring-2 focus:ring-brand-primary/20 transition-all"
                       autofocus>
                
                <!-- Loading indicator -->
                <div wire:loading wire:target="query" class="absolute right-4 top-1/2 -translate-y-1/2">
                    <div class="w-5 h-5 border-2 border-neutral-300 border-t-brand-primary rounded-full animate-spin"></div>
                </div>
            </div>

            <!-- Risultati -->
            <div class="space-y-8 max-h-[60vh] overflow-y-auto">
                @if($hasSearched && $query !== '')
                    @if($productResults->isNotEmpty())
                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-4">
                                Prodotti ({{ $productResults->count() }})
                            </h3>
                            <div class="space-y-3">
                                @foreach($productResults as $product)
                                    <a href="/prodotti/{{ $product->slug }}" 
                                       @click="$wire.close()"
                                       class="flex items-center gap-4 p-3 rounded-xl hover:bg-neutral-100 transition-colors group">
                                        <!-- Immagine prodotto -->
                                        <div class="w-12 h-12 bg-neutral-100 rounded-lg overflow-hidden flex-shrink-0">
                                            @if($product->featured_image)
                                                <img src="{{ $product->featured_image }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-neutral-200 flex items-center justify-center">
                                                    <x-heroicon-o-photo class="h-4 w-4 text-neutral-400" />
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Info prodotto -->
                                        <div class="flex-1 min-w-0">
                                            @if($product->brand)
                                                <p class="text-xs font-medium uppercase tracking-wide text-neutral-500 mb-0.5">
                                                    {{ $product->brand->name }}
                                                </p>
                                            @endif
                                            <h4 class="text-sm font-medium text-neutral-900 line-clamp-1 group-hover:text-brand-primary transition-colors">
                                                {{ $product->name }}
                                            </h4>
                                        </div>

                                        <!-- Prezzo -->
                                        <div class="text-right">
                                            <p class="text-sm font-semibold text-neutral-900">
                                                €{{ number_format($product->price, 2, ',', '.') }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            
                            @if($productResults->count() >= 6)
                                <div class="mt-4">
                                    <button wire:click="goToFullResults" 
                                            class="text-sm text-brand-primary hover:text-brand-primary-hover font-medium transition-colors">
                                        Vedi tutti i risultati per "{{ $query }}" →
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($pageResults->isNotEmpty())
                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-4">
                                Pagine e articoli ({{ $pageResults->count() }})
                            </h3>
                            <div class="space-y-3">
                                @foreach($pageResults as $page)
                                    <a href="/{{ $page->slug }}" 
                                       @click="$wire.close()"
                                       class="block p-3 rounded-xl hover:bg-neutral-100 transition-colors group">
                                        <div class="flex items-center gap-2 mb-1">
                                            <x-heroicon-o-document-text class="h-4 w-4 text-neutral-400" />
                                            <span class="text-xs text-neutral-500 uppercase tracking-wide">
                                                {{ $page->type === 'blog' ? 'Articolo' : 'Pagina' }}
                                            </span>
                                        </div>
                                        <h4 class="text-sm font-medium text-neutral-900 group-hover:text-brand-primary transition-colors line-clamp-1">
                                            {{ $page->title }}
                                        </h4>
                                        @if($page->excerpt)
                                            <p class="text-xs text-neutral-500 line-clamp-2 mt-1">
                                                {{ $page->excerpt }}
                                            </p>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($productResults->isEmpty() && $pageResults->isEmpty())
                        <!-- Nessun risultato -->
                        <div class="text-center py-8">
                            <x-heroicon-o-magnifying-glass class="h-12 w-12 text-neutral-300 mx-auto mb-4" />
                            <h3 class="text-lg font-semibold text-neutral-900 mb-2">
                                Nessun risultato per "{{ $query }}"
                            </h3>
                            <p class="text-neutral-500 mb-4">
                                Prova con parole chiave diverse o più generiche.
                            </p>
                            <button wire:click="$set('query', '')" 
                                    class="text-brand-primary hover:text-brand-primary-hover font-medium">
                                Cancella ricerca
                            </button>
                        </div>
                    @endif

                @elseif(!$hasSearched && count($recentSearches) > 0)
                    <!-- Ricerche recenti -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xs font-semibold uppercase tracking-widest text-neutral-500">
                                Ricerche recenti
                            </h3>
                            <button wire:click="clearRecent" 
                                    class="text-xs text-neutral-400 hover:text-neutral-600 transition-colors">
                                Cancella tutto
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($recentSearches as $search)
                                <button wire:click="selectRecent('{{ $search }}')"
                                        class="inline-flex items-center gap-2 px-3 py-2 bg-neutral-100 rounded-lg text-sm text-neutral-700 hover:bg-neutral-200 transition-colors">
                                    <x-heroicon-m-clock class="h-3 w-3" />
                                    {{ $search }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                @elseif(!$hasSearched)
                    <!-- Stato iniziale -->
                    <div class="text-center py-8">
                        <x-heroicon-o-magnifying-glass class="h-12 w-12 text-neutral-300 mx-auto mb-4" />
                        <h3 class="text-lg font-semibold text-neutral-900 mb-2">
                            Cosa stai cercando?
                        </h3>
                        <p class="text-neutral-500">
                            Inizia a digitare per cercare prodotti, categorie e contenuti.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pulsante chiudi -->
        <button @click="$wire.close()" 
                aria-label="Chiudi ricerca"
                class="absolute top-4 right-4 p-2 text-neutral-500 hover:text-neutral-900 transition-colors">
            <x-heroicon-o-x-mark class="h-6 w-6" />
        </button>

        <!-- Hint keyboard -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2">
            <p class="text-xs text-neutral-400">
                Premi <kbd class="px-1 py-0.5 bg-neutral-200 rounded text-xs">ESC</kbd> per chiudere
            </p>
        </div>
    </div>
</div>