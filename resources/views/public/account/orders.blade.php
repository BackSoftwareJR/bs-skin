<x-public.account-layout title="I miei ordini">

  <div class="space-y-4">
    <h1 class="font-display text-2xl text-brand-ink">I miei ordini</h1>

    @php
      $customer = auth('customer')->user();
      $orders = $customer
        ? \App\Models\Order::where('customer_id', $customer->id)->latest()->paginate(10)
        : collect();
    @endphp

    @forelse($orders as $order)
      <div class="bg-white rounded-2xl shadow-soft-sm p-5">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <p class="font-semibold text-brand-ink">Ordine #{{ $order->order_number }}</p>
            <p class="text-sm text-neutral-500 mt-0.5">{{ $order->created_at->format('d/m/Y H:i') }}</p>
          </div>
          <div class="flex flex-wrap items-center gap-3">
            <span class="px-3 py-1 rounded-full text-xs font-semibold
              {{ match($order->status->value ?? 'pending') {
                'delivered' => 'bg-green-100 text-green-700',
                'cancelled' => 'bg-red-100 text-red-700',
                'shipped'   => 'bg-blue-100 text-blue-700',
                default     => 'bg-yellow-100 text-yellow-700'
              } }}">
              {{ $order->status?->label() ?? $order->status }}
            </span>
            <span class="font-bold text-brand-ink">€{{ number_format($order->total, 2, ',', '.') }}</span>
            <a href="{{ route('account.orders.show', $order->order_number) }}"
               class="text-sm text-brand-primary hover:underline font-medium">Dettaglio →</a>
          </div>
        </div>
      </div>
    @empty
      <div class="bg-white rounded-2xl shadow-soft-sm p-12 text-center">
        <svg class="w-14 h-14 text-neutral-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
        </svg>
        <h2 class="font-semibold text-brand-ink mb-2">Nessun ordine ancora</h2>
        <p class="text-neutral-500 text-sm mb-6">I tuoi ordini appariranno qui una volta effettuati.</p>
        <a href="{{ route('shop.index') }}" class="btn-primary inline-block">Inizia a fare shopping →</a>
      </div>
    @endforelse

    @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
      <div class="mt-4">{{ $orders->links() }}</div>
    @endif
  </div>

</x-public.account-layout>
