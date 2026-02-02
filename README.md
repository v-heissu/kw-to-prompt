# 🔄 SEO to AI Prompt Converter

Web app PHP per convertire keyword SEO in prompt conversazionali per AI Assistant, con recupero volume di ricerca da Semrush.

## 🎯 Funzionalità

- ✅ Input keyword via **textarea** o **upload CSV**
- ✅ Recupero **volume di ricerca** da Semrush API (mercato Italia)
- ✅ **Topic clustering intelligente** (max 5 cluster semantici, NO catch-all generici)
- ✅ **Intent detection** automatico per ogni prompt (research, comparison, support, purchase, improvement)
- ✅ **Custom intent** configurabili dall'utente
- ✅ Generazione **prompt conversazionali** tramite OpenAI GPT-4o
- ✅ Processing **batch automatico** per grandi quantità (50 keyword per batch)
- ✅ **Progress bar** real-time con two-pass processing
- ✅ Statistiche aggregate (totale volume, media, topic unici, intent diversi)
- ✅ Export risultati in **CSV** (formato: `keyword|volume|topic|intent|prompt`)

## 📋 Requisiti

### Server
- PHP 7.4+ (consigliato 8.x)
- cURL abilitato
- `allow_url_fopen` attivo
- Hosting con supporto PHP (es. Serverplan)

### API Keys Necessarie
1. **Semrush API Key** → https://www.semrush.com/api-analytics/
2. **OpenAI API Key** → https://platform.openai.com/api-keys

## 🚀 Installazione su Serverplan

### 1. Upload Files

Carica tutti i file nella root del tuo dominio o in una sottocartella:

```
/public_html/seo-ai-tool/
├── index.php
├── process.php
├── .env.example
├── .gitignore
└── README.md
```

**Via FTP/SFTP:**
- Host: `ftp.tuodominio.it`
- User: il tuo username Serverplan
- Usa FileZilla o Cyberduck

**Via SSH (se disponibile):**
```bash
cd /home/tuousername/public_html
mkdir seo-ai-tool
cd seo-ai-tool
# poi carica i file
```

### 2. Configurazione API Keys

1. Rinomina `.env.example` in `.env`:
   ```bash
   mv .env.example .env
   ```

2. Modifica `.env` e inserisci le tue API keys:
   ```bash
   nano .env
   ```
   
   Oppure via FTP: scarica, modifica, ricarica

3. Contenuto del file `.env`:
   ```ini
   SEMRUSH_API_KEY=la_tua_chiave_semrush
   OPENAI_API_KEY=sk-proj-la_tua_chiave_openai
   ```

4. **IMPORTANTE**: Verifica permessi del file `.env`:
   ```bash
   chmod 600 .env
   ```
   (per sicurezza, solo proprietario può leggere)

### 3. Verifica Configurazione PHP

Controlla che sul tuo hosting Serverplan siano attive:

- ✅ cURL extension
- ✅ `allow_url_fopen = On`
- ✅ `max_execution_time >= 300` (per batch grandi)

Per verificare, crea un file `info.php`:
```php
<?php phpinfo(); ?>
```

Cercalo su `https://tuodominio.it/seo-ai-tool/info.php` e controlla le voci sopra.

**Poi ELIMINA il file `info.php` per sicurezza.**

### 4. Test Installazione

Vai su:
```
https://tuodominio.it/seo-ai-tool/
```

Dovresti vedere l'interfaccia della web app.

## 📖 Utilizzo

### Configurazione Intent (Opzionale)

Prima di inserire le keyword, puoi personalizzare gli intent:

**Intent di Default:**
- research
- comparison  
- support
- purchase
- improvement

**Per usare intent personalizzati:**
1. Nel campo "Configurazione Intent", inserisci i tuoi intent (uno per riga)
2. Esempi custom: `navigational`, `transactional`, `informational`
3. Se lasciato vuoto, verranno usati gli intent di default

### Input Keywords

**Opzione 1: Inserimento Manuale**
1. Clicca su tab "Inserimento Manuale"
2. Digita le keyword, una per riga
3. Esempio:
   ```
   best crm 2024
   migliori ristoranti milano
   python pandas tutorial
   ```

**Opzione 2: Upload CSV**
1. Clicca su tab "Upload CSV"
2. Trascina il file CSV o clicca per selezionarlo
3. Formato CSV:
   ```csv
   keyword
   best crm 2024
   migliori ristoranti milano
   python pandas tutorial
   ```
   (header opzionale)

### Processing

1. Clicca **"🚀 Processa Keywords"**
2. **Fase 1 (10-20%)**: Topic clustering - analisi semantica per creare 5 cluster intelligenti
3. **Fase 2 (20-100%)**: Batch processing - volume + prompt + intent per ogni keyword
4. Per liste > 50 keyword, l'elaborazione avviene in batch automatici
5. Ogni batch richiede ~10-30 secondi

### Risultati

- **Tabella interattiva** con 5 colonne:
  - Keyword
  - Volume (da Semrush)
  - Topic (cluster semantico)
  - Intent (categoria con color coding)
  - Prompt Conversazionale
- **Statistiche** aggregate: totale keyword, volume totale/medio, topic unici, intent diversi
- **Download CSV** cliccando su "💾 Scarica CSV"

Formato output CSV:
```
Keyword|Volume|Topic|Intent|Prompt
best crm 2024|5400|Business Software|purchase|Can you recommend the best CRM systems for 2024? I need something for a mid-size B2B company
python pandas filter|8100|Programming & Development|research|How do I filter a pandas dataframe in Python? Show me examples with multiple conditions
```

