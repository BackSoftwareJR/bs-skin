@extends('layouts.app')

@section('title', 'Contatti e Supporto - SkinTemple')

@push('meta')
    <meta name="description" content="Contatta il team SkinTemple per assistenza sui tuoi ordini, consulenza sui prodotti o qualsiasi informazione. Siamo qui per aiutarti.">
@endpush

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-brand-primary-soft via-white to-neutral-50 py-16">
    <x-public.container>
        <div class="max-w-2xl mx-auto text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-primary/10 text-brand-primary text-sm font-semibold tracking-wide uppercase mb-4">Supporto</span>
            <h1 class="font-display text-4xl md:text-5xl text-brand-ink mb-4">Come possiamo aiutarti?</h1>
            <p class="text-lg text-brand-ink-soft">Il nostro team è disponibile per rispondere a ogni tua domanda su prodotti, ordini e tecnologie.</p>
        </div>
    </x-public.container>
</section>

{{-- Contact channels --}}
<section class="py-14 bg-white">
    <x-public.container>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            <div class="text-center p-8 bg-neutral-50 rounded-2xl hover:bg-brand-primary-soft transition-colors group">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-soft-sm group-hover:shadow-soft-md transition-shadow">
                    <svg class="w-7 h-7 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-brand-ink mb-2">Email</h3>
                <p class="text-brand-ink-soft text-sm mb-3">Risposta entro 24 ore lavorative</p>
                <a href="mailto:info@skintemple.it" class="text-brand-primary font-medium text-sm hover:underline">info@skintemple.it</a>
            </div>

            <div class="text-center p-8 bg-neutral-50 rounded-2xl hover:bg-brand-primary-soft transition-colors group">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-soft-sm group-hover:shadow-soft-md transition-shadow">
                    <svg class="w-7 h-7 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-brand-ink mb-2">Telefono</h3>
                <p class="text-brand-ink-soft text-sm mb-3">Lun–Ven, 9:00–18:00</p>
                <a href="tel:+390000000000" class="text-brand-primary font-medium text-sm hover:underline">+39 000 000 0000</a>
            </div>

            <div class="text-center p-8 bg-neutral-50 rounded-2xl hover:bg-brand-primary-soft transition-colors group">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-soft-sm group-hover:shadow-soft-md transition-shadow">
                    <svg class="w-7 h-7 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 3H3a2 2 0 00-2 2v14l4-4h14a2 2 0 002-2V5a2 2 0 00-2-2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-brand-ink mb-2">Live Chat</h3>
                <p class="text-brand-ink-soft text-sm mb-3">Disponibile durante l'orario lavorativo</p>
                <span class="inline-flex items-center gap-1.5 text-success text-sm font-medium">
                    <span class="w-2 h-2 bg-success rounded-full"></span>
                    Online ora
                </span>
            </div>
        </div>

        {{-- Contact form + company info --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">
            {{-- Form --}}
            <div class="lg:col-span-3">
                <h2 class="font-display text-2xl text-brand-ink mb-6">Inviaci un messaggio</h2>
                <form action="#" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="nome" class="block text-sm font-medium text-brand-ink mb-1.5">Nome <span class="text-danger">*</span></label>
                            <input type="text" id="nome" name="nome" required
                                   placeholder="Mario"
                                   class="w-full rounded-xl border border-brand-border px-4 py-3 text-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none transition-all">
                        </div>
                        <div>
                            <label for="cognome" class="block text-sm font-medium text-brand-ink mb-1.5">Cognome <span class="text-danger">*</span></label>
                            <input type="text" id="cognome" name="cognome" required
                                   placeholder="Rossi"
                                   class="w-full rounded-xl border border-brand-border px-4 py-3 text-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-brand-ink mb-1.5">Email <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" required
                               placeholder="mario.rossi@email.com"
                               class="w-full rounded-xl border border-brand-border px-4 py-3 text-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none transition-all">
                    </div>

                    <div>
                        <label for="oggetto" class="block text-sm font-medium text-brand-ink mb-1.5">Oggetto</label>
                        <select id="oggetto" name="oggetto"
                                class="w-full rounded-xl border border-brand-border px-4 py-3 text-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none transition-all bg-white">
                            <option value="">Seleziona un argomento</option>
                            <option value="ordine">Domanda su un ordine</option>
                            <option value="prodotto">Informazioni su un prodotto</option>
                            <option value="reso">Reso o rimborso</option>
                            <option value="tecnico">Supporto tecnico</option>
                            <option value="altro">Altro</option>
                        </select>
                    </div>

                    <div>
                        <label for="messaggio" class="block text-sm font-medium text-brand-ink mb-1.5">Messaggio <span class="text-danger">*</span></label>
                        <textarea id="messaggio" name="messaggio" rows="5" required
                                  placeholder="Descrivi la tua richiesta..."
                                  class="w-full rounded-xl border border-brand-border px-4 py-3 text-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none transition-all resize-none"></textarea>
                    </div>

                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="privacy" name="privacy" required
                               class="mt-0.5 rounded border-brand-border text-brand-primary focus:ring-brand-primary/20">
                        <label for="privacy" class="text-sm text-brand-ink-soft">
                            Accetto la <a href="/privacy-policy" class="text-brand-primary hover:underline">Privacy Policy</a> e autorizzo il trattamento dei miei dati personali per rispondere alla mia richiesta.
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full bg-brand-primary text-white font-semibold py-4 px-6 rounded-xl hover:bg-brand-primary/90 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Invia messaggio
                    </button>
                </form>
            </div>

            {{-- Company info --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-neutral-50 rounded-2xl p-6">
                    <h3 class="font-semibold text-brand-ink mb-4">Informazioni aziendali</h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex gap-3">
                            <dt class="text-brand-ink-soft w-20 flex-shrink-0">Ragione sociale</dt>
                            <dd class="text-brand-ink font-medium">SkinTemple S.r.l.</dd>
                        </div>
                        <div class="flex gap-3">
                            <dt class="text-brand-ink-soft w-20 flex-shrink-0">Indirizzo</dt>
                            <dd class="text-brand-ink font-medium">Via Example 1<br>00100 Roma (RM)</dd>
                        </div>
                        <div class="flex gap-3">
                            <dt class="text-brand-ink-soft w-20 flex-shrink-0">P.IVA</dt>
                            <dd class="text-brand-ink font-medium">IT00000000000</dd>
                        </div>
                        <div class="flex gap-3">
                            <dt class="text-brand-ink-soft w-20 flex-shrink-0">Email</dt>
                            <dd><a href="mailto:info@skintemple.it" class="text-brand-primary hover:underline font-medium">info@skintemple.it</a></dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-neutral-50 rounded-2xl p-6">
                    <h3 class="font-semibold text-brand-ink mb-4">Orari di assistenza</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-brand-ink-soft">Lunedì – Venerdì</dt>
                            <dd class="text-brand-ink font-medium">9:00 – 18:00</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-brand-ink-soft">Sabato</dt>
                            <dd class="text-brand-ink font-medium">9:00 – 13:00</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-brand-ink-soft">Domenica</dt>
                            <dd class="text-brand-ink-soft">Chiuso</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </x-public.container>
</section>

{{-- FAQ --}}
<section class="py-16 bg-neutral-50">
    <x-public.container>
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-10">
                <span class="text-brand-primary text-sm font-semibold uppercase tracking-widest mb-3 block">FAQ</span>
                <h2 class="font-display text-3xl text-brand-ink">Domande frequenti</h2>
            </div>

            <div class="space-y-4" x-data="{ open: null }">
                @php
                $faqs = [
                    ['q' => 'Quanto tempo ci vuole per ricevere il mio ordine?', 'a' => 'Per la maggior parte degli ordini la consegna avviene entro 2–5 giorni lavorativi in Italia. Per le isole e le zone remote potrebbero essere necessari 1–2 giorni in più.'],
                    ['q' => 'Posso restituire un prodotto?', 'a' => 'Sì, puoi restituire qualsiasi prodotto entro 14 giorni dalla ricezione, purché sia integro e nella sua confezione originale. Contattaci via email per avviare la procedura di reso.'],
                    ['q' => 'I dispositivi estetici richiedono una formazione specifica?', 'a' => 'Dipende dal dispositivo. Per i device professionali consigliamo sempre una formazione adeguata. Il nostro team può aiutarti a scegliere il prodotto giusto per le tue competenze e a indicarti eventuali corsi di formazione disponibili.'],
                    ['q' => 'Come faccio a sapere quale prodotto è adatto a me?', 'a' => 'Il nostro team di esperti è disponibile per una consulenza personalizzata gratuita. Contattaci via email o telefono descrivendo le tue esigenze e il tipo di trattamento che vuoi effettuare.'],
                    ['q' => 'I prodotti sono originali e certificati?', 'a' => 'Assolutamente sì. Acquistiamo direttamente dai produttori o da distributori ufficiali autorizzati. Tutti i dispositivi sono certificati CE e rispettano le normative europee vigenti.'],
                    ['q' => 'Come funziona il pagamento?', 'a' => 'Accettiamo carte di credito/debito, PayPal e bonifico bancario. Il pagamento è protetto da crittografia SSL e i tuoi dati non vengono mai memorizzati sui nostri server.'],
                ];
                @endphp

                @foreach($faqs as $i => $faq)
                <div class="bg-white rounded-2xl overflow-hidden shadow-soft-sm">
                    <button @click="open === {{ $i }} ? open = null : open = {{ $i }}"
                            class="w-full flex items-center justify-between px-6 py-5 text-left">
                        <span class="font-medium text-brand-ink pr-4">{{ $faq['q'] }}</span>
                        <svg class="w-5 h-5 text-brand-primary flex-shrink-0 transition-transform duration-200"
                             :class="{ 'rotate-180': open === {{ $i }} }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open === {{ $i }}"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-1"
                         class="px-6 pb-5">
                        <p class="text-brand-ink-soft text-sm leading-relaxed border-t border-neutral-100 pt-4">{{ $faq['a'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </x-public.container>
</section>

@endsection
