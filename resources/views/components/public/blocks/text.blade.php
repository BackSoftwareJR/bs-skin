@php
    $data = $data ?? [];
    $bg = $data['bg'] ?? 'white';
    $maxWidth = $data['max_width'] ?? '3xl';
@endphp

<x-public.section bg="{{ $bg }}">
    <x-public.container max="{{ $maxWidth }}">
        @if($data['content'] ?? null)
            <div class="prose prose-lg prose-neutral max-w-none">
                {!! $data['content'] !!}
            </div>
        @endif
    </x-public.container>
</x-public.section>