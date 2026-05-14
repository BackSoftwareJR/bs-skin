<x-layouts.app>
  <x-public.container class="py-12">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      {{-- Sidebar navigazione account --}}
      <aside class="lg:col-span-1">
        <nav class="bg-white rounded-2xl shadow-soft-md p-4 space-y-1">
          @php $links = [
            ['route' => 'account.dashboard', 'label' => 'Dashboard', 'icon' => 'heroicon-o-home'],
            ['route' => 'account.orders.index', 'label' => 'I miei ordini', 'icon' => 'heroicon-o-shopping-bag'],
            ['route' => 'account.addresses.index', 'label' => 'Indirizzi', 'icon' => 'heroicon-o-map-pin'],
            ['route' => 'account.wishlist.index', 'label' => 'Wishlist', 'icon' => 'heroicon-o-heart'],
            ['route' => 'account.profile.edit', 'label' => 'Profilo', 'icon' => 'heroicon-o-user'],
          ]; @endphp
          @foreach($links as $link)
            <a href="{{ route($link['route']) }}" 
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs($link['route']) ? 'bg-brand-primary-soft text-brand-primary' : 'text-neutral-600 hover:bg-neutral-50' }}">
              <x-dynamic-component :component="$link['icon']" class="h-4 w-4" />
              {{ $link['label'] }}
            </a>
          @endforeach
          <form action="{{ route('account.logout') }}" method="POST" class="pt-2 border-t border-neutral-100">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-neutral-400 hover:text-danger w-full transition-colors">
              <x-heroicon-o-arrow-right-on-rectangle class="h-4 w-4" />
              Esci
            </button>
          </form>
        </nav>
      </aside>
      
      {{-- Content area --}}
      <main class="lg:col-span-3 space-y-6">
        {{-- Benvenuto --}}
        <div class="bg-white rounded-2xl shadow-soft-md p-6">
          <h1 class="font-display text-2xl text-neutral-900 mb-1">Bentornato!</h1>
          <p class="text-neutral-500 text-sm">Gestisci i tuoi ordini, indirizzi e preferenze.</p>
        </div>
        
        {{-- Ultimi ordini --}}
        <div class="bg-white rounded-2xl shadow-soft-md p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-neutral-900">Ultimi ordini</h2>
            <a href="{{ route('account.orders.index') }}" class="text-sm link-teal">Vedi tutti →</a>
          </div>
          <livewire:public.account.recent-orders />
        </div>
      </main>
    </div>
  </x-public.container>
</x-layouts.app>