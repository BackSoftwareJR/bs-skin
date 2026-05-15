@props(['title' => null])

<x-layouts.app :title="$title">
  <x-public.container class="py-10 lg:py-14">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

      {{-- Sidebar --}}
      <aside class="lg:col-span-1">
        <nav class="bg-white rounded-2xl shadow-soft-sm p-4 space-y-1">
          @php
          $customer = auth('customer')->user();
          $navLinks = [
            ['route' => 'account.dashboard',     'label' => 'Dashboard',       'icon' => 'home'],
            ['route' => 'account.orders.index',  'label' => 'I miei ordini',   'icon' => 'shopping-bag'],
            ['route' => 'account.addresses.index','label' => 'Indirizzi',      'icon' => 'map-pin'],
            ['route' => 'account.wishlist.index', 'label' => 'Wishlist',        'icon' => 'heart'],
            ['route' => 'account.profile.edit',  'label' => 'Profilo',         'icon' => 'user'],
          ];
          @endphp

          @if($customer)
          <div class="px-4 py-3 mb-2 border-b border-neutral-100">
            <p class="text-xs text-neutral-400 uppercase tracking-wide font-medium">Accesso come</p>
            <p class="text-sm font-semibold text-brand-ink mt-0.5 truncate">{{ $customer->name ?: $customer->email }}</p>
          </div>
          @endif

          @foreach($navLinks as $link)
            <a href="{{ route($link['route']) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs($link['route']) ? 'bg-brand-primary/10 text-brand-primary' : 'text-neutral-600 hover:bg-neutral-50 hover:text-brand-primary' }}">
              @if($link['icon'] === 'home')
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
              @elseif($link['icon'] === 'shopping-bag')
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
              @elseif($link['icon'] === 'map-pin')
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
              @elseif($link['icon'] === 'heart')
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
              @elseif($link['icon'] === 'user')
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
              @endif
              {{ $link['label'] }}
            </a>
          @endforeach

          <form action="{{ route('account.logout') }}" method="POST" class="pt-2 border-t border-neutral-100">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-neutral-400 hover:text-danger hover:bg-red-50 w-full transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
              Esci
            </button>
          </form>
        </nav>
      </aside>

      {{-- Main content --}}
      <main class="lg:col-span-3">
        {{ $slot }}
      </main>

    </div>
  </x-public.container>
</x-layouts.app>
