@php
    $logoPath = public_path('img/brand/logo.svg');
    $hasCustomLogo = file_exists($logoPath);
@endphp

@if ($hasCustomLogo)
    <img src="{{ asset('img/brand/logo.svg') }}" alt="SkinTemple" class="h-8 w-auto" />
@else
    <span class="font-display text-2xl font-semibold tracking-wide text-brand-dark">
        Skin<span class="text-brand-gold">Temple</span>
    </span>
@endif
