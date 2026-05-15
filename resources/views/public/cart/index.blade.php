@extends('layouts.app')

@section('title', 'Carrello - SkinTemple')

@section('content')
<x-public.container>
    <div class="py-8">
        <h1 class="text-3xl font-semibold text-brand-ink mb-8">Il tuo carrello</h1>
        
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293A1 1 0 004 16v0a1 1 0 001 1h10"></path>
            </svg>
            <h2 class="text-xl font-medium text-brand-ink mb-2">Carrello vuoto</h2>
            <p class="text-brand-muted mb-6">Aggiungi alcuni prodotti per iniziare</p>
            <x-public.button variant="primary" href="{{ route('shop.index') }}">
                Inizia a comprare
            </x-public.button>
        </div>
    </div>
</x-public.container>
@endsection