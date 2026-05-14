<button wire:click="toggle" 
        x-data="{ isWishlisted: @entangle('isWishlisted') }"
        :aria-label="isWishlisted ? 'Rimuovi dalla lista desideri' : 'Aggiungi alla lista desideri'"
        class="flex h-9 w-9 items-center justify-center rounded-full transition-all duration-200 hover:bg-white/90 hover:scale-110"
        :class="isWishlisted ? 'text-danger' : 'text-neutral-400 hover:text-danger'">
    
    <!-- Heart icon pieno se in wishlist -->
    <x-heroicon-s-heart x-show="isWishlisted" 
                        x-transition:enter="transition ease-apple duration-200"
                        x-transition:enter-start="scale-50 opacity-0"
                        x-transition:enter-end="scale-100 opacity-100"
                        class="h-5 w-5" />
    
    <!-- Heart icon vuoto se non in wishlist -->
    <x-heroicon-o-heart x-show="!isWishlisted" 
                        x-transition:enter="transition ease-apple duration-200"
                        x-transition:enter-start="scale-50 opacity-0"
                        x-transition:enter-end="scale-100 opacity-100"
                        class="h-5 w-5" />
</button>