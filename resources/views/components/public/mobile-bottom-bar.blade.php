<div class="fixed bottom-0 left-0 right-0 z-40 md:hidden bg-white/95 backdrop-blur-md supports-[backdrop-filter]:bg-white/90 shadow-soft-xl border-t border-brand-border"
     style="padding-bottom: env(safe-area-inset-bottom);">
    <div class="grid grid-cols-5 h-16">
        <!-- Home -->
        <a href="{{ route('home') }}" 
           class="flex flex-col items-center justify-center space-y-1 {{ request()->routeIs('home') ? 'text-brand-primary' : 'text-neutral-400' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="text-2xs font-medium">Home</span>
        </a>

        <!-- Shop -->
        <a href="{{ route('shop.index') }}" 
           class="flex flex-col items-center justify-center space-y-1 {{ request()->routeIs('shop.*', 'category.*', 'product.*') ? 'text-brand-primary' : 'text-neutral-400' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <span class="text-2xs font-medium">Shop</span>
        </a>

        <!-- Search -->
        <button @click="$dispatch('open-search')"
                x-data
                class="flex flex-col items-center justify-center space-y-1 text-neutral-400 hover:text-brand-primary transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <span class="text-2xs font-medium">Cerca</span>
        </button>

        <!-- Account -->
        <a href="{{ route('account.login') }}" 
           class="flex flex-col items-center justify-center space-y-1 {{ request()->routeIs('account.*') ? 'text-brand-primary' : 'text-neutral-400' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-2xs font-medium">Account</span>
        </a>

        <!-- Cart -->
        <button @click="$dispatch('open-cart-drawer')"
                x-data
                class="relative flex flex-col items-center justify-center space-y-1 text-neutral-400 hover:text-brand-primary transition-colors"
                @cart-updated.window="$event.detail.count > 0 ? ($el.querySelector('.cart-badge').textContent = $event.detail.count, $el.querySelector('.cart-badge').classList.remove('hidden'), $el.querySelector('.cart-badge').classList.add('flex')) : ($el.querySelector('.cart-badge').classList.add('hidden'), $el.querySelector('.cart-badge').classList.remove('flex'))">
            <div class="relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293A1 1 0 004 16v0a1 1 0 001 1h10"></path>
                </svg>
                <span class="cart-badge absolute -top-2 -right-2 bg-brand-primary text-white text-2xs font-medium rounded-full h-4 w-4 items-center justify-center hidden"></span>
            </div>
            <span class="text-2xs font-medium">Carrello</span>
        </button>
    </div>
</div>