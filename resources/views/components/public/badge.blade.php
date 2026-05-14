@props([
    'color' => 'primary',
    'size' => 'sm'
])

@php
$colorClasses = [
    'primary' => 'bg-brand-primary-soft text-brand-primary',
    'success' => 'bg-success-soft text-success',
    'warning' => 'bg-warning-soft text-warning',
    'danger' => 'bg-danger-soft text-danger',
    'neutral' => 'bg-neutral-100 text-neutral-600',
    'new' => 'bg-brand-accent-soft text-brand-accent',
    'promo' => 'bg-warning-soft text-warning',
];

$sizeClasses = [
    'xs' => 'px-2 py-0.5 text-2xs',
    'sm' => 'px-2.5 py-1 text-xs',
    'md' => 'px-3 py-1.5 text-sm',
];

$classes = 'inline-flex items-center font-medium rounded-full uppercase tracking-wide ' . 
           ($colorClasses[$color] ?? $colorClasses['primary']) . ' ' .
           ($sizeClasses[$size] ?? $sizeClasses['sm']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>