@extends('layouts.app')

@section('title', 'Carrello - SkinTemple')

@section('content')
@inject('cartSvc', 'App\Services\CartService')
@php
    $items    = $cartSvc->get();
    $total    = $cartSvc->total();
    $shipping = $total >= 99 ? 0.0 : 5.90;
@endphp

<div class="min-h-screen bg-neutral-50">
    <x-public.container class="py-8">
        <x-public.breadcrumb :items="[['label' => 'Carrello']]" />
        <h1 class="font-display text-3xl font-semibold text-neutral-900 mb-8">Il tuo carrello</h1>

        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(empty($items))
            {{-- Empty state --}}
            <div class="text-center py-20">
                <svg class="h-16 w-16 text-neutral-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293A1 1 0 004 16v0a1 1 0 001 1h10"/>
                </svg>
                <h2 class="text-xl font-medium text-neutral-900 mb-2">Il carrello è vuoto</h2>
                <p class="text-neutral-500 mb-6">Aggiungi qualche prodotto per iniziare</p>
                <a href="{{ route('shop.index') }}"
                   class="inline-flex items-center px-6 py-3 bg-brand-primary text-white rounded-xl font-medium hover:bg-teal-700 transition-colors">
                    Vai allo shop
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Lista prodotti --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($items as $item)
                    <div class="bg-white rounded-2xl shadow-sm p-4 flex gap-4">

                        {{-- Immagine --}}
                        <div class="w-20 h-20 rounded-xl bg-neutral-100 flex-shrink-0 overflow-hidden flex items-center justify-center">
                            @if(!empty($item['image']))
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                            @else
                                <svg class="h-8 w-8 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            @endif
                        </div>

                        {{-- Info prodotto --}}
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <div>
                                    <h3 class="font-medium text-neutral-900">{{ $item['name'] }}</h3>
                                    @if(!empty($item['sku']))
                                        <p class="text-xs text-neutral-400 mt-0.5">SKU: {{ $item['sku'] }}</p>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('cart.items.destroy', $item['product_id']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-neutral-400 hover:text-red-500 transition-colors"
                                            aria-label="Rimuovi {{ $item['name'] }}">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            <div class="flex items-center justify-between mt-3">
                                {{-- Stepper quantità --}}
                                <form method="POST" action="{{ route('cart.items.update', $item['product_id']) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <button name="quantity" value="{{ $item['quantity'] - 1 }}" type="submit"
                                            class="w-7 h-7 rounded-full border border-neutral-300 flex items-center justify-center hover:border-brand-primary hover:text-brand-primary transition-colors">−</button>
                                    <span class="w-6 text-center text-sm font-medium">{{ $item['quantity'] }}</span>
                                    <button name="quantity" value="{{ $item['quantity'] + 1 }}" type="submit"
                                            class="w-7 h-7 rounded-full border border-neutral-300 flex items-center justify-center hover:border-brand-primary hover:text-brand-primary transition-colors">+</button>
                                </form>
                                <p class="font-semibold text-neutral-900">€{{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Sidebar riepilogo --}}
                <div class="space-y-4">

                    {{-- Codice sconto --}}
                    <div class="bg-white rounded-2xl shadow-sm p-4">
                        <h3 class="font-medium text-neutral-900 mb-3">Codice sconto</h3>
                        <form method="POST" action="{{ route('cart.coupon.store') }}" class="flex gap-2">
                            @csrf
                            <input type="text" name="code" placeholder="CODICE"
                                   class="flex-1 px-3 py-2 border border-neutral-300 rounded-xl text-sm focus:outline-none focus:border-brand-primary transition-colors">
                            <button type="submit"
                                    class="px-4 py-2 bg-neutral-900 text-white rounded-xl text-sm font-medium hover:bg-neutral-700 transition-colors">
                                Applica
                            </button>
                        </form>
                        @if(session('coupon_error'))
                            <p class="text-xs text-red-500 mt-2">{{ session('coupon_error') }}</p>
                        @endif
                        @if(session('coupon_success'))
                            <p class="text-xs text-green-600 mt-2">{{ session('coupon_success') }}</p>
                        @endif
                    </div>

                    {{-- Totali --}}
                    <div class="bg-white rounded-2xl shadow-sm p-4 space-y-3">
                        <h3 class="font-medium text-neutral-900 mb-1">Riepilogo ordine</h3>
                        <div class="flex justify-between text-sm">
                            <span class="text-neutral-600">Subtotale</span>
                            <span>€{{ number_format($total, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-neutral-600">Spedizione</span>
                            <span>{{ $shipping == 0 ? 'Gratuita' : '€'.number_format($shipping, 2, ',', '.') }}</span>
                        </div>
                        @if($shipping > 0)
                            <p class="text-xs text-neutral-400">Spedizione gratuita per ordini ≥ €99</p>
                        @endif
                        <div class="border-t border-neutral-200 pt-3 flex justify-between font-semibold">
                            <span>Totale</span>
                            <span class="text-lg text-brand-primary">€{{ number_format($total + $shipping, 2, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('checkout.index') }}"
                           class="block w-full text-center py-3 bg-brand-primary text-white rounded-xl font-semibold hover:bg-teal-700 transition-colors mt-2">
                            Procedi al checkout →
                        </a>
                        <a href="{{ route('shop.index') }}"
                           class="block w-full text-center py-2 text-sm text-neutral-500 hover:text-neutral-700">
                            ← Continua lo shopping
                        </a>
                    </div>
                </div>

            </div>
        @endif
    </x-public.container>
</div>
@endsection
