<div 
    x-data="{ show: !localStorage.getItem('announcement-dismissed') }"
    x-show="show"
    x-transition:leave="transition-all duration-300"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform -translate-y-full"
    class="bg-brand-primary text-white py-2 text-xs text-center"
>
    <x-public.container>
        <div class="flex items-center justify-between">
            <div class="flex-1 text-center">
                <span>{{ config('skintemple.announcement_text', 'Spedizione gratuita sopra €99 · Made in Italy') }}</span>
            </div>
            <button 
                @click="show = false; localStorage.setItem('announcement-dismissed', 'true')"
                class="ml-4 text-white/70 hover:text-white transition-colors"
                aria-label="Chiudi"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </x-public.container>
</div>