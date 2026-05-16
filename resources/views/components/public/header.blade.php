@inject('cartService', 'App\Services\CartService')
<div x-data="{ mobileMenuOpen: false, productsOpen: false, scrolled: false }"
     @scroll.window="scrolled = (window.pageYOffset || document.documentElement.scrollTop) > 8">
<header
    class="sticky top-0 z-50 bg-white/95 backdrop-blur-md supports-[backdrop-filter]:bg-white/80 transition-[box-shadow,border-color] duration-200 border-b"
    :class="scrolled ? 'shadow-md border-brand-border/50' : 'shadow-soft-sm border-transparent'"
>
    <x-public.container>
        <div class="flex items-center justify-between h-16 lg:h-20">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="block">
                    <x-public.brand-logo class="h-8 lg:h-10" />
                </a>
            </div>

            <!-- Navigation desktop -->
            <nav class="hidden lg:flex items-center space-x-8">
                <a href="{{ route('home') }}"
                   class="text-sm font-medium {{ request()->routeIs('home') ? 'text-brand-primary border-b-2 border-brand-primary pb-1' : 'text-brand-ink-soft hover:text-brand-primary' }} transition-colors">
                    Home
                </a>

                <!-- Prodotti dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.away="open = false"
                            class="flex items-center space-x-1 text-sm font-medium {{ request()->routeIs('shop.*', 'category.*', 'product.*') ? 'text-brand-primary' : 'text-brand-ink-soft hover:text-brand-primary' }} transition-colors">
                        <span>Prodotti</span>
                        <svg class="w-4 h-4 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open"
                         style="display:none"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute top-full left-0 mt-2 w-52 bg-white rounded-xl shadow-soft-lg border border-brand-border py-2">
                        <a href="{{ route('shop.index') }}" class="block px-4 py-2.5 text-sm text-brand-ink-soft hover:text-brand-primary hover:bg-brand-primary-soft transition-colors font-medium">
                            Tutti i prodotti
                        </a>
                        <div class="border-t border-brand-border/50 my-1"></div>
                        <a href="{{ route('category.show', 'monouso') }}" class="block px-4 py-2 text-sm text-brand-ink-soft hover:text-brand-primary hover:bg-brand-primary-soft transition-colors">Monouso</a>
                        <a href="{{ route('category.show', 'cosmetici') }}" class="block px-4 py-2 text-sm text-brand-ink-soft hover:text-brand-primary hover:bg-brand-primary-soft transition-colors">Cosmetici</a>
                        <a href="{{ route('category.show', 'tecnologie') }}" class="block px-4 py-2 text-sm text-brand-ink-soft hover:text-brand-primary hover:bg-brand-primary-soft transition-colors">Tecnologie</a>
                    </div>
                </div>

                <a href="{{ route('technologies.index') }}"
                   class="text-sm font-medium {{ request()->routeIs('technologies.index') ? 'text-brand-primary border-b-2 border-brand-primary pb-1' : 'text-brand-ink-soft hover:text-brand-primary' }} transition-colors">
                    Tecnologie
                </a>

                <a href="{{ route('about') }}"
                   class="text-sm font-medium {{ request()->routeIs('about') ? 'text-brand-primary border-b-2 border-brand-primary pb-1' : 'text-brand-ink-soft hover:text-brand-primary' }} transition-colors">
                    Chi Siamo
                </a>

                <a href="/supporto"
                   class="text-sm font-medium {{ request()->is('supporto') ? 'text-brand-primary border-b-2 border-brand-primary pb-1' : 'text-brand-ink-soft hover:text-brand-primary' }} transition-colors">
                    Supporto
                </a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center space-x-1 lg:space-x-2">
                <!-- Search -->
                <button @click="$dispatch('open-search')"
                        aria-label="Cerca"
                        class="p-2 text-brand-ink-soft hover:text-brand-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                <!-- Account -->
                @auth
                    <a href="{{ route('account.dashboard') }}"
                       aria-label="Account"
                       class="p-2 text-brand-ink-soft hover:text-brand-primary transition-colors">
                @else
                    <a href="{{ route('account.login') }}"
                       aria-label="Accedi"
                       class="p-2 text-brand-ink-soft hover:text-brand-primary transition-colors">
                @endauth
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </a>

                <!-- Cart -->
                <button @click="$dispatch('open-cart-drawer')"
                        aria-label="Carrello"
                        class="relative p-2 text-brand-ink-soft hover:text-brand-primary transition-colors"
                        x-data
                        @cart-updated.window="$event.detail.count > 0 ? ($el.querySelector('.cart-badge').textContent = $event.detail.count, $el.querySelector('.cart-badge').classList.remove('hidden'), $el.querySelector('.cart-badge').classList.add('flex')) : ($el.querySelector('.cart-badge').classList.add('hidden'), $el.querySelector('.cart-badge').classList.remove('flex'))">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293A1 1 0 004 16v0a1 1 0 001 1h10"></path>
                    </svg>
                    @php $cartCount = $cartService->count(); @endphp
                    <span class="cart-badge absolute -top-1 -right-1 bg-brand-primary text-white text-2xs font-medium rounded-full h-5 w-5 items-center justify-center {{ $cartCount > 0 ? 'flex' : 'hidden' }}">{{ $cartCount > 0 ? $cartCount : '' }}</span>
                </button>

                <!-- CTA desktop -->
                <a href="{{ route('contact') }}" class="hidden lg:inline-flex ml-2 items-center px-4 py-2 bg-brand-primary text-white text-sm font-semibold rounded-xl hover:bg-brand-primary/90 transition-colors">
                    Contattaci
                </a>

                <!-- Mobile menu button -->
                <button @click="mobileMenuOpen = true"
                        aria-label="Apri menu"
                        class="lg:hidden p-2 text-brand-ink-soft hover:text-brand-primary transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </x-public.container>
