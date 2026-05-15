@extends('layouts.app')

@section('title', 'Termini di Servizio - SkinTemple')

@push('meta')
    <meta name="description" content="Termini e condizioni generali di vendita di SkinTemple.">
@endpush

@section('content')

<section class="bg-gradient-to-br from-brand-primary-soft via-white to-neutral-50 py-16">
    <x-public.container>
        <div class="max-w-2xl mx-auto text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-primary/10 text-brand-primary text-sm font-semibold tracking-wide uppercase mb-4">Legale</span>
            <h1 class="font-display text-4xl md:text-5xl text-brand-ink mb-4">Termini di Servizio</h1>
            <p class="text-brand-ink-soft">Ultimo aggiornamento: {{ date('d/m/Y') }}</p>
        </div>
    </x-public.container>
</section>

<section class="py-16 bg-white">
    <x-public.container>
        <div class="max-w-3xl mx-auto">
            <div class="bg-brand-primary-soft border border-brand-primary/20 rounded-2xl p-6 mb-10">
                <div class="flex gap-4">
                    <svg class="w-6 h-6 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-brand-ink mb-1">Documento in aggiornamento</h3>
                        <p class="text-sm text-brand-ink-soft">
                            I Termini e le Condizioni Generali di Vendita sono in fase di redazione.
                            Per qualsiasi informazione, contattaci direttamente.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-neutral-50 rounded-2xl p-8">
                    <h2 class="font-display text-xl text-brand-ink mb-4">Venditore</h2>
                    <p class="text-brand-ink-soft text-sm leading-relaxed">
                        SkinTemple S.r.l.<br>
                        Strada Santa Vittoria 11, 10024 Moncalieri (TO)<br>
                        P.IVA 11863510019<br>
                        Email: <a href="mailto:info@skintemple.it" class="text-brand-primary hover:underline">info@skintemple.it</a>
                    </p>
                </div>

                <div class="bg-neutral-50 rounded-2xl p-8">
                    <h2 class="font-display text-xl text-brand-ink mb-4">Condizioni complete</h2>
                    <p class="text-brand-ink-soft text-sm leading-relaxed">
                        Le condizioni generali di vendita complete sono in fase di redazione e saranno pubblicate a breve.
                        L'utilizzo del presente sito e l'acquisto di prodotti sono regolati dalle norme vigenti in materia
                        di commercio elettronico (D.Lgs. 70/2003), tutela del consumatore (D.Lgs. 206/2005 — Codice del Consumo)
                        e dalla normativa europea applicabile.
                    </p>
                    <p class="text-brand-ink-soft text-sm leading-relaxed mt-4">
                        Per informazioni su resi, rimborsi, garanzie o qualsiasi altra condizione contrattuale,
                        scrivici a <a href="mailto:info@skintemple.it" class="text-brand-primary hover:underline">info@skintemple.it</a>.
                    </p>
                </div>
            </div>
        </div>
    </x-public.container>
</section>

@endsection
