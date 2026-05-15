<x-public.account-layout title="La mia wishlist">

  @php
    $customer = auth('customer')->user();
    $wishlistIds = $customer ? session('skintemple_wishlist_' . $customer->id, []) : [];
    $products = $wishlistIds
      ? \App\Models\Product::whereIn('id', $wishlistIds)->published()->with(['media', 'brand'])->get()
      : collect();
  @endphp

  <div>
    <h1 class="font-display text-2xl text-brand-ink mb-6">La mia wishlist</h1>

    @if($products->isEmpty())
      <div class="bg-white rounded-2xl shadow-soft-sm p-12 text-center">
        <svg class="w-14 h-14 text-neutral-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
        </svg>
        <h2 class="font-semibold text-brand-ink mb-2">La tua wishlist è vuota</h2>
        <p class="text-neutral-500 text-sm mb-6">Salva i tuoi prodotti preferiti per trovarli facilmente.</p>
        <a href="{{ route('shop.index') }}" class="btn-primary inline-block">Esplora i prodotti →</a>
      </div>
    @else
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4 lg:gap-6">
        @foreach($products as $product)
          <x-public.product-card :product="$product" />
        @endforeach
      </div>
    @endif
  </div>

</x-public.account-layout>
