@extends('layouts.app')

@section('title', 'Cookie Policy - SkinTemple')

@push('meta')
    <meta name="description" content="Informativa sull'uso dei cookie su SkinTemple.">
@endpush

@section('content')

<section class="bg-gradient-to-br from-brand-primary-soft via-white to-neutral-50 py-16">
    <x-public.container>
        <div class="max-w-2xl mx-auto text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-primary/10 text-brand-primary text-sm font-semibold tracking-wide uppercase mb-4">Legale</span>
            <h1 class="font-display text-4xl md:text-5xl text-brand-ink mb-4">Cookie Policy</h1>
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
                            La Cookie Policy è in fase di aggiornamento. Per informazioni sull'uso dei cookie su questo sito,
                            contattaci direttamente a <a href="mailto:info@skintemple.it" class="text-brand-primary hover:underline">info@skintemple.it</a>.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-neutral-50 rounded-2xl p-8">
                    <h2 class="font-display text-xl text-brand-ink mb-4">Cosa sono i cookie</h2>
                    <p class="text-brand-ink-soft text-sm leading-relaxed">
                        I cookie sono piccoli file di testo che i siti web visitati inviano al browser dell'utente, dove vengono memorizzati
                        per essere poi ritrasmessi agli stessi siti alla successiva visita. Vengono utilizzati per diverse finalità,
                        tra cui il corretto funzionamento del sito, l'analisi delle visite e la personalizzazione dei contenuti.
                    </p>
                </div>

                <div class="bg-neutral-50 rounded-2xl p-8">
                    <h2 class="font-display text-xl text-brand-ink mb-4">Tipologie di cookie utilizzati</h2>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-brand-primary rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <p class="text-sm font-medium text-brand-ink mb-1">Cookie tecnici necessari</p>
                                <p class="text-sm text-brand-ink-soft">Necessari per il funzionamento del sito e del carrello acquisti. Non richiedono consenso.</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-brand-primary rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <p class="text-sm font-medium text-brand-ink mb-1">Cookie di sessione</p>
                                <p class="text-sm text-brand-ink-soft">Utilizzati per mantenere la sessione utente attiva durante la navigazione. Vengono eliminati alla chiusura del browser.</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-neutral-300 rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <p class="text-sm font-medium text-brand-ink mb-1">Cookie analitici (opzionali)</p>
                                <p class="text-sm text-brand-ink-soft">Utilizzati per raccogliere dati anonimi sull'utilizzo del sito. Attivati solo con consenso esplicito.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-neutral-50 rounded-2xl p-8">
                    <h2 class="font-display text-xl text-brand-ink mb-4">Come gestire i cookie</h2>
                    <p class="text-brand-ink-soft text-sm leading-relaxed">
                        Puoi gestire le preferenze sui cookie dal tuo browser. La maggior parte dei browser moderni consente di
                        bloccare o eliminare i cookie nelle impostazioni. Tieni presente che la disabilitazione dei cookie tecnici
                        potrebbe compromettere alcune funzionalità del sito.
                    </p>
                    <p class="text-brand-ink-soft text-sm leading-relaxed mt-4">
                        Per ulteriori informazioni: <a href="mailto:info@skintemple.it" class="text-brand-primary hover:underline">info@skintemple.it</a>
                    </p>
                </div>
            </div>
        </div>
    </x-public.container>
</section>

@endsection
