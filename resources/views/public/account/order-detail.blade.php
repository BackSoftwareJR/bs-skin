<x-public.account-layout title="Dettaglio ordine">

  @php
    $customer = auth('customer')->user();
    $order = $customer
        ? \App\Models\Order::where('order_number', $orderNumber)
            ->where('customer_id', $customer->id)
            ->with(['items', 'shipments', 'statusHistory'])
            ->first()
        : null;
  @endphp

  @if(!$order)
    <div class="bg-white rounded-2xl shadow-soft-sm p-12 text-center">
      <svg class="h-12 w-12 mx-auto mb-4 text-neutral-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <h2 class="text-lg font-semibold text-neutral-900 mb-2">Ordine non trovato</h2>
      <p class="text-neutral-500 mb-6">L'ordine che stai cercando non esiste o non ti appartiene.</p>
      <a href="{{ route('account.orders.index') }}"
         class="inline-block bg-brand-primary text-white text-sm font-medium px-5 py-2.5 rounded-xl hover:bg-brand-primary/90 transition-colors">
        ← Torna agli ordini
      </a>
    </div>
  @else
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-neutral-500 mb-6">
      <a href="{{ route('account.dashboard') }}" class="hover:text-brand-primary">Account</a>
      <span>/</span>
      <a href="{{ route('account.orders.index') }}" class="hover:text-brand-primary">Ordini</a>
      <span>/</span>
      <span class="text-neutral-900 font-medium">#{{ $order->order_number }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      {{-- Prodotti + stato spedizione --}}
      <div class="lg:col-span-2 space-y-6">

        {{-- Header ordine --}}
        <div class="bg-white rounded-2xl shadow-soft-sm p-6">
          <div class="flex items-start justify-between flex-wrap gap-4">
            <div>
              <h1 class="font-semibold text-lg text-neutral-900">Ordine #{{ $order->order_number }}</h1>
              <p class="text-sm text-neutral-500 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            @php
              $statusColor = match($order->status?->value ?? 'pending') {
                'delivered'  => 'bg-emerald-50 text-emerald-700',
                'cancelled'  => 'bg-red-50 text-red-700',
                'refunded'   => 'bg-purple-50 text-purple-700',
                'shipped'    => 'bg-sky-50 text-sky-700',
                'paid', 'confirmed', 'processing' => 'bg-blue-50 text-blue-700',
                default      => 'bg-amber-50 text-amber-700',
              };
            @endphp
            <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $statusColor }}">
              {{ $order->status?->label() ?? ucfirst($order->status ?? 'In attesa') }}
            </span>
          </div>
        </div>

        {{-- Prodotti --}}
        <div class="bg-white rounded-2xl shadow-soft-sm p-6">
          <h2 class="font-semibold text-neutral-900 mb-4">Prodotti ordinati</h2>
          <div class="space-y-4">
            @forelse($order->items as $item)
              <div class="flex gap-4 py-4 border-b border-neutral-100 last:border-0">
                <div class="w-16 h-16 bg-neutral-50 rounded-xl flex-shrink-0 overflow-hidden">
                  @if($item->image_url ?? null)
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                  @else
                    <div class="w-full h-full flex items-center justify-center text-neutral-300">
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>
                  @endif
                </div>
                <div class="flex-1 min-w-0">
                  <div class="font-medium text-neutral-900">{{ $item->name }}</div>
                  @if($item->variant_name ?? null)
                    <div class="text-sm text-neutral-400 mt-0.5">{{ $item->variant_name }}</div>
                  @endif
                  <div class="text-sm text-neutral-500 mt-0.5">Qtà: {{ $item->quantity }}</div>
                </div>
                <div class="font-semibold text-neutral-900">€{{ number_format($item->total, 2, ',', '.') }}</div>
              </div>
            @empty
              <p class="text-neutral-400 text-sm">Nessun prodotto trovato.</p>
            @endforelse
          </div>
        </div>

        {{-- Timeline stato --}}
        <div class="bg-white rounded-2xl shadow-soft-sm p-6">
          <h2 class="font-semibold text-neutral-900 mb-4">Stato ordine</h2>
          @php
            $steps = [
              ['key' => 'pending',    'label' => 'Ricevuto'],
              ['key' => 'processing', 'label' => 'In elaborazione'],
              ['key' => 'shipped',    'label' => 'Spedito'],
              ['key' => 'delivered',  'label' => 'Consegnato'],
            ];
            $statusOrder = ['pending' => 0, 'confirmed' => 0, 'paid' => 0, 'processing' => 1, 'shipped' => 2, 'delivered' => 3, 'cancelled' => -1, 'refunded' => -1];
            $currentStep = $statusOrder[$order->status?->value ?? 'pending'] ?? 0;
          @endphp
          <div class="flex items-center gap-0">
            @foreach($steps as $i => $step)
              @php $done = $currentStep >= $i; @endphp
              <div class="flex items-center {{ $i < count($steps) - 1 ? 'flex-1' : '' }}">
                <div class="flex flex-col items-center">
                  <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $done ? 'bg-brand-primary text-white' : 'bg-neutral-100 text-neutral-400' }} transition-colors">
                    @if($done)
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                    @else
                      <div class="w-2 h-2 rounded-full bg-current"></div>
                    @endif
                  </div>
                  <span class="text-xs mt-1.5 text-center {{ $done ? 'text-brand-primary font-medium' : 'text-neutral-400' }}">
                    {{ $step['label'] }}
                  </span>
                </div>
                @if($i < count($steps) - 1)
                  <div class="flex-1 h-0.5 mx-2 mb-4 {{ $currentStep > $i ? 'bg-brand-primary' : 'bg-neutral-100' }} transition-colors"></div>
                @endif
              </div>
            @endforeach
          </div>
        </div>

        {{-- Spedizione --}}
        @php $shipment = $order->shipments->first(); @endphp
        @if($shipment)
          <div class="bg-white rounded-2xl shadow-soft-sm p-6">
            <h2 class="font-semibold text-neutral-900 mb-3">Spedizione</h2>
            <p class="text-sm text-neutral-700">Corriere: <strong>{{ $shipment->carrier }}</strong></p>
            @if($shipment->tracking_number ?? null)
              <p class="text-sm text-neutral-700 mt-1">
                Tracking: <strong>{{ $shipment->tracking_number }}</strong>
                @if($shipment->tracking_url ?? null)
                  <a href="{{ $shipment->tracking_url }}" target="_blank"
                     class="ml-2 text-brand-primary hover:text-brand-primary/80 font-medium">
                    Traccia →
                  </a>
                @endif
              </p>
            @endif
          </div>
        @endif
      </div>

      {{-- Sidebar riepilogo --}}
      <div class="space-y-6">

        {{-- Totali --}}
        <div class="bg-white rounded-2xl shadow-soft-sm p-6">
          <h2 class="font-semibold text-neutral-900 mb-4">Riepilogo</h2>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between text-neutral-600">
              <span>Subtotale</span>
              <span>€{{ number_format($order->subtotal, 2, ',', '.') }}</span>
            </div>
            @if(($order->discount_total ?? 0) > 0)
              <div class="flex justify-between text-emerald-600">
                <span>Sconto</span>
                <span>-€{{ number_format($order->discount_total, 2, ',', '.') }}</span>
              </div>
            @endif
            <div class="flex justify-between text-neutral-600">
              <span>Spedizione</span>
              <span>
                @if(($order->shipping_total ?? 0) == 0)
                  Gratuita
                @else
                  €{{ number_format($order->shipping_total, 2, ',', '.') }}
                @endif
              </span>
            </div>
            <div class="flex justify-between font-bold text-base pt-2 border-t border-neutral-100 text-neutral-900">
              <span>Totale</span>
              <span>€{{ number_format($order->total, 2, ',', '.') }}</span>
            </div>
          </div>
        </div>

        {{-- Indirizzo di consegna --}}
        @php $addr = $order->shipping_address_json ?? []; @endphp
        @if(!empty($addr))
          <div class="bg-white rounded-2xl shadow-soft-sm p-6">
            <h2 class="font-semibold text-neutral-900 mb-3">Indirizzo di consegna</h2>
            <address class="not-italic text-sm text-neutral-600 space-y-0.5">
              @if($addr['full_name'] ?? null)<div class="font-medium">{{ $addr['full_name'] }}</div>@endif
              @if($addr['street'] ?? null)<div>{{ $addr['street'] }}{{ ($addr['civic'] ?? null) ? ', ' . $addr['civic'] : '' }}</div>@endif
              @if($addr['postal_code'] ?? null)<div>{{ $addr['postal_code'] }} {{ $addr['city'] ?? '' }} {{ ($addr['province'] ?? null) ? '(' . $addr['province'] . ')' : '' }}</div>@endif
              @if($addr['phone'] ?? null)<div class="mt-1 text-neutral-400">{{ $addr['phone'] }}</div>@endif
            </address>
          </div>
        @endif

      </div>
    </div>
  @endif

</x-public.account-layout>
