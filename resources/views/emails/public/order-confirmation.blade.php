@component('layouts.email', ['subject' => 'Conferma ordine #' . $order->order_number])
  <h1>Grazie per il tuo ordine!</h1>
  <p>Il tuo ordine <strong>#{{ $order->order_number }}</strong> è stato ricevuto e sarà elaborato a breve.</p>
  
  <h2>Riepilogo ordine</h2>
  <table class="order-items">
    <tr>
      <th>Prodotto</th>
      <th style="text-align:right;">Qtà</th>
      <th style="text-align:right;">Prezzo</th>
    </tr>
    @foreach($order->items as $item)
    <tr>
      <td>
        <strong>{{ $item->name }}</strong>
        @if($item->variant_name)<br><span style="font-size:12px;color:#64748B;">{{ $item->variant_name }}</span>@endif
      </td>
      <td style="text-align:right;">{{ $item->quantity }}</td>
      <td style="text-align:right;">€{{ number_format($item->total, 2, ',', '.') }}</td>
    </tr>
    @endforeach
    @if($order->discount_total > 0)
    <tr><td colspan="2" style="text-align:right; color:#059669;">Sconto applicato</td><td style="text-align:right; color:#059669;">-€{{ number_format($order->discount_total, 2, ',', '.') }}</td></tr>
    @endif
    <tr><td colspan="2" style="text-align:right;">Spedizione</td><td style="text-align:right;">€{{ number_format($order->shipping_total, 2, ',', '.') }}</td></tr>
    <tr class="total-row"><td colspan="2" style="text-align:right;">Totale</td><td style="text-align:right; color:#0F8A8A;">€{{ number_format($order->total, 2, ',', '.') }}</td></tr>
  </table>
  
  <h2>Indirizzo di spedizione</h2>
  @php $addr = $order->shipping_address_json; @endphp
  <p>{{ $addr['full_name'] ?? '' }}<br>{{ $addr['street'] ?? '' }}, {{ $addr['civic'] ?? '' }}<br>{{ $addr['postal_code'] ?? '' }} {{ $addr['city'] ?? '' }} ({{ $addr['province'] ?? '' }})</p>
  
  <h2>Metodo di pagamento</h2>
  <p>{{ ucfirst($order->payment_method ?? 'Da confermare') }}</p>
  
  <a href="{{ url('/account/ordini/' . $order->order_number) }}" class="btn">Visualizza ordine</a>
  
  <hr class="divider">
  <p style="font-size:13px; color:#64748B;">Per qualsiasi domanda, contattaci a <a href="mailto:info@skintemple.it" style="color:#0F8A8A;">info@skintemple.it</a></p>
@endcomponent