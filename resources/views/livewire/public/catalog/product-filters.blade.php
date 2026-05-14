<div>
    <!-- Filtri attivi -->
    @if(count($this->activeFilters) > 0)
        <div class="mb-6 pb-4 border-b border-neutral-200">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="text-sm font-medium text-neutral-700">Filtri attivi:</span>
                @foreach($this->activeFilters as $filter)
                    <span class="inline-flex items-center gap-1 rounded-full bg-neutral-100 px-3 py-1 text-sm text-neutral-700">
                        {{ $filter['label'] }}
                        <button type="button" 
                                wire:click="removeFilter('{{ $filter['key'] }}', '{{ $filter['value'] }}')"
                                aria-label="Rimuovi filtro" 
                                class="ml-0.5 p-0.5 rounded-full hover:bg-neutral-200 transition-colors">
                            <x-heroicon-m-x-mark class="h-3 w-3" />
                        </button>
                    </span>
                @endforeach
            </div>
            <button wire:click="resetFilters" 
                    class="text-sm link-teal">
                Rimuovi tutti i filtri
            </button>
        </div>
    @endif

    <div class="space-y-6">
        <!-- Categorie -->
        <div class="border-b border-neutral-200 pb-4">
            <h3 class="text-sm font-semibold text-neutral-900 mb-3">Categorie</h3>
            <div class="space-y-2">
                @foreach($this->availableCategories as $category)
                    <label class="flex items-center justify-between cursor-pointer group">
                        <span class="flex items-center gap-2">
                            <input type="checkbox" 
                                   value="{{ $category->id }}"
                                   wire:model.live="selectedCategories"
                                   class="h-4 w-4 rounded-md border-neutral-300 text-brand-primary focus:ring-2 focus:ring-brand-primary/20">
                            <span class="text-sm text-neutral-700 group-hover:text-neutral-900 transition-colors">
                                {{ $category->name }}
                            </span>
                        </span>
                        <span class="text-xs text-neutral-400">{{ $category->products_count }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Prezzo -->
        <div class="border-b border-neutral-200 pb-4">
            <h3 class="text-sm font-semibold text-neutral-900 mb-3">Prezzo</h3>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label for="price-min" class="block text-xs text-neutral-500 mb-1">Da</label>
                    <input type="number" 
                           id="price-min"
                           wire:model.live.debounce.500ms="priceMin"
                           placeholder="€0"
                           min="0"
                           step="0.01"
                           class="w-full rounded-lg border-neutral-300 text-sm focus:border-brand-primary focus:ring-brand-primary/20">
                </div>
                <div>
                    <label for="price-max" class="block text-xs text-neutral-500 mb-1">A</label>
                    <input type="number" 
                           id="price-max"
                           wire:model.live.debounce.500ms="priceMax"
                           placeholder="€999"
                           min="0"
                           step="0.01"
                           class="w-full rounded-lg border-neutral-300 text-sm focus:border-brand-primary focus:ring-brand-primary/20">
                </div>
            </div>
        </div>

        <!-- Marchi -->
        <div class="border-b border-neutral-200 pb-4">
            <h3 class="text-sm font-semibold text-neutral-900 mb-3">Marchi</h3>
            <div class="space-y-2 max-h-48 overflow-y-auto">
                @foreach($this->availableBrands as $brand)
                    <label class="flex items-center justify-between cursor-pointer group">
                        <span class="flex items-center gap-2">
                            <input type="checkbox" 
                                   value="{{ $brand->id }}"
                                   wire:model.live="selectedBrands"
                                   class="h-4 w-4 rounded-md border-neutral-300 text-brand-primary focus:ring-2 focus:ring-brand-primary/20">
                            <span class="text-sm text-neutral-700 group-hover:text-neutral-900 transition-colors">
                                {{ $brand->name }}
                            </span>
                        </span>
                        <span class="text-xs text-neutral-400">{{ $brand->products_count }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Stato -->
        <div class="border-b border-neutral-200 pb-4">
            <h3 class="text-sm font-semibold text-neutral-900 mb-3">Stato</h3>
            <div class="space-y-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" 
                           wire:model.live="inStockOnly"
                           class="h-4 w-4 rounded-md border-neutral-300 text-brand-primary focus:ring-2 focus:ring-brand-primary/20">
                    <span class="text-sm text-neutral-700">Solo disponibili</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" 
                           wire:model.live="isNewOnly"
                           class="h-4 w-4 rounded-md border-neutral-300 text-brand-primary focus:ring-2 focus:ring-brand-primary/20">
                    <span class="text-sm text-neutral-700">Novità</span>
                </label>
            </div>
        </div>

        <!-- Ordinamento -->
        <div>
            <h3 class="text-sm font-semibold text-neutral-900 mb-3">Ordina per</h3>
            <select wire:model.live="sort" 
                    class="w-full rounded-lg border-neutral-300 text-sm focus:border-brand-primary focus:ring-brand-primary/20">
                <option value="newest">Più recenti</option>
                <option value="price_asc">Prezzo crescente</option>
                <option value="price_desc">Prezzo decrescente</option>
                <option value="name_asc">Nome A-Z</option>
                <option value="name_desc">Nome Z-A</option>
                <option value="bestseller">Più venduti</option>
            </select>
        </div>
    </div>
</div>