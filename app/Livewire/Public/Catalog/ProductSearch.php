<?php

namespace App\Livewire\Public\Catalog;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Product;
use App\Models\Page;
use Illuminate\Support\Collection;

class ProductSearch extends Component
{
    #[Url(as: 'q')]
    public string $query = '';
    
    public Collection $productResults;
    public Collection $pageResults;
    public array $recentSearches = [];
    public bool $hasSearched = false;
    public bool $isOpen = false;
    public bool $loading = false;

    protected $listeners = ['open-search' => 'open', 'openSearch' => 'open'];

    public function mount(): void
    {
        $this->productResults = collect();
        $this->pageResults = collect();
        $this->loadRecentSearches();
    }

    public function open(): void
    {
        $this->isOpen = true;
        $this->loadRecentSearches();
    }

    public function close(): void
    {
        $this->isOpen = false;
    }

    public function updatedQuery(): void
    {
        if (strlen($this->query) >= 2) {
            $this->loading = true;
            $this->search();
            $this->loading = false;
        } else {
            $this->productResults = collect();
            $this->pageResults = collect();
            $this->hasSearched = false;
        }
    }

    public function search(): void
    {
        if (strlen($this->query) < 2) {
            return;
        }

        $this->hasSearched = true;

        // Ricerca prodotti
        $this->productResults = Product::published()
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->query . '%')
                  ->orWhere('description', 'like', '%' . $this->query . '%')
                  ->orWhere('sku', 'like', '%' . $this->query . '%');
            })
            ->with(['brand', 'media'])
            ->limit(6)
            ->get();

        // Ricerca pagine/blog
        $this->pageResults = Page::published()
            ->where(function ($q) {
                $q->where('title', 'like', '%' . $this->query . '%')
                  ->orWhere('excerpt', 'like', '%' . $this->query . '%')
                  ->orWhere('content', 'like', '%' . $this->query . '%');
            })
            ->limit(4)
            ->get();

        // Salva ricerca se ha risultati
        if ($this->productResults->count() > 0 || $this->pageResults->count() > 0) {
            $this->addToRecentSearches($this->query);
        }
    }

    public function selectRecent(string $term): void
    {
        $this->query = $term;
        $this->search();
    }

    public function clearRecent(): void
    {
        session()->forget('recent_searches');
        $this->recentSearches = [];
    }

    public function goToFullResults(): void
    {
        if ($this->query) {
            $this->addToRecentSearches($this->query);
            return redirect()->to('/shop?q=' . urlencode($this->query));
        }
    }

    protected function loadRecentSearches(): void
    {
        $this->recentSearches = session('recent_searches', []);
    }

    protected function addToRecentSearches(string $query): void
    {
        $recent = session('recent_searches', []);
        
        // Rimuovi se già presente
        $recent = array_filter($recent, fn($item) => $item !== $query);
        
        // Aggiungi all'inizio
        array_unshift($recent, $query);
        
        // Mantieni solo i primi 5
        $recent = array_slice($recent, 0, 5);
        
        session(['recent_searches' => $recent]);
        $this->recentSearches = $recent;
    }

    public function render()
    {
        return view('livewire.public.catalog.product-search');
    }
}