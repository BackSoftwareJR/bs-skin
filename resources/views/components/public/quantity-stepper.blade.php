@props(['value' => 1, 'min' => 1, 'max' => 99])

<div x-data="{ qty: @js($value) }" class="inline-flex items-center rounded-xl border border-neutral-300">
    <button @click="qty > {{ $min }} && qty--" :disabled="qty <= {{ $min }}"
            class="flex h-10 w-10 items-center justify-center text-neutral-600 hover:text-neutral-900 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
            aria-label="Diminuisci quantità">
        <x-heroicon-m-minus class="h-4 w-4" />
    </button>
    <input type="number" x-model="qty" :min="{{ $min }}" :max="{{ $max }}"
           class="h-10 w-12 border-0 bg-transparent text-center text-sm font-semibold text-neutral-900 focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
           aria-label="Quantità">
    <button @click="qty < {{ $max }} && qty++" :disabled="qty >= {{ $max }}"
            class="flex h-10 w-10 items-center justify-center text-neutral-600 hover:text-neutral-900 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
            aria-label="Aumenta quantità">
        <x-heroicon-m-plus class="h-4 w-4" />
    </button>
</div>