@php
    $data = $data ?? [];
    $bg = $data['bg'] ?? 'brand-primary';
    $textColor = str_contains($bg, 'brand-primary') ? 'text-white' : 'text-neutral-900';
@endphp

<x-public.section bg="{{ $bg }}" class="{{ $textColor }}">
    <x-public.container>
        <div class="text-center">
            @if($data['title'] ?? null)
                <h2 class="font-display text-3xl md:text-4xl font-semibold mb-6">
                    {{ $data['title'] }}
                </h2>
            @endif
            
            @if($data['text'] ?? null)
                <p class="text-xl mb-8 opacity-90 max-w-2xl mx-auto">
                    {{ $data['text'] }}
                </p>
            @endif
            
            @if($data['cta_text'] ?? null)
                <a href="{{ $data['cta_url'] ?? '#' }}" 
                   class="{{ str_contains($bg, 'brand-primary') ? 'btn-secondary text-brand-primary bg-white hover:bg-neutral-100' : 'btn-primary' }}">
                    {{ $data['cta_text'] }}
                </a>
            @endif
        </div>
    </x-public.container>
</x-public.section>