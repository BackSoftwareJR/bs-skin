<div>
  @if($recentOrders->isEmpty())
    <div class="text-center py-8 text-neutral-400">
      <x-heroicon-o-shopping-bag class="h-8 w-8 mx-auto mb-2 text-neutral-200" />
      <p class="text-sm">Nessun ordine ancora</p>
      <a href="{{ route('shop.index') }}" class="link-teal text-sm mt-1 inline-block">Inizia a fare shopping →</a>
    </div>
  @else
    <div class="space-y-3">
      @foreach($recentOrders as $order)
        <div class="flex items-center justify-between p-4 bg-neutral-25 rounded-xl border border-neutral-100">
          <div class="flex-1">
            <div class="flex items-center gap-3">
              <div class="font-medium text-sm">#{{ $order->order_number }}</div>
              <span class="px-2 py-1 rounded-full text-xs font-medium {{ match($order->status->value ?? 'pending') { 'delivered' => 'bg-success-bg text-success', 'cancelled' => 'bg-danger-bg text-danger', 'shipped' => 'bg-info-bg text-info', default => 'bg-warning-bg text-warning' } }}">
                {{ $order->status?->label() ?? 'In attesa' }}
              </span>
            </div>
            <div class="text-xs text-neutral-500 mt-1">
              {{ $order->created_at->format('d/m/Y') }} • {{ $order->items->count() }} {{ $order->items->count() === 1 ? 'prodotto' : 'prodotti' }}
            </div>
          </div>
          <div class="text-right">
            <div class="font-semibold text-sm">€{{ number_format($order->total, 2, ',', '.') }}</div>
            <a href="{{ route('account.orders.show', $order->order_number) }}" class="link-teal text-xs">Dettaglio</a>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>