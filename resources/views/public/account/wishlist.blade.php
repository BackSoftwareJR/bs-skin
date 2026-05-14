<x-layouts.app>
  <x-public.container class="py-12">
    <h1 class="font-display text-3xl mb-8">La mia wishlist</h1>
    @php
      $customerId = session('skintemple_customer_id');
      $customer = $customerId ? \App\Models\Customer::find($customerId) : null;
      // La wishlist è salvata come JSON su customer o come tabella separata
      // Per ora recuperiamo i product_ids dalla sessione o da un campo JSON
      $wishlistIds = session('skintemple_wishlist_' . $customerId, []);
      $products = $wishlistIds ? \App\Models\Product::whereIn('id', $wishlistIds)->published()->with(['media', 'brand'])->get() : collect();
    @endphp
    @if($products->isEmpty())
      <div class="text-center py-16 text-neutral-400">
        <x-heroicon-o-heart class="h-12 w-12 mx-auto mb-4 text-neutral-200" />
        <p>La tua wishlist è vuota. <a href="{{ route('shop.index') }}" class="link-teal">Esplora i prodotti →</a></p>
      </div>
    @else
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
          <x-public.product-card :product="$product" />
        @endforeach
      </div>
    @endif
  </x-public.container>
</x-layouts.app>