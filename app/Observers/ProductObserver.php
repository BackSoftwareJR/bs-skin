<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    public function created(Product $product): void
    {
        $this->invalidateCache();
    }

    public function updated(Product $product): void
    {
        $this->invalidateCache();
        
        // Se è diventato pubblicato, invalida anche cache speciali
        if ($product->wasChanged('status') && $product->status->value === 'published') {
            $this->invalidateSpecialCache();
        }
    }

    public function deleted(Product $product): void
    {
        $this->invalidateCache();
    }

    private function invalidateCache(): void
    {
        // Cache invalidation per file driver (niente tags supportate)
        $keys = [
            'skintemple_products_featured',
            'skintemple_products_new',
            'skintemple_products_bestseller',
            'skintemple_catalog_categories_with_products',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
        
        // Flush pattern-based cache per catalogo
        $this->flushCatalogCache();
    }

    private function invalidateSpecialCache(): void
    {
        Cache::forget('skintemple_products_featured');
        Cache::forget('skintemple_products_new');
    }

    private function flushCatalogCache(): void
    {
        // Simula flush per chiavi con pattern (non supportato da file driver)
        $prefixes = ['skintemple_catalog_', 'skintemple_category_', 'skintemple_brand_'];
        
        foreach ($prefixes as $prefix) {
            // In un'implementazione reale, dovresti tenere traccia delle chiavi
            // o usare un driver di cache che supporta pattern matching
        }
    }
}