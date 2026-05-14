<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
    public function created(Category $category): void
    {
        $this->invalidateCache();
    }

    public function updated(Category $category): void
    {
        $this->invalidateCache();
    }

    public function deleted(Category $category): void
    {
        $this->invalidateCache();
    }

    private function invalidateCache(): void
    {
        $keys = [
            'skintemple_categories_tree',
            'skintemple_menu_main_nav',
            'skintemple_menu_footer_nav',
            'skintemple_menu_mobile_nav',
            'skintemple_categories_macroaree',
            'skintemple_categories_microaree',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}