<x-layouts.app>
  <x-public.container class="py-12">
    @php
      $customerId = session('skintemple_customer_id');
      $order = \App\Models\Order::where('order_number', $orderNumber)
        ->where('customer_id', $customerId)
        ->with(['items', 'shipment', 'statusHistory'])
        ->firstOrFail();
    @endphp
    <!-- Breadcrumb -->
    <x-public.breadcrumb :items="[['label' => 'Account', 'url' => route('account.dashboard')], ['label' => 'Ordini', 'url' => route('account.orders.index')], ['label' => '#' . $order->order_number, 'url' => '#']]" />
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-6">
      <!-- Items + stato -->
      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-soft-md p-6">
          <h1 class="font-semibold mb-1">Ordine #{{ $order->order_number }}</h1>
          <p class="text-sm text-neutral-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
          
          <div class="mt-6 space-y-4">
            @foreach($order->items as $item)
              <div class="flex gap-4 py-4 border-b border-neutral-100 last:border-0">
                <div class="w-16 h-16 bg-neutral-50 rounded-xl flex-shrink-0"></div>
                <div class="flex-1">
                  <div class="font-medium">{{ $item->name }}</div>
                  @if($item->variant_name) <div class="text-sm text-neutral-400">{{ $item->variant_name }}</div> @endif
                  <div class="text-sm text-neutral-500">Qtà: {{ $item->quantity }}</div>
                </div>
                <div class="font-semibold">€{{ number_format($item->total, 2, ',', '.') }}</div>
              </div>
            @endforeach
          </div>
        </div>
        
        <!-- Tracking -->
        @if($order->shipment)
          <div class="bg-white rounded-2xl shadow-soft-md p-6">
            <h2 class="font-semibold mb-3">Spedizione</h2>
            <p class="text-sm">Corriere: <strong>{{ $order->shipment->carrier }}</strong></p>
            @if($order->shipment->tracking_number)
              <p class="text-sm">Tracking: <strong>{{ $order->shipment->tracking_number }}</strong>
                @if($order->shipment->tracking_url)
                  <a href="{{ $order->shipment->tracking_url }}" target="_blank" class="link-teal ml-2">Traccia →</a>
                @endif
              </p>
            @endif
          </div>
        @endif
      </div>
      
      <!-- Riepilogo -->
      <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-soft-md p-6">
          <h2 class="font-semibold mb-4">Riepilogo</h2>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between"><span>Subtotale</span><span>€{{ number_format($order->subtotal, 2, ',', '.') }}</span></div>
            @if($order->discount_total > 0) <div class="flex justify-between text-success"><span>Sconto</span><span>-€{{ number_format($order->discount_total, 2, ',', '.') }}</span></div> @endif
            <div class="flex justify-between"><span>Spedizione</span><span>€{{ number_format($order->shipping_total, 2, ',', '.') }}</span></div>
            <div class="flex justify-between font-bold text-base pt-2 border-t border-neutral-100"><span>Totale</span><span>€{{ number_format($order->total, 2, ',', '.') }}</span></div>
          </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-soft-md p-6">
          <h2 class="font-semibold mb-2">Indirizzo di spedizione</h2>
          @php $addr = $order->shipping_address_json; @endphp
          <p class="text-sm text-neutral-600">{{ $addr['full_name'] ?? '' }}<br>{{ $addr['street'] ?? '' }}, {{ $addr['civic'] ?? '' }}<br>{{ $addr['postal_code'] ?? '' }} {{ $addr['city'] ?? '' }}</p>
        </div>
      </div>
    </div>
  </x-public.container>
</x-layouts.app>