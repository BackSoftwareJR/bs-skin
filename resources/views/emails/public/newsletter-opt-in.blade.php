@component('layouts.email', ['subject' => 'Conferma la tua iscrizione alla newsletter'])
  <h1>Conferma la tua iscrizione</h1>
  <p>Ciao,<br>grazie per esserti iscritto alla newsletter di SkinTemple!</p>
  
  <p>Per completare l'iscrizione e ricevere le nostre novità sui trattamenti estetici e le tecnologie più avanzate, conferma il tuo indirizzo email cliccando sul bottone qui sotto.</p>
  
  <a href="{{ url('/newsletter/conferma/' . urlencode($subscriber->email) . '/' . $subscriber->double_opt_in_token) }}" class="btn">Conferma iscrizione</a>
  
  <hr class="divider">
  <p style="font-size:13px; color:#64748B;">Se non hai richiesto questa iscrizione, ignora questa email. Non riceverai altre comunicazioni da noi.</p>
  
  <p style="font-size:13px; color:#64748B;">Ti arriveranno aggiornamenti su:</p>
  <ul style="font-size:13px; color:#64748B; padding-left: 20px;">
    <li>Nuovi trattamenti e tecnologie</li>
    <li>Offerte esclusive riservate agli iscritti</li>
    <li>Consigli di bellezza dal nostro team di esperti</li>
  </ul>
@endcomponent