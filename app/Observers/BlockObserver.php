<?php

namespace App\Observers;

use App\Models\Block;
use Illuminate\Support\Facades\Cache;

class BlockObserver
{
    public function saved(Block $block): void
    {
        Cache::forget('skintemple_blocks_' . $block->location);
        if ($block->page_id) {
            Cache::forget('skintemple_page_' . $block->page_id);
        }
    }

    public function deleted(Block $block): void
    {
        $this->saved($block);
    }
}