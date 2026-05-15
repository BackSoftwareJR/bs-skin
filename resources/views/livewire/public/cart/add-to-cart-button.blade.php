<div>
    <button wire:click="addToCart"
            wire:loading.attr="disabled"
            {{ $attributes->merge(['class' => 'w-full flex items-center justify-center gap-2 px-6 py-3 bg-brand-primary text-white font-medium rounded-xl hover:bg-teal-700 transition-colors disabled:opacity-60']) }}>

        <span wire:loading.remove wire:target="addToCart">
            @if($added)
                <span class="flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Aggiunto!
                </span>
            @else
                {{ $buttonText }}
            @endif
        </span>

        <span wire:loading wire:target="addToCart" class="flex items-center gap-2">
            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
            </svg>
            Aggiunta...
        </span>
    </button>
</div>
