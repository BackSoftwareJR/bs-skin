@php
    $data = $data ?? [];
    $bg = $data['bg'] ?? 'white';
    $title = $data['title'] ?? null;
    $subtitle = $data['subtitle'] ?? null;
    $limit = $data['limit'] ?? 8;
    $sort = $data['sort'] ?? 'newest';
    $categorySlug = $data['category_slug'] ?? null;
@endphp

<x-public.section bg="{{ $bg }}">
    <x-public.container>
        @if($title)
            <div class="text-center mb-12">
                <h2 class="font-display text-3xl md:text-4xl font-medium text-neutral-900 mb-4">
                    {{ $title }}
                </h2>
                @if($subtitle)
                    <p class="text-neutral-600">{{ $subtitle }}</p>
                @endif
            </div>
        @endif

        <livewire:public.catalog.product-grid 
            :limit="$limit" 
            :sort="$sort"
            :category-slug="$categorySlug" />
    </x-public.container>
</x-public.section>