## 🔧 Troubleshooting

### ❌ Errore: "File .env non trovato"
**Soluzione**: Hai rinominato `.env.example` in `.env`?

### ❌ Errore: "API keys non configurate"
**Soluzione**: Verifica che nel file `.env` ci siano entrambe le API keys senza spazi:
```ini
SEMRUSH_API_KEY=abc123def456
OPENAI_API_KEY=sk-proj-xyz789
```

### ❌ Errore: "cURL error"
**Soluzione**: 
1. Verifica che cURL sia abilitato su PHP
2. Controlla firewall/restrizioni del server
3. Contatta supporto Serverplan se necessario

### ❌ Errore: "Semrush API error: HTTP 403"
**Soluzione**: 
- API key Semrush non valida
- Verifica su https://www.semrush.com/api-analytics/
- Controlla che il piano includa accessi API

### ❌ Errore: "OpenAI API error: HTTP 401"
**Soluzione**:
- API key OpenAI non valida o scaduta
- Verifica su https://platform.openai.com/api-keys
- Controlla credito disponibile

### ❌ Timeout durante elaborazione
**Soluzione**:
- Batch troppo grande: il sistema divide automaticamente in blocchi da 50
- Se persiste, contatta supporto Serverplan per aumentare `max_execution_time`

### ❌ Volume restituito sempre "N/A"
**Soluzione**:
- Verifica che la keyword esista nel database Semrush Italia
- Alcune keyword hanno volume 0 o non disponibile
- Controlla log errori PHP per dettagli

## 📊 Costi API

### Semrush
- Dipende dal piano (controllare limiti mensili)
- ~1 chiamata API per keyword

### OpenAI GPT-4o
- ~$5 per 1M token input
- ~$15 per 1M token output
- **Stima per 100 keyword**:
  - 1 call clustering (iniziale): ~$0.05
  - 100 call prompt+intent: ~$0.15-0.40
  - **Totale**: ~$0.20-0.45

## 🧠 Come Funziona

### Two-Pass Processing

L'applicazione utilizza un approccio in due fasi per ottimizzare qualità e costi:

**Fase 1: Topic Clustering (1 chiamata OpenAI)**
- Analizza TUTTE le keyword in una singola chiamata
- Identifica i 5 topic più significativi nella lista
- Crea cluster semantici intelligenti (NO "Other", "General", "Miscellaneous")
- Se le keyword coprono 10 argomenti, sceglie i 5 più rappresentativi
- Mappa ogni keyword al cluster più vicino

**Fase 2: Enrichment Batch (N chiamate)**
- Per ogni keyword:
  - Recupera volume da Semrush (1 call)
  - Genera prompt + intent da OpenAI (1 call)
  - Associa topic dal mapping Fase 1
- Processing in batch da 50 keyword per evitare timeout

### Intent Detection

Ogni prompt generato viene automaticamente classificato in una delle categorie:

- **research**: Informazioni, apprendimento, comprensione
- **comparison**: Confronto opzioni, valutazione alternative
- **support**: Risoluzione problemi, troubleshooting, assistenza
- **purchase**: Raccomandazioni prodotti, decisioni d'acquisto
- **improvement**: Ottimizzazione, miglioramento esistente

Color coding nella UI:
- 🔵 research (blue)
- 🟣 comparison (purple)
- 🟢 support (green)
- 🟠 purchase (orange)
- 🔴 improvement (pink)

## 🔒 Sicurezza

**Best Practices:**

1. ✅ File `.env` con permessi 600
   ```bash
   chmod 600 .env
   ```

2. ✅ Non committare `.env` su Git (già in `.gitignore`)

3. ✅ Considera autenticazione HTTP Basic per produzione:
   ```apache
   # .htaccess
   AuthType Basic
   AuthName "Restricted Area"
   AuthUserFile /path/to/.htpasswd
   Require valid-user
   ```

4. ✅ Disabilita `display_errors` in produzione:
   ```php
   // In process.php, riga 9:
   ini_set('display_errors', 0);
   ```

## 📝 Limiti Noti

- Batch processing: max 50 keyword per volta (configurabile in `process.php`)
- Timeout: 300 secondi per batch (configurabile via `set_time_limit()`)
- No caching risultati Semrush (ogni run = nuove chiamate API)
- No retry automatico su errori API

## 🛠️ Personalizzazioni

### Modificare prompt OpenAI

Modifica la variabile `$systemPrompt` in `process.php` (linea ~133):

```php
$systemPrompt = <<<PROMPT
[tuo prompt personalizzato]
PROMPT;
```

### Cambiare mercato Semrush

Modifica parametro `database` in `process.php` (linea ~107):

```php
'database' => 'us',  // usa 'us' per USA, 'uk' per UK, ecc.
```

### Modificare batch size

Modifica in `index.php` (linea ~451):

```javascript
const batchSize = 100;  // default: 50
```

## 📧 Supporto

Per problemi tecnici:
- Verifica log errori PHP: `/logs/error_log` o via cPanel
- Contatta supporto Serverplan per configurazioni server
- Per bug tool: apri issue su GitHub (se pubblico)

## 📜 Licenza

Uso interno Pro Web Digital Consulting.

---

**Versione**: 1.0  
**Autore**: Pro Web Digital Consulting  
**Data**: 2025
