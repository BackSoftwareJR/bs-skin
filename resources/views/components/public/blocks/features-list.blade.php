@php
    $data = $data ?? [];
    $bg = $data['bg'] ?? 'neutral';
    $items = $data['items'] ?? [];
@endphp

<x-public.section bg="{{ $bg }}">
    <x-public.container>
        @if($data['title'] ?? null)
            <div class="text-center mb-12">
                <h2 class="font-display text-3xl md:text-4xl font-semibold text-neutral-900 mb-6">
                    {{ $data['title'] }}
                </h2>
                @if($data['subtitle'] ?? null)
                    <p class="text-neutral-600 max-w-2xl mx-auto">
                        {{ $data['subtitle'] }}
                    </p>
                @endif
            </div>
        @endif

        @if(count($items) > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($items as $item)
                    <div class="text-center">
                        @if($item['icon'] ?? null)
                            <div class="w-16 h-16 bg-brand-primary rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <x-dynamic-component :component="'heroicon-o-' . ($item['icon'] ?? 'star')" 
                                                     class="h-8 w-8 text-white" />
                            </div>
                        @endif
                        
                        @if($item['title'] ?? null)
                            <h3 class="text-xl font-semibold text-neutral-900 mb-3">
                                {{ $item['title'] }}
                            </h3>
                        @endif
                        
                        @if($item['description'] ?? null)
                            <p class="text-neutral-600">
                                {{ $item['description'] }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </x-public.container>
</x-public.section>