<x-public.account-layout title="La mia wishlist">

  @php
    $customer    = auth('customer')->user();
    $wishlistIds = $customer ? session('skintemple_wishlist_' . $customer->id, []) : [];
    $products    = ($customer && !empty($wishlistIds))
        ? \App\Models\Product::whereIn('id', $wishlistIds)->with(['media', 'brand'])->get()
        : collect();
  @endphp

  <div class="bg-white rounded-2xl shadow-soft-sm p-6">
    <div class="flex items-center justify-between mb-6">
      <h1 class="font-semibold text-xl text-neutral-900">La mia wishlist</h1>
      @if($products->isNotEmpty())
        <span class="text-sm text-neutral-500">{{ $products->count() }} {{ $products->count() === 1 ? 'prodotto' : 'prodotti' }}</span>
      @endif
    </div>

    @if($products->isEmpty())
      <div class="text-center py-16">
        <svg class="h-12 w-12 mx-auto mb-4 text-neutral-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        <p class="text-neutral-500 mb-6">La tua wishlist è vuota.</p>
        <a href="{{ route('shop.index') }}"
           class="inline-block bg-brand-primary text-white text-sm font-medium px-5 py-2.5 rounded-xl hover:bg-brand-primary/90 transition-colors">
          Esplora i prodotti →
        </a>
      </div>
    @else
      <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
        @foreach($products as $product)
          <x-public.product-card :product="$product" />
        @endforeach
      </div>
    @endif
  </div>

</x-public.account-layout>