</header>

<!-- Mobile drawer overlay -->
<div x-show="mobileMenuOpen"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="mobileMenuOpen = false"
     class="fixed inset-0 bg-black/40 z-[60] lg:hidden backdrop-blur-sm"
     style="display: none;">
</div>

<!-- Mobile drawer panel -->
<div x-show="mobileMenuOpen"
     x-transition:enter="transition ease-out duration-250"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="fixed inset-y-0 left-0 z-[60] w-80 max-w-[85vw] bg-white shadow-2xl lg:hidden flex flex-col"
     style="display: none;"
     @keydown.escape.window="mobileMenuOpen = false">

    <!-- Drawer header -->
    <div class="flex items-center justify-between px-5 py-4 border-b border-brand-border">
        <a href="{{ route('home') }}" @click="mobileMenuOpen = false">
            <x-public.brand-logo class="h-8" />
        </a>
        <button @click="mobileMenuOpen = false"
                aria-label="Chiudi menu"
                class="p-2 text-brand-ink-soft hover:text-brand-primary transition-colors rounded-lg hover:bg-neutral-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Drawer nav -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
        <a href="{{ route('home') }}"
           @click="mobileMenuOpen = false"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('home') ? 'bg-brand-primary-soft text-brand-primary' : 'text-brand-ink-soft hover:bg-neutral-50 hover:text-brand-primary' }} transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Home
        </a>

        <!-- Prodotti con sub-menu -->
        <div>
            <button @click="productsOpen = !productsOpen"
                    class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('shop.*', 'category.*', 'product.*') ? 'bg-brand-primary-soft text-brand-primary' : 'text-brand-ink-soft hover:bg-neutral-50 hover:text-brand-primary' }} transition-colors">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Prodotti
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': productsOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="productsOpen" x-transition class="ml-4 mt-1 space-y-1 border-l-2 border-brand-primary-soft pl-4">
                <a href="{{ route('shop.index') }}"
                   @click="mobileMenuOpen = false"
                   class="block px-3 py-2 text-sm text-brand-ink-soft hover:text-brand-primary transition-colors font-medium">
                    Tutti i prodotti
                </a>
                <a href="{{ route('category.show', 'monouso') }}"
                   @click="mobileMenuOpen = false"
                   class="block px-3 py-2 text-sm text-brand-ink-soft hover:text-brand-primary transition-colors">
                    Monouso
                </a>
                <a href="{{ route('category.show', 'cosmetici') }}"
                   @click="mobileMenuOpen = false"
                   class="block px-3 py-2 text-sm text-brand-ink-soft hover:text-brand-primary transition-colors">
                    Cosmetici
                </a>
                <a href="{{ route('category.show', 'tecnologie') }}"
                   @click="mobileMenuOpen = false"
                   class="block px-3 py-2 text-sm text-brand-ink-soft hover:text-brand-primary transition-colors">
                    Tecnologie
                </a>
            </div>
        </div>

        <a href="{{ route('technologies.index') }}"
           @click="mobileMenuOpen = false"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('technologies.index') ? 'bg-brand-primary-soft text-brand-primary' : 'text-brand-ink-soft hover:bg-neutral-50 hover:text-brand-primary' }} transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
            </svg>
            Tecnologie
        </a>

        <a href="{{ route('about') }}"
           @click="mobileMenuOpen = false"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('about') ? 'bg-brand-primary-soft text-brand-primary' : 'text-brand-ink-soft hover:bg-neutral-50 hover:text-brand-primary' }} transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Chi Siamo
        </a>

        <a href="/supporto"
           @click="mobileMenuOpen = false"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->is('supporto') ? 'bg-brand-primary-soft text-brand-primary' : 'text-brand-ink-soft hover:bg-neutral-50 hover:text-brand-primary' }} transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            Supporto
        </a>
    </nav>

    <!-- Drawer footer actions -->
    <div class="px-4 py-5 border-t border-brand-border space-y-3">
        <a href="{{ route('contact') }}"
           @click="mobileMenuOpen = false"
           class="flex items-center justify-center gap-2 w-full bg-brand-primary text-white text-sm font-semibold py-3 rounded-xl hover:bg-brand-primary/90 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Contattaci
        </a>
        @auth
            <a href="{{ route('account.dashboard') }}"
               @click="mobileMenuOpen = false"
               class="flex items-center justify-center gap-2 w-full border border-brand-border text-brand-ink text-sm font-medium py-3 rounded-xl hover:bg-neutral-50 transition-colors">
                Il mio account
            </a>
        @else
            <a href="{{ route('account.login') }}"
               @click="mobileMenuOpen = false"
               class="flex items-center justify-center gap-2 w-full border border-brand-border text-brand-ink text-sm font-medium py-3 rounded-xl hover:bg-neutral-50 transition-colors">
                Accedi
            </a>
        @endauth
    </div>
</div>
</div>
