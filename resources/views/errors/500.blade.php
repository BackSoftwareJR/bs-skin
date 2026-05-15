@extends('layouts.app')

@section('title', 'Errore del server - SkinTemple')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <x-public.container>
        <div class="text-center">
            <div class="text-7xl font-display font-light text-neutral-200 mb-6 tracking-tighter">500</div>
            <h1 class="text-xl font-semibold text-neutral-900 mb-4">Errore del server</h1>
            <p class="text-base text-neutral-500 mb-12 max-w-md mx-auto">
                Si è verificato un errore. Stiamo lavorando per risolverlo.
            </p>
            <div class="space-x-4">
                <x-public.button variant="primary" onclick="window.location.reload()">
                    Riprova
                </x-public.button>
                <x-public.button variant="secondary" href="{{ route('home') }}">
                    Torna alla home
                </x-public.button>
            </div>
        </div>
    </x-public.container>
</div>
@endsection