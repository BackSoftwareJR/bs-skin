@props(['block'])

@switch($block->type)
    @case('hero')
        @include('components.public.blocks.hero', ['data' => $block->content_json])
        @break
    @case('text')
        @include('components.public.blocks.text', ['data' => $block->content_json])
        @break
    @case('image')
        @include('components.public.blocks.image', ['data' => $block->content_json])
        @break
    @case('features')
        @include('components.public.blocks.features', ['data' => $block->content_json])
        @break
    @case('features-list')
        @include('components.public.blocks.features-list', ['data' => $block->content_json])
        @break
    @case('text-quote')
        @include('components.public.blocks.text-quote', ['data' => $block->content_json])
        @break
    @case('product-grid')
        @include('components.public.blocks.product-grid', ['data' => $block->content_json])
        @break
    @case('cta')
        @include('components.public.blocks.cta', ['data' => $block->content_json])
        @break
    @case('newsletter')
        <x-public.section bg="{{ $data['bg'] ?? 'neutral' }}">
            <x-public.container max="lg">
                <div class="text-center">
                    <h2 class="font-display text-2xl md:text-3xl font-semibold text-neutral-900 mb-4">
                        {{ $data['title'] ?? 'Resta aggiornato' }}
                    </h2>
                    <p class="text-neutral-600 mb-8">
                        {{ $data['subtitle'] ?? 'Iscriviti alla newsletter per ricevere novità e offerte esclusive' }}
                    </p>
                    <livewire:public.newsletter.subscribe-form />
                </div>
            </x-public.container>
        </x-public.section>
        @break
    @case('contact-form')
        @include('components.public.blocks.contact-form', ['data' => $block->content_json])
        @break
    @case('video')
        @include('components.public.blocks.video', ['data' => $block->content_json])
        @break
    @case('html')
        @include('components.public.blocks.html', ['data' => $block->content_json])
        @break
    @case('spacer')
        @include('components.public.blocks.spacer', ['data' => $block->content_json])
        @break
    @case('testimonial')
        @include('components.public.blocks.testimonial', ['data' => $block->content_json])
        @break
    @case('brand-grid')
        @include('components.public.blocks.brand-grid', ['data' => $block->content_json])
        @break
    @default
        {{-- Blocco sconosciuto, skip silenzioso --}}
@endswitch