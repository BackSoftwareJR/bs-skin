@component('layouts.email', ['subject' => 'Il tuo codice di accesso SkinTemple'])
  <h1>Il tuo codice di accesso</h1>
  <p>Ciao,<br>hai richiesto un codice OTP per accedere a SkinTemple. Inseriscilo nella pagina di accesso.</p>
  
  <div style="background:#E6F4F4; border-radius:12px; padding:32px; text-align:center; margin:24px 0;">
    <div class="label">Il tuo codice OTP</div>
    <div style="font-size:48px; font-weight:700; letter-spacing:0.2em; color:#0F8A8A; margin:8px 0; font-family:monospace;">{{ $otpCode }}</div>
    <div style="font-size:13px; color:#64748B;">Valido per 10 minuti · Usa una sola volta</div>
  </div>
  
  <a href="{{ $loginUrl ?? url('/account/login') }}" class="btn">Accedi ora</a>
  
  <hr class="divider">
  <p style="font-size:13px; color:#64748B;">Se non hai richiesto questo codice, ignora questa email. Il tuo account è al sicuro.</p>
@endcomponent