@extends('layouts.app')

@php
    $product = $product ?? null;
@endphp

@if($product)
    @section('title', $product->name . ' - SkinTemple')
    
    @push('meta')
        <meta name="description" content="{{ $product->meta_description ?: Str::limit($product->description, 160) }}">
        <meta property="og:title" content="{{ $product->name }}">
        <meta property="og:description" content="{{ Str::limit($product->description, 160) }}">
        @if($product->featured_image)
            <meta property="og:image" content="{{ $product->featured_image }}">
        @endif
    @endpush
@endif

<div class="min-h-screen bg-surface">
    <x-public.container class="py-8">
        @if($product)
            <!-- Breadcrumb -->
            @php
                $breadcrumbItems = [
                    ['label' => 'Shop', 'url' => '/shop']
                ];
                
                if($product->categories->isNotEmpty()) {
                    $category = $product->categories->first();
                    $breadcrumbItems[] = ['label' => $category->name, 'url' => '/shop?categories[]=' . $category->id];
                }
                
                $breadcrumbItems[] = ['label' => $product->name];
            @endphp
            <x-public.breadcrumb :items="$breadcrumbItems" />

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16">
                <!-- Gallery -->
                <div class="space-y-4">
                    <!-- Main image -->
                    <div class="aspect-square rounded-2xl overflow-hidden bg-white">
                        @if($product->featured_image)
                            <img src="{{ $product->featured_image }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-contain p-8">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-neutral-400">
                                <x-heroicon-o-photo class="h-24 w-24" />
                            </div>
                        @endif
                    </div>
                    
                    <!-- Thumbnails (se ci sono più immagini) -->
                    @if($product->media && $product->media->count() > 1)
                        <div class="flex gap-2 overflow-x-auto">
                            @foreach($product->media->take(5) as $media)
                                <button class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden bg-white border-2 border-transparent hover:border-brand-primary transition-colors">
                                    <img src="{{ $media->url }}" alt="" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="space-y-6">
                    <!-- Brand -->
                    @if($product->brand)
                        <p class="text-2xs font-semibold uppercase tracking-widest text-brand-primary">
                            {{ $product->brand->name }}
                        </p>
                    @endif

                    <!-- Name -->
                    <h1 class="font-display text-3xl lg:text-4xl font-semibold text-neutral-900 {{ $product->type === 'device' ? 'uppercase tracking-wide' : '' }}">
                        {{ $product->name }}
                    </h1>

                    <!-- Reviews placeholder -->
                    <div class="flex items-center gap-2">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <x-heroicon-s-star class="h-4 w-4 text-yellow-400" />
                            @endfor
                        </div>
                        <span class="text-sm text-neutral-600">(23 recensioni)</span>
                    </div>

                    <!-- Price -->
                    <div class="space-y-2">
                        @if($product->type === 'device')
                            <!-- Device pricing with rent/buy options -->
                            <div class="space-y-3">
                                <p class="text-sm font-medium text-neutral-700">Modalità di fornitura:</p>
                                <div class="flex gap-3">
                                    <div class="flex-1 p-4 border-2 border-brand-primary bg-brand-primary/5 rounded-xl">
                                        <p class="text-sm font-medium text-brand-primary mb-1">Acquisto</p>
                                        <p class="text-2xl font-bold text-brand-primary">€4.200</p>
                                    </div>
                                    <div class="flex-1 p-4 border-2 border-neutral-300 rounded-xl">
                                        <p class="text-sm font-medium text-neutral-700 mb-1">Noleggio</p>
                                        <p class="text-2xl font-bold text-neutral-900">€180<span class="text-base font-normal">/mese</span></p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Regular product pricing -->
                            <div class="flex items-center gap-4">
                                @if($product->compare_at_price)
                                    <span class="text-lg text-neutral-500 line-through">€{{ number_format($product->compare_at_price, 2, ',', '.') }}</span>
                                    <span class="text-3xl font-bold text-brand-primary">€{{ number_format($product->price, 2, ',', '.') }}</span>
                                @else
                                    <span class="text-3xl font-bold text-neutral-900">€{{ number_format($product->price, 2, ',', '.') }}</span>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Description -->
                    @if($product->short_description)
                        <div class="prose prose-neutral max-w-none">
                            <p>{{ $product->short_description }}</p>
                        </div>
                    @endif

                    <!-- Variants (se presenti) -->
                    @if($product->variants && $product->variants->count() > 1)
                        <div>
                            <p class="text-sm font-medium text-neutral-700 mb-3">Formato:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($product->variants as $variant)
                                    <button class="px-4 py-2 border border-neutral-300 rounded-lg text-sm hover:border-brand-primary transition-colors">
                                        {{ $variant->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($product->type !== 'device')
                        <!-- Quantity + Add to Cart -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">Quantità</label>
                                <x-public.quantity-stepper value="1" />
                            </div>
                            
                            <div class="flex gap-3">
                                <div class="flex-1">
                                    <livewire:public.cart.add-to-cart-button :product-id="$product->id" />
                                </div>
                                <livewire:public.cart.wishlist-toggle :product-id="$product->id" />
                            </div>
                        </div>
                    @else
                        <!-- Device CTA buttons -->
                        <div class="space-y-3">
                            <a href="/contatti?device={{ $product->slug }}" class="btn-primary w-full text-center">
                                Richiedi demo
                            </a>
                            <button class="btn-secondary w-full">
                                Acquista subito
                            </button>
                        </div>
                    @endif

                    <!-- Trust signals -->
                    <div class="space-y-2 pt-4 border-t border-neutral-200">
                        <div class="flex items-center gap-2 text-sm text-neutral-600">
                            <x-heroicon-o-truck class="h-4 w-4 text-success" />
                            <span>Spedizione gratuita per ordini > €59</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-neutral-600">
                            <x-heroicon-o-flag class="h-4 w-4 text-success" />
                            <span>100% Made in Italy</span>
                        </div>
                        @if($product->type === 'device')
                            <div class="flex items-center gap-2 text-sm text-neutral-600">
                                <x-heroicon-o-shield-check class="h-4 w-4 text-success" />
                                <span>Garanzia 24 mesi</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-neutral-600">
                                <x-heroicon-o-academic-cap class="h-4 w-4 text-success" />
                                <span>Formazione operatore inclusa</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Tabs -->
            <div class="mt-16" x-data="{ activeTab: 'description' }">
                <div class="border-b border-neutral-200">
                    <nav class="flex space-x-8">
                        <button @click="activeTab = 'description'" 
                                :class="activeTab === 'description' ? 'border-brand-primary text-brand-primary' : 'border-transparent text-neutral-500 hover:text-neutral-700'"
                                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                            Descrizione
                        </button>
                        
                        @if($product->type === 'cosmetic')
                            <button @click="activeTab = 'ingredients'" 
                                    :class="activeTab === 'ingredients' ? 'border-brand-primary text-brand-primary' : 'border-transparent text-neutral-500 hover:text-neutral-700'"
                                    class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                Ingredienti
                            </button>
                        @endif

                        @if($product->type === 'device')
                            <button @click="activeTab = 'specs'" 
                                    :class="activeTab === 'specs' ? 'border-brand-primary text-brand-primary' : 'border-transparent text-neutral-500 hover:text-neutral-700'"
                                    class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                Specifiche tecniche
                            </button>
                        @endif

                        <button @click="activeTab = 'reviews'" 
                                :class="activeTab === 'reviews' ? 'border-brand-primary text-brand-primary' : 'border-transparent text-neutral-500 hover:text-neutral-700'"
                                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                            Recensioni (23)
                        </button>
                    </nav>
                </div>

                <div class="py-8">
                    <!-- Description Tab -->
                    <div x-show="activeTab === 'description'" class="prose prose-neutral max-w-none">
                        {!! $product->description !!}
                    </div>

                    <!-- Ingredients Tab -->
                    @if($product->type === 'cosmetic')
                        <div x-show="activeTab === 'ingredients'" class="prose prose-neutral max-w-none">
                            <h3>Ingredienti (INCI)</h3>
                            <p>{{ $product->ingredients ?: 'Informazioni sugli ingredienti non disponibili.' }}</p>
                        </div>
                    @endif

                    <!-- Specs Tab -->
                    @if($product->type === 'device')
                        <div x-show="activeTab === 'specs'">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <h3 class="text-lg font-semibold text-neutral-900 mb-4">Specifiche tecniche</h3>
                                    <div class="space-y-3">
                                        <div class="flex justify-between py-2 border-b border-neutral-100">
                                            <span class="text-neutral-600">Tecnologia</span>
                                            <span class="font-medium">Pressoterapia sequenziale</span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-neutral-100">
                                            <span class="text-neutral-600">Programmi</span>
                                            <span class="font-medium">12 preimpostati</span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-neutral-100">
                                            <span class="text-neutral-600">Dimensioni</span>
                                            <span class="font-medium">45 x 35 x 25 cm</span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-neutral-100">
                                            <span class="text-neutral-600">Peso</span>
                                            <span class="font-medium">8.5 kg</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-neutral-900 mb-4">Certificazioni</h3>
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2">
                                            <x-heroicon-s-check-badge class="h-5 w-5 text-success" />
                                            <span class="text-sm">CE Medical Device</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <x-heroicon-s-check-badge class="h-5 w-5 text-success" />
                                            <span class="text-sm">ISO 13485</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <x-heroicon-s-check-badge class="h-5 w-5 text-success" />
                                            <span class="text-sm">Made in Italy</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Reviews Tab -->
                    <div x-show="activeTab === 'reviews'">
                        <p class="text-neutral-600">Le recensioni saranno disponibili a breve.</p>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <div class="mt-16">
                <h2 class="font-display text-2xl font-semibold text-neutral-900 mb-8">Prodotti correlati</h2>
                <livewire:public.catalog.product-grid :limit="4" />
            </div>
            
        @else
            <!-- Product not found -->
            <div class="text-center py-16">
                <h1 class="text-2xl font-semibold text-neutral-900 mb-4">Prodotto non trovato</h1>
                <p class="text-neutral-600 mb-8">Il prodotto che stai cercando non esiste o è stato rimosso.</p>
                <a href="/shop" class="btn-primary">Torna allo shop</a>
            </div>
        @endif
    </x-public.container>
</div>