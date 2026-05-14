@php
    $data = $data ?? [];
    $layout = $data['layout'] ?? 'center'; // center, split
    $bg = $data['bg'] ?? 'white';
@endphp

<x-public.section bg="{{ $bg }}" padding="lg">
    <x-public.container>
        @if($layout === 'split')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center {{ ($data['reversed'] ?? false) ? 'lg:grid-flow-col-dense' : '' }}">
                <div class="{{ ($data['reversed'] ?? false) ? 'lg:col-start-2' : '' }}">
                    @if($data['title'] ?? null)
                        <h1 class="font-display text-4xl md:text-5xl font-semibold text-neutral-900 mb-6">
                            {!! $data['title'] !!}
                        </h1>
                    @endif
                    
                    @if($data['subtitle'] ?? null)
                        <p class="text-lg md:text-xl text-neutral-600 mb-8">
                            {{ $data['subtitle'] }}
                        </p>
                    @endif
                    
                    @if($data['cta_text'] ?? null)
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ $data['cta_url'] ?? '#' }}" class="btn-primary">
                                {{ $data['cta_text'] }}
                            </a>
                            @if($data['secondary_cta_text'] ?? null)
                                <a href="{{ $data['secondary_cta_url'] ?? '#' }}" class="btn-secondary">
                                    {{ $data['secondary_cta_text'] }}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
                
                <div class="{{ ($data['reversed'] ?? false) ? 'lg:col-start-1' : '' }}">
                    @if($data['image_url'] ?? null)
                        <img src="{{ $data['image_url'] }}" 
                             alt="{{ $data['image_alt'] ?? '' }}" 
                             class="w-full h-auto rounded-2xl">
                    @elseif($data['image_placeholder'] ?? false)
                        <div class="aspect-square rounded-full bg-brand-primary-soft flex items-center justify-center">
                            <div class="w-64 h-64 bg-white rounded-full flex items-center justify-center">
                                <span class="text-brand-primary text-sm font-medium">Placeholder Image</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            {{-- Center layout --}}
            <div class="text-center">
                @if($data['title'] ?? null)
                    <h1 class="font-display text-5xl md:text-6xl font-semibold text-neutral-900 tracking-tight mb-6">
                        {!! $data['title'] !!}
                    </h1>
                @endif
                
                @if($data['subtitle'] ?? null)
                    <p class="text-lg md:text-xl text-neutral-600 mb-8 max-w-3xl mx-auto">
                        {{ $data['subtitle'] }}
                    </p>
                @endif
                
                @if($data['cta_text'] ?? null)
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ $data['cta_url'] ?? '#' }}" class="btn-primary">
                            {{ $data['cta_text'] }}
                        </a>
                        @if($data['secondary_cta_text'] ?? null)
                            <a href="{{ $data['secondary_cta_url'] ?? '#' }}" class="btn-secondary">
                                {{ $data['secondary_cta_text'] }}
                            </a>
                        @endif
                    </div>
                @endif

                @if($data['image_url'] ?? null)
                    <div class="mt-12">
                        <img src="{{ $data['image_url'] }}" 
                             alt="{{ $data['image_alt'] ?? '' }}" 
                             class="mx-auto max-w-full h-auto rounded-2xl">
                    </div>
                @endif
            </div>
        @endif
    </x-public.container>
</x-public.section>