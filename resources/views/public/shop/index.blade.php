@extends('layouts.app')

@section('title', 'Shop - SkinTemple')

@push('meta')
    <meta name="description" content="Scopri tutti i prodotti SkinTemple: tecnologie multifunzione, cosmetici professionali e monouso per il centro estetico.">
@endpush

@section('content')
<div class="min-h-screen bg-surface">
    <x-public.container class="py-8">
        <!-- Breadcrumb -->
        <x-public.breadcrumb :items="[['label' => 'Shop']]" />

        <!-- Header -->
        <div class="mb-8">
            <h1 class="font-display text-3xl font-semibold text-neutral-900 mb-2">Tutti i prodotti</h1>
            <p class="text-neutral-600">Scopri la nostra gamma completa di prodotti per il centro estetico</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar filtri (desktop) -->
            <aside class="hidden lg:block w-64 flex-shrink-0">
                <div class="sticky top-8">
                    <livewire:public.catalog.product-filters />
                </div>
            </aside>

            <!-- Main content -->
            <main class="flex-1 min-w-0">
                <!-- Mobile: bottone filtri -->
                <div class="lg:hidden mb-6">
                    <button @click="$dispatch('open-filters')" 
                            class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-white border border-neutral-300 rounded-xl text-sm font-medium text-neutral-700 hover:bg-neutral-50 transition-colors">
                        <x-heroicon-o-funnel class="h-4 w-4" />
                        Filtra prodotti
                    </button>
                </div>

                <!-- Product grid -->
                <livewire:public.catalog.product-grid />
            </main>
        </div>
    </x-public.container>

    <!-- Mobile filters drawer -->
    <div x-data="{ open: false }" 
         @open-filters.window="open = true">
        <div x-show="open"
             style="display:none"
             x-transition:enter="transition ease-apple duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-trap.noscroll="open"
             class="fixed inset-0 z-50 lg:hidden">
            <!-- Backdrop -->
            <div @click="open = false" class="absolute inset-0 bg-black/50"></div>
            
            <!-- Drawer -->
            <div x-show="open"
                 style="display:none"
                 x-transition:enter="transition ease-apple duration-300"
                 x-transition:enter-start="translate-y-full"
                 x-transition:enter-end="translate-y-0"
                 class="absolute bottom-0 left-0 right-0 bg-white rounded-t-2xl shadow-soft-xl max-h-[80vh] flex flex-col">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-900">Filtri</h3>
                    <button @click="open = false" class="p-1 text-neutral-500 hover:text-neutral-700">
                        <x-heroicon-o-x-mark class="h-5 w-5" />
                    </button>
                </div>
                
                <!-- Content -->
                <div class="flex-1 overflow-y-auto p-4">
                    <livewire:public.catalog.product-filters />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection