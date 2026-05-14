@component('layouts.email', ['subject' => 'Aggiornamento ordine #' . $order->order_number])
  <h1>Aggiornamento sul tuo ordine</h1>
  <p>Il tuo ordine <strong>#{{ $order->order_number }}</strong> è stato aggiornato.</p>
  
  <div style="background:#E6F4F4; border-radius:12px; padding:24px; text-align:center; margin:24px 0;">
    <div class="label">Nuovo stato</div>
    <div style="font-size:18px; font-weight:600; color:#0F8A8A; margin:4px 0;">{{ $newStatus }}</div>
  </div>
  
  @if($trackingNumber && $trackingUrl)
    <h2>Informazioni di spedizione</h2>
    <p>Il tuo ordine è stato spedito! Puoi seguire la consegna utilizzando il numero di tracking:</p>
    <div style="background:#F1F5F9; border-radius:8px; padding:16px; margin:16px 0;">
      <div class="label">Numero di tracking</div>
      <div class="value" style="font-family:monospace; font-size:16px;">{{ $trackingNumber }}</div>
    </div>
    <a href="{{ $trackingUrl }}" class="btn">Traccia il pacco</a>
  @elseif($trackingNumber)
    <h2>Informazioni di spedizione</h2>
    <p>Il tuo ordine è stato spedito con numero di tracking: <strong>{{ $trackingNumber }}</strong></p>
  @endif
  
  <a href="{{ url('/account/ordini/' . $order->order_number) }}" class="btn">Visualizza ordine</a>
  
  <hr class="divider">
  <p style="font-size:13px; color:#64748B;">Per qualsiasi domanda, contattaci a <a href="mailto:info@skintemple.it" style="color:#0F8A8A;">info@skintemple.it</a></p>
@endcomponent