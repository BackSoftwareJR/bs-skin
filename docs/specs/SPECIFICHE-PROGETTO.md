# SPECIFICHE DI PROGETTO: SKINTEMPLE E-COMMERCE

## 1. Architettura di Sistema
L'applicativo seguirà un'architettura Headless (disaccoppiata).
* **Backend / API:** Ultima versione stabile e sicura di Laravel. Risiederà su un dominio dedicato (`api.skintemple.it`). Fornirà endpoint RESTful (o GraphQL) protetti.
* **Frontend / SSR:** Node.js .
* **Database:** MySQL relazionale, strutturato per gestire moli di dati e-commerce in modo scalabile.

## 2. Design e UI/UX (Frontend e Backoffice)
L'intera interfaccia (sia lato cliente che lato backoffice) deve seguire principi di design premium, minimalista e altamente intuitivo.
* **Estetica:** Stile pulito, utilizzo strategico del white space, tipografia leggibile (es. font sans-serif moderni come San Francisco o Inter). Leggero utilizzo di glassmorphism per menu e overlay, bordi smussati e ombre morbide.
* **Backoffice:** Non deve sembrare un gestionale anni '90. Deve avere un'interfaccia "consumer-like", piacevole, intuitiva e fluida come l'app lato cliente in stile decisamente apple super mdoerna effetti glass minimalismo spazi giusti e da mobile voglio app web mobile con menu dedicato varie intergfaccie studiate bene da mobile ecc 

## 3. Struttura Frontend (Lato Utente)
### 3.1 Navigazione Principale
* **Home**
* **Prodotti e Tecnologie (Shop):** Menu a tendina generato dinamicamente. Mostrerà le "Macroaree" di prodotto.
* **Chi Siamo**
* **Supporto**

### 3.2 Area Personale e Autenticazione (Passwordless)
* **Login Flow:** Nessuna password. L'utente inserisce la mail -> Accetta obbligatoriamente Termini e Privacy -> Riceve un OTP (One Time Password) via mail per accedere.
* **Controllo Esistenza:** Se la mail non ha ordini o account associato, il sistema mostra un messaggio: "Non hai ancora effettuato ordini con noi" prima di inviare l'OTP (o reindirizza al flusso di registrazione/guest).
* **Dashboard Utente:** * Storico ordini.
    * Tracking spedizioni.
    * **Sezione Sconti Dedicati:** Visualizzazione di coupon o listini speciali assegnati specificamente a quell'utente dal backoffice.

## 4. Struttura Backend & Backoffice (Admin)
Il Backoffice è diviso in due macro-aree logiche:

### 4.1 Area Operativa (E-commerce Management)
* **Gestione Catalogo (Prodotti):**
    * Creazione pagine prodotto complesse suddivise in sezioni (es. benefici, ingredienti, modo d'uso).
    * Campi prodotto: Prezzo, Immagini multiple, Descrizioni, Etichette (Novità, Marchio, Promozioni).
    * Struttura a livelli: **Macroaree** (che generano pagine dedicate con URL propri) e **Microaree** (che fungono da filtri dinamici all'interno delle pagine della macroarea).
* **Gestione Ordini e Spedizioni:** Dashboard per il tracking dello stato di avanzamento, stampa etichette, gestione resi.
* **Gestione Promozioni:** Sistema avanzato per la creazione di Coupon e Sconti.
    * Regole applicabili per: singolo utente, gruppi di utenti, scadenza temporale, valore fisso o percentuale, soglia minima di spesa.

### 4.2 Area CMS (Content Management)
* Gestione diretta e visuale dei testi e delle immagini delle pagine statiche (Home, Chi Siamo, Supporto).
* L'interfaccia di modifica deve essere user-friendly (WYSIWYG o form a blocchi puliti).

## 5. Infrastruttura Mail e Deliverability (Standard 2026)
Per evitare blocchi di spam (specialmente considerando server come Hostinger) il sistema mail deve essere impeccabile:
* **Tecnologia:** Utilizzo di SMTP autenticato, idealmente appoggiandosi a servizi transazionali moderni (es. Postmark, Resend o SendGrid) per tassi di consegna ottimali, piuttosto che il mailer base dell'hosting.
* **Sicurezza DNS:** Configurazione rigida e automatizzata per DKIM, SPF e policy DMARC.
* **Template:** Email transazionali (OTP, Conferma Ordine, Spedizione) create con design responsivo moderno, in linea con l'identità visiva del brand SkinTemple.