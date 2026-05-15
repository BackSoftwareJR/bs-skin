<x-public.account-layout title="I miei ordini">

  @php
    $customer   = auth('customer')->user();
    $orders     = $customer
        ? \App\Models\Order::where('customer_id', $customer->id)->latest()->paginate(10)
        : collect();
  @endphp

  <div class="bg-white rounded-2xl shadow-soft-sm p-6">
    <div class="flex items-center justify-between mb-6">
      <h1 class="font-semibold text-xl text-neutral-900">I miei ordini</h1>
    </div>

    @forelse($orders as $order)
      <div class="border border-neutral-100 rounded-2xl p-5 mb-4 hover:border-brand-primary/20 transition-colors">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <div class="font-semibold text-neutral-900">#{{ $order->order_number }}</div>
            <div class="text-sm text-neutral-500 mt-0.5">{{ $order->created_at->format('d/m/Y H:i') }}</div>
          </div>
          <div class="flex items-center gap-4 flex-wrap">
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
            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
              {{ $order->status?->label() ?? ucfirst($order->status ?? 'In attesa') }}
            </span>
            <span class="font-semibold text-neutral-900">€{{ number_format($order->total, 2, ',', '.') }}</span>
            <a href="{{ route('account.orders.show', $order->order_number) }}"
               class="text-sm text-brand-primary hover:text-brand-primary/80 font-medium transition-colors">
              Dettaglio →
            </a>
          </div>
        </div>
      </div>
    @empty
      <div class="text-center py-16 text-neutral-400">
        <svg class="h-12 w-12 mx-auto mb-4 text-neutral-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        <p class="text-neutral-500 mb-4">Nessun ordine ancora.</p>
        <a href="{{ route('shop.index') }}"
           class="inline-block bg-brand-primary text-white text-sm font-medium px-5 py-2.5 rounded-xl hover:bg-brand-primary/90 transition-colors">
          Inizia a fare shopping →
        </a>
      </div>
    @endforelse

    @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
      <div class="mt-4">{{ $orders->links() }}</div>
    @endif
  </div>

</x-public.account-layout>
