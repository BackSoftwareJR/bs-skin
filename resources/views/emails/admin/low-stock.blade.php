@component('layouts.email', ['subject' => 'Allarme scorte in esaurimento'])
  <style>
    .header { background: #F59E0B !important; }
  </style>
  
  <h1>⚠️ Scorte in esaurimento</h1>
  <p>Alcuni prodotti sono sotto la soglia minima di magazzino e potrebbero essere presto esauriti.</p>
  
  <h2>Prodotti con scorte basse</h2>
  <table class="order-items">
    <tr>
      <th>Prodotto</th>
      <th>SKU</th>
      <th style="text-align:right;">Disponibili</th>
      <th style="text-align:right;">Soglia</th>
    </tr>
    @foreach($items as $item)
    <tr>
      <td>
        <strong>{{ $item->variant->product->name }}</strong>
        @if($item->variant->name)<br><span style="font-size:12px;color:#64748B;">{{ $item->variant->name }}</span>@endif
      </td>
      <td style="font-family:monospace; font-size:12px;">{{ $item->sku }}</td>
      <td style="text-align:right; color:#DC2626; font-weight:600;">{{ $item->quantity_available }}</td>
      <td style="text-align:right; color:#64748B;">{{ $item->low_stock_threshold }}</td>
    </tr>
    @endforeach
  </table>
  
  <a href="{{ url('/admin/inventory') }}" class="btn" style="background:#F59E0B;">Gestisci magazzino</a>
  
  <hr class="divider">
  <p style="font-size:13px; color:#64748B;">Ti consigliamo di riordinare questi prodotti prima che vadano completamente esauriti.</p>
  <p style="font-size:13px; color:#64748B;">Ricevi questa email perché sei amministratore di SkinTemple.</p>
@endcomponent