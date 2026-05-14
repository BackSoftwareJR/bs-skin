<?php

namespace App\Livewire\Public\Catalog;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class ProductGrid extends Component
{
    use WithPagination;

    public int $perPage = 24;
    public string $viewMode = 'grid';
    public ?int $limit = null;
    public ?string $categorySlug = null;
    public ?string $brandSlug = null;

    // Filtri (sincronizzati con ProductFilters)
    public array $selectedCategories = [];
    public array $selectedBrands = [];
    public array $selectedAttributeValues = [];
    public ?float $priceMin = null;
    public ?float $priceMax = null;
    public bool $inStockOnly = false;
    public bool $isNewOnly = false;
    public string $search = '';
    public string $sort = 'newest';

    protected $queryString = [
        'search' => ['except' => ''],
        'sort' => ['except' => 'newest'],
        'page' => ['except' => 1],
    ];

    public function mount(?int $limit = null, ?string $sort = 'newest', ?string $categorySlug = null, ?string $brandSlug = null): void
    {
        $this->limit = $limit;
        $this->sort = $sort ?? 'newest';
        $this->categorySlug = $categorySlug;
        $this->brandSlug = $brandSlug;
    }

    #[On('filters-updated')]
    public function updateFilters(array $filters): void
    {
        $this->selectedCategories = $filters['selectedCategories'] ?? [];
        $this->selectedBrands = $filters['selectedBrands'] ?? [];
        $this->selectedAttributeValues = $filters['selectedAttributeValues'] ?? [];
        $this->priceMin = $filters['priceMin'] ?? null;
        $this->priceMax = $filters['priceMax'] ?? null;
        $this->inStockOnly = $filters['inStockOnly'] ?? false;
        $this->isNewOnly = $filters['isNewOnly'] ?? false;
        $this->search = $filters['search'] ?? '';
        $this->sort = $filters['sort'] ?? 'newest';

        // Reset pagination quando i filtri cambiano
        $this->resetPage();
    }

    public function loadMore(): void
    {
        $this->perPage += 24;
    }

    protected function getProductsQuery()
    {
        $query = Product::query()
            ->with(['brand', 'categories', 'media', 'variants.inventory'])
            ->where('published', true);

        // Ricerca testuale
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro categorie
        if (!empty($this->selectedCategories)) {
            $query->whereHas('categories', function ($q) {
                $q->whereIn('categories.id', $this->selectedCategories);
            });
        }

        // Filtro brand
        if (!empty($this->selectedBrands)) {
            $query->whereIn('brand_id', $this->selectedBrands);
        }

        // Filtro prezzo
        if ($this->priceMin !== null) {
            $query->where('price', '>=', $this->priceMin);
        }
        if ($this->priceMax !== null) {
            $query->where('price', '<=', $this->priceMax);
        }

        // Filtro stock
        if ($this->inStockOnly) {
            $query->whereHas('variants', function ($q) {
                $q->whereHas('inventory', function ($q) {
                    $q->where('quantity', '>', 0);
                });
            });
        }

        // Filtro novità
        if ($this->isNewOnly) {
            $query->where('is_new', true);
        }

        // Ordinamento
        switch ($this->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'bestseller':
                // TODO: implementare logica bestseller quando avremo le vendite
                $query->orderBy('created_at', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query;
    }

    public function render(): View
    {
        $query = $this->getProductsQuery();

        if ($this->limit) {
            // Per homepage o sezioni specifiche
            $products = $query->limit($this->limit)->get();
            $pagination = null;
        } else {
            // Per pagina shop con paginazione
            $products = $query->paginate($this->perPage, pageName: 'page');
            $pagination = $products;
        }

        return view('livewire.public.catalog.product-grid', [
            'products' => $products,
            'pagination' => $pagination,
        ]);
    }
}