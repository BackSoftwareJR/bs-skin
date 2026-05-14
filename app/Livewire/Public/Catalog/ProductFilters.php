<?php

namespace App\Livewire\Public\Catalog;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Collection;

class ProductFilters extends Component
{
    public ?string $categorySlug = null;
    public ?string $brandSlug = null;

    #[Url(as: 'search')]
    public string $search = '';

    #[Url(as: 'sort')]
    public string $sort = 'newest';

    #[Url(as: 'price_min')]
    public ?float $priceMin = null;

    #[Url(as: 'price_max')]
    public ?float $priceMax = null;

    #[Url(as: 'categories')]
    public array $selectedCategories = [];

    #[Url(as: 'brands')]
    public array $selectedBrands = [];

    #[Url(as: 'attributes')]
    public array $selectedAttributeValues = [];

    #[Url(as: 'in_stock')]
    public bool $inStockOnly = false;

    #[Url(as: 'is_new')]
    public bool $isNewOnly = false;

    public function mount(?string $categorySlug = null, ?string $brandSlug = null): void
    {
        $this->categorySlug = $categorySlug;
        $this->brandSlug = $brandSlug;

        // Se abbiamo uno slug categoria o brand, aggiungiamo ai filtri attivi
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category && !in_array($category->id, $this->selectedCategories)) {
                $this->selectedCategories[] = $category->id;
            }
        }

        if ($brandSlug) {
            $brand = Brand::where('slug', $brandSlug)->first();
            if ($brand && !in_array($brand->id, $this->selectedBrands)) {
                $this->selectedBrands[] = $brand->id;
            }
        }
    }

    public function updatedSelectedCategories(): void
    {
        $this->emitFiltersChanged();
    }

    public function updatedSelectedBrands(): void
    {
        $this->emitFiltersChanged();
    }

    public function updatedPriceMin(): void
    {
        $this->emitFiltersChanged();
    }

    public function updatedPriceMax(): void
    {
        $this->emitFiltersChanged();
    }

    public function updatedSort(): void
    {
        $this->emitFiltersChanged();
    }

    public function updatedInStockOnly(): void
    {
        $this->emitFiltersChanged();
    }

    public function updatedIsNewOnly(): void
    {
        $this->emitFiltersChanged();
    }

    public function removeFilter(string $key, $value): void
    {
        switch ($key) {
            case 'category':
                $this->selectedCategories = array_diff($this->selectedCategories, [$value]);
                break;
            case 'brand':
                $this->selectedBrands = array_diff($this->selectedBrands, [$value]);
                break;
            case 'price':
                $this->priceMin = null;
                $this->priceMax = null;
                break;
            case 'in_stock':
                $this->inStockOnly = false;
                break;
            case 'is_new':
                $this->isNewOnly = false;
                break;
        }

        $this->emitFiltersChanged();
    }

    public function resetFilters(): void
    {
        $this->selectedCategories = [];
        $this->selectedBrands = [];
        $this->selectedAttributeValues = [];
        $this->priceMin = null;
        $this->priceMax = null;
        $this->inStockOnly = false;
        $this->isNewOnly = false;
        $this->search = '';
        
        $this->emitFiltersChanged();
    }

    public function getAvailableCategoriesProperty(): Collection
    {
        return Category::active()
            ->withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get();
    }

    public function getAvailableBrandsProperty(): Collection
    {
        return Brand::active()
            ->withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get();
    }

    public function getActiveFiltersProperty(): array
    {
        $filters = [];

        // Categorie
        foreach ($this->selectedCategories as $categoryId) {
            $category = Category::find($categoryId);
            if ($category) {
                $filters[] = [
                    'key' => 'category',
                    'value' => $categoryId,
                    'label' => $category->name
                ];
            }
        }

        // Brand
        foreach ($this->selectedBrands as $brandId) {
            $brand = Brand::find($brandId);
            if ($brand) {
                $filters[] = [
                    'key' => 'brand',
                    'value' => $brandId,
                    'label' => $brand->name
                ];
            }
        }

        // Prezzo
        if ($this->priceMin || $this->priceMax) {
            $label = 'Prezzo: ';
            if ($this->priceMin && $this->priceMax) {
                $label .= "€{$this->priceMin} - €{$this->priceMax}";
            } elseif ($this->priceMin) {
                $label .= "da €{$this->priceMin}";
            } else {
                $label .= "fino a €{$this->priceMax}";
            }

            $filters[] = [
                'key' => 'price',
                'value' => 'range',
                'label' => $label
            ];
        }

        // Stock
        if ($this->inStockOnly) {
            $filters[] = [
                'key' => 'in_stock',
                'value' => true,
                'label' => 'Solo disponibili'
            ];
        }

        // Novità
        if ($this->isNewOnly) {
            $filters[] = [
                'key' => 'is_new',
                'value' => true,
                'label' => 'Novità'
            ];
        }

        return $filters;
    }

    protected function emitFiltersChanged(): void
    {
        $this->dispatch('filters-updated', [
            'selectedCategories' => $this->selectedCategories,
            'selectedBrands' => $this->selectedBrands,
            'selectedAttributeValues' => $this->selectedAttributeValues,
            'priceMin' => $this->priceMin,
            'priceMax' => $this->priceMax,
            'inStockOnly' => $this->inStockOnly,
            'isNewOnly' => $this->isNewOnly,
            'search' => $this->search,
            'sort' => $this->sort,
        ]);
    }

    public function render()
    {
        return view('livewire.public.catalog.product-filters');
    }
}