@extends('layouts.app')

@section('title', 'Chi Siamo - SkinTemple')

@push('meta')
    <meta name="description" content="SkinTemple è il tuo punto di riferimento per i migliori trattamenti estetici professionali. Scopri la nostra storia, i nostri valori e il nostro team.">
@endpush

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-brand-primary-soft via-white to-neutral-50 py-20">
    <x-public.container>
        <div class="max-w-3xl mx-auto text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-primary/10 text-brand-primary text-sm font-semibold tracking-wide uppercase mb-4">Chi Siamo</span>
            <h1 class="font-display text-4xl md:text-5xl text-brand-ink mb-6 leading-tight">
                La bellezza professionale,<br class="hidden md:block"> alla tua portata
            </h1>
            <p class="text-lg text-brand-ink-soft leading-relaxed">
                SkinTemple nasce dalla passione per l'estetica professionale e dalla volontà di rendere accessibili le migliori tecnologie e prodotti del settore.
            </p>
        </div>
    </x-public.container>
</section>

{{-- Story --}}
<section class="py-20 bg-white">
    <x-public.container>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="text-brand-primary text-sm font-semibold uppercase tracking-widest mb-3 block">La nostra storia</span>
                <h2 class="font-display text-3xl text-brand-ink mb-6">Nati dalla passione per l'estetica professionale</h2>
                <div class="space-y-4 text-brand-ink-soft leading-relaxed">
                    <p>
                        SkinTemple è nata con un obiettivo chiaro: diventare il punto di riferimento per i professionisti dell'estetica e per chi desidera portare a casa l'efficacia dei trattamenti professionali.
                    </p>
                    <p>
                        Abbiamo selezionato con cura ogni prodotto e tecnologia presente nel nostro catalogo, lavorando direttamente con i migliori produttori e marchi del settore per garantire qualità, sicurezza ed efficacia reale.
                    </p>
                    <p>
                        Oggi serviamo centri estetici, professionisti del benessere e appassionati in tutta Italia, con un servizio che mette al centro la consulenza personalizzata e la soddisfazione del cliente.
                    </p>
                </div>
            </div>
            <div class="relative">
                <div class="bg-gradient-to-br from-brand-primary-soft to-neutral-100 rounded-3xl aspect-square flex items-center justify-center">
                    <div class="text-center p-8">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="bg-white rounded-2xl p-6 shadow-soft-md text-center">
                                <div class="text-4xl font-display font-bold text-brand-primary mb-1">500+</div>
                                <div class="text-sm text-brand-ink-soft font-medium">Prodotti</div>
                            </div>
                            <div class="bg-white rounded-2xl p-6 shadow-soft-md text-center">
                                <div class="text-4xl font-display font-bold text-brand-primary mb-1">2k+</div>
                                <div class="text-sm text-brand-ink-soft font-medium">Clienti</div>
                            </div>
                            <div class="bg-white rounded-2xl p-6 shadow-soft-md text-center">
                                <div class="text-4xl font-display font-bold text-brand-primary mb-1">50+</div>
                                <div class="text-sm text-brand-ink-soft font-medium">Brand selezionati</div>
                            </div>
                            <div class="bg-white rounded-2xl p-6 shadow-soft-md text-center">
                                <div class="text-4xl font-display font-bold text-brand-primary mb-1">5★</div>
                                <div class="text-sm text-brand-ink-soft font-medium">Valutazione media</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-public.container>
</section>

{{-- Values --}}
<section class="py-20 bg-neutral-50">
    <x-public.container>
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-brand-primary text-sm font-semibold uppercase tracking-widest mb-3 block">I nostri valori</span>
            <h2 class="font-display text-3xl text-brand-ink">Cosa ci guida ogni giorno</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-8 shadow-soft-sm hover:shadow-soft-md transition-shadow">
                <div class="w-12 h-12 bg-brand-primary/10 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-brand-ink text-lg mb-3">Qualità garantita</h3>
                <p class="text-brand-ink-soft text-sm leading-relaxed">Ogni prodotto è selezionato personalmente e testato prima di essere inserito nel nostro catalogo. Nessun compromesso sulla qualità.</p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-soft-sm hover:shadow-soft-md transition-shadow">
                <div class="w-12 h-12 bg-brand-primary/10 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-brand-ink text-lg mb-3">Consulenza esperta</h3>
                <p class="text-brand-ink-soft text-sm leading-relaxed">Il nostro team è composto da professionisti del settore estetico, pronti a guidarti nella scelta dei prodotti più adatti alle tue esigenze.</p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-soft-sm hover:shadow-soft-md transition-shadow">
                <div class="w-12 h-12 bg-brand-primary/10 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-brand-ink text-lg mb-3">Innovazione continua</h3>
                <p class="text-brand-ink-soft text-sm leading-relaxed">Monitoriamo costantemente le ultime tendenze e tecnologie del settore estetico per portarti sempre il meglio dell'innovazione.</p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-soft-sm hover:shadow-soft-md transition-shadow">
                <div class="w-12 h-12 bg-brand-primary/10 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-brand-ink text-lg mb-3">Sicurezza prima di tutto</h3>
                <p class="text-brand-ink-soft text-sm leading-relaxed">Tutti i nostri dispositivi e prodotti rispettano le normative CE e le direttive europee di sicurezza per uso professionale e domestico.</p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-soft-sm hover:shadow-soft-md transition-shadow">
                <div class="w-12 h-12 bg-brand-primary/10 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-brand-ink text-lg mb-3">Supporto dedicato</h3>
                <p class="text-brand-ink-soft text-sm leading-relaxed">Assistenza pre e post-vendita per rispondere a ogni tua domanda, con tempi di risposta rapidi e personale qualificato.</p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-soft-sm hover:shadow-soft-md transition-shadow">
                <div class="w-12 h-12 bg-brand-primary/10 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-brand-ink text-lg mb-3">Passione autentica</h3>
                <p class="text-brand-ink-soft text-sm leading-relaxed">Amiamo davvero il mondo dell'estetica. Questa passione si traduce in ogni prodotto che selezioniamo e in ogni consiglio che offriamo.</p>
            </div>
        </div>
    </x-public.container>
</section>

{{-- Mission --}}
<section class="py-20 bg-brand-ink">
    <x-public.container>
        <div class="max-w-3xl mx-auto text-center">
            <span class="text-brand-primary text-sm font-semibold uppercase tracking-widest mb-4 block">La nostra missione</span>
            <blockquote class="font-display text-2xl md:text-3xl text-white leading-relaxed mb-8">
                "Vogliamo che ogni persona possa accedere alle tecnologie e ai prodotti estetici professionali, con la sicurezza di avere al fianco un team esperto e appassionato."
            </blockquote>
            <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 bg-brand-primary text-white font-semibold px-8 py-4 rounded-xl hover:bg-brand-primary/90 transition-colors">
                Scopri i nostri prodotti
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </x-public.container>
</section>

{{-- CTA Contact --}}
<section class="py-16 bg-white">
    <x-public.container>
        <div class="bg-brand-primary-soft rounded-3xl p-10 text-center">
            <h2 class="font-display text-2xl text-brand-ink mb-3">Hai domande? Siamo qui per te.</h2>
            <p class="text-brand-ink-soft mb-6">Il nostro team è disponibile per rispondere a ogni tua curiosità su prodotti, tecnologie e trattamenti.</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-brand-primary text-white font-semibold px-6 py-3 rounded-xl hover:bg-brand-primary/90 transition-colors">
                Contattaci
            </a>
        </div>
    </x-public.container>
</section>

@endsection
