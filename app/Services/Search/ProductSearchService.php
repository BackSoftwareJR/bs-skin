<?php

declare(strict_types=1);

namespace App\Services\Search;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductSearchService
{
    public function search(string $query, array $filters = [], int $perPage = 24): LengthAwarePaginator
    {
        $searchQuery = Product::search($query)
            ->where('status', 'published')
            ->whereNotNull('published_at');
            
        // Applica filtri
        if (!empty($filters['category'])) {
            $searchQuery->whereHas('categories', function ($q) use ($filters) {
                $q->where('slug', $filters['category']);
            });
        }
        
        if (!empty($filters['brand'])) {
            $searchQuery->whereHas('brand', function ($q) use ($filters) {
                $q->where('slug', $filters['brand']);
            });
        }
        
        if (isset($filters['price_min']) || isset($filters['price_max'])) {
            $searchQuery->where(function ($q) use ($filters) {
                if (isset($filters['price_min'])) {
                    $q->where('price', '>=', $filters['price_min']);
                }
                if (isset($filters['price_max'])) {
                    $q->where('price', '<=', $filters['price_max']);
                }
            });
        }
        
        if (!empty($filters['in_stock'])) {
            $searchQuery->whereHas('variants.inventory', function ($q) {
                $q->where(function ($q) {
                    $q->where('quantity', '>', 0)
                      ->orWhere('allow_backorder', true);
                });
            });
        }
        
        if (!empty($filters['is_new'])) {
            $searchQuery->where('is_new', true);
        }
        
        if (!empty($filters['is_featured'])) {
            $searchQuery->where('is_featured', true);
        }
        
        // Gestisci attributi (complex filter)
        if (!empty($filters['attributes'])) {
            foreach ($filters['attributes'] as $attributeId => $valueIds) {
                $searchQuery->whereHas('attributeValues', function ($q) use ($attributeId, $valueIds) {
                    $q->where('attribute_id', $attributeId)
                      ->whereIn('id', $valueIds);
                });
            }
        }
        
        return $searchQuery->paginate($perPage);
    }
    
    public function getSuggestions(string $query, int $limit = 8): Collection
    {
        return Product::search($query)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->take($limit)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'image' => $product->getFirstMediaUrl('gallery'),
                ];
            });
    }
    
    public function getFiltersForQuery(string $query, array $activeFilters = []): array
    {
        $baseQuery = Product::search($query)
            ->where('status', 'published')
            ->whereNotNull('published_at');
            
        // Brands disponibili
        $brands = $baseQuery->with('brand')
            ->get()
            ->pluck('brand')
            ->filter()
            ->unique('id')
            ->map(function ($brand) {
                return [
                    'slug' => $brand->slug,
                    'name' => $brand->name,
                    'count' => 0, // TODO: calcolare count reale
                ];
            })
            ->values();
            
        // Categorie disponibili
        $categories = collect(); // TODO: implementare aggregazione categorie
        
        // Range prezzi
        $prices = $baseQuery->get(['price'])->pluck('price')->filter();
        $priceRange = [
            'min' => $prices->min() ?? 0,
            'max' => $prices->max() ?? 1000,
        ];
        
        return [
            'brands' => $brands,
            'categories' => $categories,
            'price_range' => $priceRange,
            'has_stock' => true, // sempre disponibile come filtro
        ];
    }
}