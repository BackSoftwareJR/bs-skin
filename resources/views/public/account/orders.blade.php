<x-layouts.app>
  <x-public.container class="py-12">
    <h1 class="font-display text-3xl mb-8">I miei ordini</h1>
    @php
      $customerId = session('skintemple_customer_id');
      $orders = $customerId ? \App\Models\Order::where('customer_id', $customerId)->latest()->paginate(10) : collect();
    @endphp
    @forelse($orders as $order)
      <div class="bg-white rounded-2xl shadow-soft-md p-6 mb-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <div class="font-semibold">Ordine #{{ $order->order_number }}</div>
            <div class="text-sm text-neutral-500">{{ $order->created_at->format('d/m/Y H:i') }}</div>
          </div>
          <div class="flex items-center gap-4">
            <span class="px-3 py-1 rounded-full text-xs font-medium {{ match($order->status->value ?? 'pending') { 'delivered' => 'bg-success-bg text-success', 'cancelled' => 'bg-danger-bg text-danger', 'shipped' => 'bg-info-bg text-info', default => 'bg-warning-bg text-warning' } }}">
              {{ $order->status?->label() ?? $order->status }}
            </span>
            <span class="font-semibold">€{{ number_format($order->total, 2, ',', '.') }}</span>
            <a href="{{ route('account.orders.show', $order->order_number) }}" class="link-teal text-sm">Dettaglio →</a>
          </div>
        </div>
      </div>
    @empty
      <div class="text-center py-16 text-neutral-400">
        <x-heroicon-o-shopping-bag class="h-12 w-12 mx-auto mb-4 text-neutral-200" />
        <p>Nessun ordine ancora. <a href="{{ route('shop.index') }}" class="link-teal">Inizia a fare shopping →</a></p>
      </div>
    @endforelse
    {{ $orders->links() }}
  </x-public.container>
</x-layouts.app>