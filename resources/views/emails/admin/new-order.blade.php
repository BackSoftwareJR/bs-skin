@component('layouts.email', ['subject' => 'Nuovo ordine #' . $order->order_number . ' ricevuto'])
  <style>
    .header { background: #DC2626 !important; }
  </style>
  
  <h1>Nuovo ordine ricevuto</h1>
  <p>È appena arrivato un nuovo ordine sulla piattaforma SkinTemple.</p>
  
  <h2>Dettagli ordine</h2>
  <table style="width:100%; border-collapse: collapse; margin: 16px 0;">
    <tr>
      <td style="padding:8px 0; border-bottom:1px solid #E2E8F0;"><strong>Numero ordine:</strong></td>
      <td style="padding:8px 0; border-bottom:1px solid #E2E8F0;">#{{ $order->order_number }}</td>
    </tr>
    <tr>
      <td style="padding:8px 0; border-bottom:1px solid #E2E8F0;"><strong>Cliente:</strong></td>
      <td style="padding:8px 0; border-bottom:1px solid #E2E8F0;">{{ $order->customer_email }}</td>
    </tr>
    <tr>
      <td style="padding:8px 0; border-bottom:1px solid #E2E8F0;"><strong>Totale:</strong></td>
      <td style="padding:8px 0; border-bottom:1px solid #E2E8F0; font-weight:600; color:#0F8A8A;">€{{ number_format($order->total, 2, ',', '.') }}</td>
    </tr>
    <tr>
      <td style="padding:8px 0; border-bottom:1px solid #E2E8F0;"><strong>Stato pagamento:</strong></td>
      <td style="padding:8px 0; border-bottom:1px solid #E2E8F0;">{{ ucfirst($order->payment_status ?? 'In attesa') }}</td>
    </tr>
    <tr>
      <td style="padding:8px 0; border-bottom:1px solid #E2E8F0;"><strong>Data ordine:</strong></td>
      <td style="padding:8px 0; border-bottom:1px solid #E2E8F0;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
    </tr>
  </table>
  
  <h2>Prodotti ordinati</h2>
  <ul style="list-style: none; padding: 0;">
    @foreach($order->items as $item)
      <li style="padding: 8px 0; border-bottom: 1px solid #E2E8F0;">
        <strong>{{ $item->name }}</strong>
        @if($item->variant_name) - {{ $item->variant_name }} @endif
        <span style="color: #64748B;">({{ $item->quantity }}x)</span>
      </li>
    @endforeach
  </ul>
  
  <a href="{{ url('/admin/orders/' . $order->id) }}" class="btn" style="background:#DC2626;">Gestisci ordine</a>
  
  <hr class="divider">
  <p style="font-size:13px; color:#64748B;">Ricevi questa email perché sei amministratore di SkinTemple.</p>
@endcomponent