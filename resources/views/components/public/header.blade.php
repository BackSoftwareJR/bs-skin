<header class="sticky top-0 bg-white shadow-soft-sm z-40">
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
                
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.away="open = false"
                            class="flex items-center space-x-1 text-sm font-medium {{ request()->routeIs('shop.*', 'category.*', 'product.*') ? 'text-brand-primary' : 'text-brand-ink-soft hover:text-brand-primary' }} transition-colors">
                        <span>Prodotti</span>
                        <svg class="w-4 h-4 transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div x-show="open" x-transition class="absolute top-full left-0 mt-2 w-48 bg-white rounded-xl shadow-soft-lg border border-brand-border py-2">
                        <a href="{{ route('shop.index') }}" class="block px-4 py-2 text-sm text-brand-ink-soft hover:text-brand-primary hover:bg-brand-primary-soft transition-colors">Tutti i prodotti</a>
                        <a href="{{ route('category.show', 'tecnologie') }}" class="block px-4 py-2 text-sm text-brand-ink-soft hover:text-brand-primary hover:bg-brand-primary-soft transition-colors">Tecnologie</a>
                        <a href="{{ route('category.show', 'viso-corpo') }}" class="block px-4 py-2 text-sm text-brand-ink-soft hover:text-brand-primary hover:bg-brand-primary-soft transition-colors">Viso e Corpo</a>
                        <a href="{{ route('category.show', 'monouso') }}" class="block px-4 py-2 text-sm text-brand-ink-soft hover:text-brand-primary hover:bg-brand-primary-soft transition-colors">Monouso</a>
                    </div>
                </div>

                <a href="#" class="text-sm font-medium text-brand-ink-soft hover:text-brand-primary transition-colors">Tecnologie</a>
                <a href="#" class="text-sm font-medium text-brand-ink-soft hover:text-brand-primary transition-colors">Chi Siamo</a>
                <a href="#" class="text-sm font-medium text-brand-ink-soft hover:text-brand-primary transition-colors">Blog</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center space-x-4">
                <!-- Search -->
                <button class="p-2 text-brand-ink-soft hover:text-brand-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                <!-- Account -->
                <a href="{{ route('account.login') }}" class="p-2 text-brand-ink-soft hover:text-brand-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </a>

                <!-- Cart -->
                <button class="relative p-2 text-brand-ink-soft hover:text-brand-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293A1 1 0 004 16v0a1 1 0 001 1h10"></path>
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-brand-primary text-white text-2xs font-medium rounded-full h-5 w-5 flex items-center justify-center">3</span>
                </button>

                <!-- CTA -->
                <a href="#" class="hidden lg:inline-flex btn-secondary">Contattaci</a>

                <!-- Mobile menu button -->
                <button class="lg:hidden p-2 text-brand-ink-soft hover:text-brand-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </x-public.container>
</header>