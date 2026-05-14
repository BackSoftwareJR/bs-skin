@props(['class' => 'h-10'])

@php
  $logoPath = public_path('img/brand/logo.svg');
  $pngPath = public_path('img/brand/logo_skinTemple.png');
@endphp

@if(file_exists($logoPath))
  <img src="{{ asset('img/brand/logo.svg') }}" alt="SkinTemple" {{ $attributes->merge(['class' => $class]) }}>
@elseif(file_exists($pngPath))
  <img src="{{ asset('img/brand/logo_skinTemple.png') }}" alt="SkinTemple" {{ $attributes->merge(['class' => $class]) }}>
@else
  {{-- SVG inline placeholder --}}
  <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 180 40" xmlns="http://www.w3.org/2000/svg" aria-label="SkinTemple">
    <circle cx="18" cy="20" r="14" fill="#0F8A8A" opacity="0.12"/>
    <path d="M18 8 L24 14 L24 22 L18 28 L12 22 L12 14 Z" fill="#0F8A8A" opacity="0.6"/>
    <text x="38" y="26" font-family="Georgia, serif" font-size="18" font-weight="600" fill="#0F172A" letter-spacing="-0.5">SkinTemple</text>
  </svg>
@endif