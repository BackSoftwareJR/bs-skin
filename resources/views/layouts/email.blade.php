<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $subject ?? 'SkinTemple' }}</title>
  <style>
    body { margin: 0; padding: 0; font-family: Inter, 'Helvetica Neue', Arial, sans-serif; background-color: #F8FAFC; color: #0F172A; }
    .container { max-width: 600px; margin: 40px auto; background: #FFFFFF; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(15,23,42,0.08); }
    .header { background: #0F8A8A; padding: 32px 40px; text-align: center; }
    .header-logo { color: #FFFFFF; font-size: 24px; font-weight: 600; letter-spacing: -0.5px; }
    .body { padding: 40px; }
    .footer { background: #F1F5F9; padding: 24px 40px; text-align: center; font-size: 12px; color: #64748B; }
    .btn { display: inline-block; background: #0F8A8A; color: #FFFFFF; text-decoration: none; padding: 14px 36px; border-radius: 50px; font-weight: 500; font-size: 14px; letter-spacing: 0.5px; margin: 24px 0; }
    .divider { border: none; border-top: 1px solid #E2E8F0; margin: 24px 0; }
    h1 { font-size: 26px; font-weight: 600; margin: 0 0 8px; color: #0F172A; }
    h2 { font-size: 18px; font-weight: 600; margin: 24px 0 8px; color: #0F172A; }
    p { font-size: 15px; line-height: 1.6; margin: 0 0 16px; color: #334155; }
    .label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #64748B; }
    .value { font-size: 15px; color: #0F172A; font-weight: 500; }
    table.order-items { width: 100%; border-collapse: collapse; margin: 16px 0; }
    table.order-items th { text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #64748B; padding: 8px 0; border-bottom: 2px solid #E2E8F0; }
    table.order-items td { padding: 12px 0; border-bottom: 1px solid #E2E8F0; font-size: 14px; color: #334155; }
    .total-row td { font-weight: 700; font-size: 16px; color: #0F172A; padding-top: 16px; border-bottom: none; }
    @media (max-width: 620px) { .container { margin: 0; border-radius: 0; } .body { padding: 24px; } .header { padding: 24px; } }
  </style>
</head>
<body>
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#F8FAFC; min-height:100vh; padding: 20px 0;">
    <tr><td align="center">
      <div class="container">
        <div class="header">
          <div class="header-logo">SkinTemple</div>
          <div style="color:rgba(255,255,255,0.7); font-size:12px; margin-top:4px;">Tecnologie Made in Italy</div>
        </div>
        <div class="body">
          {{ $slot }}
        </div>
        <div class="footer">
          <p style="margin:0 0 8px;">SkinTemple · Strada Santa Vittoria 11, 10024 Moncalieri (TO)<br>
          P.IVA 11863510019 · <a href="mailto:info@skintemple.it" style="color:#0F8A8A;">info@skintemple.it</a></p>
          <p style="margin:0; font-size:11px;">
            <a href="{{ url('/privacy') }}" style="color:#64748B;">Privacy Policy</a> · 
            <a href="{{ url('/termini-di-servizio') }}" style="color:#64748B;">Termini di Servizio</a>
          </p>
        </div>
      </div>
    </td></tr>
  </table>
</body>
</html>