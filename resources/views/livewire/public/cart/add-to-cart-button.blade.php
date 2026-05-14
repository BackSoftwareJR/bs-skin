<button wire:click="addToCart"
        wire:loading.attr="disabled"
        {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 w-full btn-primary text-center disabled:opacity-50']) }}>
    
    <!-- Loading state -->
    <span wire:loading wire:target="addToCart" class="flex items-center gap-2">
        <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
        Aggiungendo...
    </span>
    
    <!-- Success state -->
    <span x-show="@js($added)" x-transition class="flex items-center gap-2 text-white">
        <x-heroicon-m-check class="h-4 w-4" />
        Aggiunto!
    </span>
    
    <!-- Default state -->
    <span wire:loading.remove wire:target="addToCart" x-show="!@js($added)" x-transition>
        {{ $buttonText }}
    </span>
</button>