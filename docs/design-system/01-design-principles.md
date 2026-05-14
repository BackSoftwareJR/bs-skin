# 01 — Principi di Design

## Principi guida

### 1. Spazio prima di decorazione
Lo spazio bianco e il primo strumento di design. Ogni elemento respira. Il padding generoso e i margini ampi comunicano qualita e chiarezza senza bisogno di ornamenti. Se un layout sembra affollato, la soluzione e togliere, mai aggiungere.

### 2. Tipografia come prima UI
La gerarchia visiva si costruisce con peso, dimensione e spaziatura del testo, non con colori vivaci o bordi. Un titolo Cormorant Garamond 600 in nero slate su sfondo bianco comunica piu di qualsiasi decorazione grafica. La quote serif italica ("La cura della pelle, ridefinita.") e una firma di brand.

### 3. Brand teal come segnale, non come decorazione
Il teal `brand.primary` (#0F8A8A) e il colore-segnale del brand. Indica un'azione, un link, uno stato attivo, l'identita SkinTemple. **Non e mai sfondo pieno di sezioni grandi**, **non e mai bordo decorativo di card standard**, **non e mai fill di icone non interattive**. Quando l'utente vede il teal, deve sapere che puo cliccare o che sta guardando un elemento del brand stesso.

### 4. Ombre morbide, mai bordi netti
Le elevazioni si esprimono con ombre diffuse multi-layer (stile Apple), mai con bordi spessi o ombre dure. L'effetto deve essere "l'elemento galleggia leggermente", non "l'elemento e incorniciato". Hover delle card: leggero `-translate-y-0.5` + passaggio da `shadow-soft-md` a `shadow-soft-lg`.

### 5. Glass solo dove aggiunge gerarchia
Il glassmorphism (backdrop-blur + trasparenza) si usa esclusivamente per overlay navigazione (header sticky, mega menu, drawer mobile, modale), mai su card prodotto o sezioni di contenuto standard. Il glass deve creare un livello di profondita percepibile sopra il contenuto, non essere decorazione. Per overlay tintati brand usare `glass.tint` (teal ~8% opacity) anziche colore pieno.

### 6. Mobile-first integrale
Ogni componente nasce per viewport 320px e poi si espande. La versione mobile non e un adattamento: e la versione primaria. Bottom tab bar fissa, drawer dal basso, touch target 44x44px minimo. L'esperienza mobile deve sembrare un'app web nativa.

### 7. Italiano impeccabile
Ogni testo visibile dall'utente e in italiano corretto, senza anglicismi inutili. "Aggiungi al carrello" e non "Add to cart". "Scopri di piu" e non "Learn more". Le label tecniche nel codice restano in inglese. La cura del linguaggio riflette la cura del prodotto.

---

## Voice & Tone

### Registro B2B (Tecnologie per professionisti)
- **Autorevole** ma mai freddo. Il tono e quello di un consulente esperto che parla a un collega professionista del settore estetico/medico.
- Termini tecnici dove appropriati (radiofrequenza, cavitazione, endermologie, pressoterapia, laser diodo) ma sempre spiegati nel contesto della seduta o del trattamento.
- Focus su ROI, affidabilita, assistenza post-vendita, Made in Italy come garanzia di qualita costruttiva.
- Esempio: "Una sola piattaforma, infinite possibilita operative. Ottimizza lo spazio in cabina senza rinunciare alla versatilita."

### Registro B2C (Cosmetici skincare)
- **Caloroso e rassicurante**, mai aggressivo o pressante. Il tono e quello di chi consiglia con competenza.
- Evitare iperboli ("il MIGLIORE", "risultati INCREDIBILI"). Preferire affermazioni concrete e misurate.
- Focus su ingredienti, qualita, Made in Italy, esperienza sensoriale.
- Esempio: "Selezionati con rigore, formulati in Italia. Prodotti che rispettano la tua pelle e le tue aspettative."

### Registro tecnico-operativo (Monouso B2B)
- **Diretto e pratico**. Cliente target: titolare/operatore centro estetico che fa riordino. Vuole vedere SKU, formato confezione, prezzo unitario, disponibilita.
- Niente storytelling sui prodotti di consumo: "Lenzuolino da massaggio in TNT 25/28gr mq 100x200 — confezione da 100 pz" e perfetto cosi.

### Cosa evitare sempre
- Tono commerciale aggressivo ("COMPRA ORA!", "Solo per OGGI!")
- Linguaggio tecnico-ostile senza contesto
- Promesse non verificabili
- Emoji nei testi istituzionali (ammesse solo in comunicazioni social, mai sul sito)

---

## Anti-pattern da evitare

| Anti-pattern | Perche evitarlo | Alternativa corretta |
|---|---|---|
| Ombre dure (`shadow-lg` Tailwind default) | Effetto pesante, non premium | Usare `shadow-soft-*` custom |
| Gradienti vivaci (viola-rosa, blu-verde elettrico) | Incoerente con palette teal sobria | Gradienti tono-su-tono (`primary` -> `accent`, surface -> surface-soft) |
| Teal come sfondo di intere sezioni | Brucia il significato di segnale | Sezione tintata: `primary-soft` (#E6F4F4) o `surface-soft` (#F8FAFC) |
| Bordi teal su card prodotto | Effetto "tag" inappropriato | Card senza bordo, solo `shadow-soft-md` |
| Font display oversized ovunque | Perde impatto, difficile da leggere | Cormorant solo per H1-H2 hero e quote signature |
| Glassmorphism su tutto | Effetto confuso, perde gerarchia | Glass solo su overlay navigazione |
| Underline statico sotto link nav | Look anni '90 | Underline animato (`animate-underline-grow`) sotto voce attiva |
| Animazioni rimbalzanti (bounce, elastic) | Non coerente con estetica Apple | Easing `cubic-bezier(0.4, 0, 0.2, 1)`, durate 200-300ms |
| Card con troppi elementi visibili | Sovraccaricare la percezione | Max 4-5 info per card (brand label, nome, SKU/sub, prezzo, link "Scopri di piu >") |
| Colori semantici come decorazione | Rosso/verde usati per estetica | Colori semantici solo per stati reali |
| Scroll hijacking | Frustrante, problematico su mobile | Scroll nativo, animazioni on-scroll leggere |
| Icon-only button senza label | Inaccessibile, ambiguo | Sempre `aria-label`, tooltip su hover |
