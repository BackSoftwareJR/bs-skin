<x-public.account-layout title="Dashboard">

  @php $customer = auth('customer')->user(); @endphp

  <div class="space-y-6">
    {{-- Welcome banner --}}
    <div class="bg-gradient-to-r from-brand-primary to-brand-primary/80 rounded-2xl p-6 text-white">
      <h1 class="font-display text-2xl mb-1">
        Bentornato{{ $customer?->name ? ', ' . explode(' ', $customer->name)[0] : '' }}!
      </h1>
      <p class="text-white/80 text-sm">Gestisci i tuoi ordini, indirizzi e preferenze dal tuo pannello personale.</p>
    </div>

    {{-- Quick stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
      @php
        $ordersCount = $customer ? \App\Models\Order::where('customer_id', $customer->id)->count() : 0;
        $wishlistCount = $customer ? count(session('skintemple_wishlist_' . $customer->id, [])) : 0;
      @endphp
      <div class="bg-white rounded-2xl shadow-soft-sm p-5 text-center">
        <div class="text-3xl font-bold text-brand-primary mb-1">{{ $ordersCount }}</div>
        <p class="text-sm text-neutral-500">Ordini totali</p>
      </div>
      <div class="bg-white rounded-2xl shadow-soft-sm p-5 text-center">
        <div class="text-3xl font-bold text-brand-primary mb-1">{{ $wishlistCount }}</div>
        <p class="text-sm text-neutral-500">Wishlist</p>
      </div>
      <div class="bg-white rounded-2xl shadow-soft-sm p-5 text-center col-span-2 sm:col-span-1">
        <div class="text-3xl font-bold text-brand-primary mb-1">
          <svg class="w-8 h-8 text-brand-primary mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
          </svg>
        </div>
        <p class="text-sm text-neutral-500">Account verificato</p>
      </div>
    </div>

    {{-- Recent orders --}}
    <div class="bg-white rounded-2xl shadow-soft-sm p-6">
      <div class="flex items-center justify-between mb-5">
        <h2 class="font-semibold text-neutral-900">Ultimi ordini</h2>
        <a href="{{ route('account.orders.index') }}" class="text-sm text-brand-primary hover:text-brand-primary/80 font-medium transition-colors">Vedi tutti →</a>
      </div>
      <livewire:public.account.recent-orders />
    </div>

    {{-- Quick links --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <a href="{{ route('account.profile.edit') }}" class="bg-white rounded-2xl shadow-soft-sm p-5 flex items-center gap-4 hover:shadow-soft-md transition-shadow group">
        <div class="w-10 h-10 bg-brand-primary/10 rounded-xl flex items-center justify-center group-hover:bg-brand-primary/20 transition-colors">
          <svg class="w-5 h-5 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
        <div>
          <p class="font-medium text-brand-ink">Modifica profilo</p>
          <p class="text-xs text-neutral-500">Aggiorna nome ed email</p>
        </div>
      </a>
      <a href="{{ route('account.addresses.index') }}" class="bg-white rounded-2xl shadow-soft-sm p-5 flex items-center gap-4 hover:shadow-soft-md transition-shadow group">
        <div class="w-10 h-10 bg-brand-primary/10 rounded-xl flex items-center justify-center group-hover:bg-brand-primary/20 transition-colors">
          <svg class="w-5 h-5 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
        </div>
        <div>
          <p class="font-medium text-brand-ink">I miei indirizzi</p>
          <p class="text-xs text-neutral-500">Gestisci gli indirizzi di spedizione</p>
        </div>
      </a>
    </div>
  </div>

</x-public.account-layout>
