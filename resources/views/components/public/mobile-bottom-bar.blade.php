<div class="fixed bottom-0 left-0 right-0 z-50 md:hidden bg-white shadow-soft-xl border-t border-brand-border"
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

        <!-- Cart -->
        <button class="flex flex-col items-center justify-center space-y-1 text-neutral-400 relative">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293A1 1 0 004 16v0a1 1 0 001 1h10"></path>
            </svg>
            <span class="absolute -top-2 -right-2 bg-brand-primary text-white text-2xs font-medium rounded-full h-4 w-4 flex items-center justify-center">3</span>
            <span class="text-2xs font-medium">Carrello</span>
        </button>

        <!-- Account -->
        <a href="{{ route('account.login') }}" 
           class="flex flex-col items-center justify-center space-y-1 {{ request()->routeIs('account.*') ? 'text-brand-primary' : 'text-neutral-400' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-2xs font-medium">Account</span>
        </a>

        <!-- Menu -->
        <button class="flex flex-col items-center justify-center space-y-1 text-neutral-400"
                x-data x-on:click="$dispatch('mobile-menu-toggle')">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <span class="text-2xs font-medium">Menu</span>
        </button>
    </div>
</div>