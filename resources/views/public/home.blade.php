@extends('layouts.app')

@section('title', 'SkinTemple - Tecnologie Multifunzione per il Centro Estetico')

@push('meta')
    <meta name="description" content="Tecnologie multifunzione al servizio del centro estetico. Made in Italy, assistenza dedicata, noleggio e vendita.">
@endpush

@section('content')
{{-- Renderizza blocchi CMS dinamici --}}
@php 
    $blocks = \App\Models\Block::where('location', 'homepage')
        ->active()
        ->orderBy('sort_order')
        ->get();
@endphp

@if($blocks->count() > 0)
    @foreach($blocks as $block)
        <x-public.block-renderer :block="$block" />
    @endforeach
@else
    {{-- Fallback HTML statico se nessun blocco CMS --}}
    
    {{-- Hero Section --}}
    <x-public.section bg="white" padding="lg">
        <x-public.container>
            <div class="text-center">
                <h1 class="font-display text-5xl md:text-6xl font-semibold text-neutral-900 tracking-tight mb-6">
                    TECNOLOGIE MULTIFUNZIONE<br>
                    <span class="text-brand-primary">AL SERVIZIO DEL CENTRO ESTETICO</span>
                </h1>
                <p class="text-lg md:text-xl text-neutral-600 mb-8 max-w-3xl mx-auto">
                    SkinTemple è la soluzione Made In Italy che ti affianca nella crescita del tuo business con tecnologie all'avanguardia
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/shop" class="btn-primary">Scopri i prodotti</a>
                    <a href="/tecnologie" class="btn-secondary">Le nostre tecnologie</a>
                </div>
            </div>
        </x-public.container>
    </x-public.section>

    {{-- Feature Bar --}}
    <x-public.feature-bar />

    {{-- Features Split: Tratta tutti gli inestetismi --}}
    <x-public.section bg="neutral">
        <x-public.container>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="font-display text-3xl md:text-4xl font-semibold text-neutral-900 mb-6">
                        TRATTA TUTTI GLI INESTETISMI
                    </h2>
                    <div class="space-y-4 text-neutral-600">
                        <p>Con la tecnologia multifunzione puoi lavorare viso e corpo con un solo dispositivo, ottimizzando spazi e investimenti.</p>
                        <p>Ogni trattamento è personalizzabile in base alle esigenze specifiche del cliente.</p>
                    </div>
                    <div class="mt-8">
                        <a href="/tecnologie" class="btn-primary">Scopri le tecnologie</a>
                    </div>
                </div>
                <div class="relative">
                    <div class="aspect-square rounded-full bg-brand-primary-soft flex items-center justify-center">
                        <div class="w-64 h-64 bg-white rounded-full flex items-center justify-center">
                            <span class="text-brand-primary text-sm font-medium">Immagine dispositivo</span>
                        </div>
                    </div>
                </div>
            </div>
        </x-public.container>
    </x-public.section>

    {{-- Quote Section --}}
    <x-public.section bg="white">
        <x-public.container>
            <div class="text-center">
                <h2 class="font-display text-4xl md:text-5xl font-medium text-neutral-900 mb-6">
                    "La cura della pelle, ridefinita."
                </h2>
                <p class="text-lg text-neutral-600 italic font-display mb-8 max-w-2xl mx-auto">
                    La qualità di ogni prodotto è il riflesso della cura che mettiamo nella selezione delle migliori tecnologie
                </p>
                <a href="/shop" class="btn-primary">Esplora i prodotti</a>
            </div>
        </x-public.container>
    </x-public.section>

    {{-- Features List: Un solo dispositivo --}}
    <x-public.section bg="neutral">
        <x-public.container>
            <div class="text-center mb-12">
                <h2 class="font-display text-3xl md:text-4xl font-semibold text-neutral-900 mb-6">
                    UN SOLO DISPOSITIVO, INFINITI RISULTATI
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-brand-primary rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <x-heroicon-o-wrench-screwdriver class="h-8 w-8 text-white" />
                    </div>
                    <h3 class="text-xl font-semibold text-neutral-900 mb-3">Massima versatilità</h3>
                    <p class="text-neutral-600">Versatilità operativa per trattare ogni tipo di inestetismo con un solo investimento</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-brand-primary rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <x-heroicon-o-computer-desktop class="h-8 w-8 text-white" />
                    </div>
                    <h3 class="text-xl font-semibold text-neutral-900 mb-3">Display personalizzabile</h3>
                    <p class="text-neutral-600">Personalizza il display con il logo del tuo istituto per un'esperienza professionale</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-brand-primary rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <x-heroicon-o-credit-card class="h-8 w-8 text-white" />
                    </div>
                    <h3 class="text-xl font-semibold text-neutral-900 mb-3">Noleggio o vendita</h3>
                    <p class="text-neutral-600">Scegli la formula che preferisci: acquisto diretto o noleggio operativo flessibile</p>
                </div>
            </div>
        </x-public.container>
    </x-public.section>

    {{-- Nuovi Arrivi --}}
    <x-public.section bg="white">
        <x-public.container>
            <div class="text-center mb-12">
                <h2 class="font-display text-3xl md:text-4xl font-medium text-neutral-900 mb-4">
                    Nuovi Arrivi
                </h2>
                <p class="text-neutral-600">Scopri le ultime novità per il tuo centro estetico</p>
            </div>
            <livewire:public.catalog.product-grid :limit="8" sort="newest" />
        </x-public.container>
    </x-public.section>

    {{-- CTA Section --}}
    <x-public.section bg="brand-primary" class="text-white">
        <x-public.container>
            <div class="text-center">
                <h2 class="font-display text-3xl md:text-4xl font-semibold mb-6">
                    Scopri come ottimizzare il tuo centro
                </h2>
                <p class="text-xl mb-8 opacity-90 max-w-2xl mx-auto">
                    Richiedi una consulenza gratuita per scoprire la soluzione più adatta alle tue esigenze
                </p>
                <a href="/contatti" class="btn-secondary text-brand-primary bg-white hover:bg-neutral-100">
                    Contattaci
                </a>
            </div>
        </x-public.container>
    </x-public.section>

    {{-- Newsletter Section --}}
    <x-public.section bg="neutral">
        <x-public.container max="lg">
            <div class="text-center">
                <h2 class="font-display text-2xl md:text-3xl font-semibold text-neutral-900 mb-4">
                    Resta aggiornato
                </h2>
                <p class="text-neutral-600 mb-8">
                    Iscriviti alla newsletter per ricevere novità, consigli e offerte esclusive
                </p>
                <livewire:public.newsletter.subscribe-form />
            </div>
        </x-public.container>
    </x-public.section>
@endif
@endsection