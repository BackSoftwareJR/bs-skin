@extends('layouts.app')

@section('title', 'Privacy Policy - SkinTemple')

@push('meta')
    <meta name="description" content="Informativa sulla privacy di SkinTemple. Come trattiamo i tuoi dati personali.">
@endpush

@section('content')

<section class="bg-gradient-to-br from-brand-primary-soft via-white to-neutral-50 py-16">
    <x-public.container>
        <div class="max-w-2xl mx-auto text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-primary/10 text-brand-primary text-sm font-semibold tracking-wide uppercase mb-4">Legale</span>
            <h1 class="font-display text-4xl md:text-5xl text-brand-ink mb-4">Privacy Policy</h1>
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
                            La presente informativa sulla privacy è in fase di aggiornamento per adeguarsi alle ultime normative GDPR.
                            Per qualsiasi informazione sul trattamento dei tuoi dati personali, contattaci direttamente.
                        </p>
                    </div>
                </div>
            </div>

            <div class="prose prose-neutral max-w-none space-y-8">
                <div class="bg-neutral-50 rounded-2xl p-8">
                    <h2 class="font-display text-xl text-brand-ink mb-4">Titolare del trattamento</h2>
                    <p class="text-brand-ink-soft text-sm leading-relaxed">
                        SkinTemple S.r.l.<br>
                        Strada Santa Vittoria 11, 10024 Moncalieri (TO)<br>
                        P.IVA 11863510019<br>
                        Email: <a href="mailto:info@skintemple.it" class="text-brand-primary hover:underline">info@skintemple.it</a>
                    </p>
                </div>

                <div class="bg-neutral-50 rounded-2xl p-8">
                    <h2 class="font-display text-xl text-brand-ink mb-4">Informazioni complete</h2>
                    <p class="text-brand-ink-soft text-sm leading-relaxed">
                        Il documento completo relativo alla Privacy Policy è in fase di redazione e sarà pubblicato a breve.
                        Nel frattempo, per qualsiasi domanda riguardante il trattamento dei tuoi dati personali, il loro utilizzo,
                        la tua richiesta di accesso, rettifica, cancellazione o portabilità, ti invitiamo a contattarci direttamente
                        all'indirizzo <a href="mailto:info@skintemple.it" class="text-brand-primary hover:underline">info@skintemple.it</a>.
                    </p>
                    <p class="text-brand-ink-soft text-sm leading-relaxed mt-4">
                        Risponderemo alla tua richiesta entro 30 giorni come previsto dal Regolamento (UE) 2016/679 (GDPR).
                    </p>
                </div>
            </div>
        </div>
    </x-public.container>
</section>

@endsection
