<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Product;
use Illuminate\Support\Collection;

interface SearchEngine
{
    public function search(string $query, array $filters = [], int $limit = 20): Collection;

    public function indexProduct(Product $product): void;

    public function removeProduct(Product $product): void;
}
