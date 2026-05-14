# 01 — Principi di Design

## Principi guida

### 1. Spazio prima di decorazione
Lo spazio bianco e il primo strumento di design. Ogni elemento respira. Il padding generoso e i margini ampi comunicano lusso e chiarezza senza bisogno di ornamenti. Se un layout sembra affollato, la soluzione e togliere, mai aggiungere.

### 2. Tipografia come prima UI
La gerarchia visiva si costruisce con peso, dimensione e spaziatura del testo, non con colori vivaci o bordi. Un titolo Cormorant Garamond 600 su sfondo bianco comunica piu di qualsiasi decorazione grafica.

### 3. Ombre morbide, mai bordi netti
Le elevazioni si esprimono con ombre diffuse e leggere (stile Apple), mai con bordi spessi o ombre dure `box-shadow` con offset elevato. L'effetto deve essere "l'elemento galleggia leggermente", non "l'elemento e incorniciato".

### 4. Glass solo dove aggiunge gerarchia
Il glassmorphism (backdrop-blur + trasparenza) si usa esclusivamente per overlay navigazione (mega menu, drawer mobile, modale), mai su card prodotto o sezioni di contenuto standard. Il glass deve creare un livello di profondita percepibile, non essere decorazione fine a se stessa.

### 5. Mobile-first integrale
Ogni componente nasce per viewport 320px e poi si espande. La versione mobile non e un adattamento: e la versione primaria. Bottom tab bar fissa, drawer dal basso, touch target 44x44px minimo. L'esperienza mobile deve sembrare un'app nativa.

### 6. Italiano impeccabile
Ogni testo visibile dall'utente e in italiano corretto, senza anglicismi inutili. "Aggiungi al carrello" e non "Add to cart". Le label tecniche nel codice restano in inglese. La cura del linguaggio riflette la cura del prodotto.

---

## Voice & Tone

### Registro B2B (Tecnologie per professionisti)
- **Autorevole** ma mai freddo. Il tono e quello di un consulente esperto che parla a un collega professionista.
- Termini tecnici dove appropriati (radiofrequenza, cavitazione, piattaforma multifunzione) ma sempre spiegati nel contesto.
- Focus su ROI, affidabilita, assistenza, Made in Italy come garanzia di qualita costruttiva.
- Esempio: "Una sola piattaforma, infinite possibilita operative. Ottimizza lo spazio in cabina senza rinunciare alla versatilita."

### Registro B2C (Cosmetici skincare)
- **Caloroso e rassicurante**, mai aggressivo o pressante. Il tono e quello di chi consiglia con competenza.
- Evitare iperboli ("il MIGLIOR prodotto", "risultati INCREDIBILI"). Preferire affermazioni concrete e misurate.
- Focus su ingredienti, qualita, Made in Italy, esperienza sensoriale.
- Esempio: "Selezionati con rigore, formulati in Italia. Prodotti che rispettano la tua pelle e le tue aspettative."

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
| Gradienti vivaci (viola-rosa, blu-verde) | Incoerente con palette oro/nero | Gradienti sottili oro-trasparente se necessario |
| Font display oversized ovunque | Perde impatto, difficile da leggere | Cormorant solo per H1-H2 hero, sezioni claim |
| Glassmorphism su tutto | Effetto confuso, perde significato gerarchico | Glass solo su overlay, mega menu, modal |
| Bordi spessi colorati | Estetica anni 2010, non minimal | Bordi 1px `neutral.200` o nessun bordo con ombra |
| Animazioni rimbalzanti (bounce, elastic) | Non coerente con estetica Apple | Easing `cubic-bezier(0.4, 0, 0.2, 1)`, durate 200-300ms |
| Card con troppi elementi visibili | Sovraccaricare la percezione | Max 4-5 informazioni per card (brand, nome, prezzo, badge, CTA hover) |
| Colori semantici come decorazione | Rosso/verde usati per estetica | Colori semantici solo per stati reali (errore, successo, avviso) |
| Scroll hijacking | Frustrante, problematico su mobile | Scroll nativo, animazioni on-scroll leggere con `IntersectionObserver` |
| Icon-only button senza label | Inaccessibile, ambiguo | Sempre `aria-label` o testo visibile, tooltip su hover |
