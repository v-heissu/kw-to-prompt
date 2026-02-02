# 🔧 Troubleshooting - JSON Error Fix

## Errore: "Unexpected token '<', "<br /> <b>"... is not valid JSON"

Questo errore significa che `process.php` sta restituendo HTML (un messaggio di errore PHP) invece di JSON.

### ✅ Soluzione Rapida (3 step)

#### Step 1: Controlla Configurazione
Vai su: `http://tuodominio.it/seo-ai-tool/test.php`

Questo file diagnostico controllerà:
- ✅ PHP version
- ✅ cURL abilitato
- ✅ File .env configurato
- ✅ API keys valide

#### Step 2: Verifica File .env

Il file `.env` DEVE esistere e contenere le API keys:

```ini
SEMRUSH_API_KEY=la_tua_vera_chiave_semrush
OPENAI_API_KEY=sk-proj-la_tua_vera_chiave_openai
```

**Errori comuni:**
- ❌ File ancora chiamato `.env.example` (deve essere `.env`)
- ❌ API keys non cambiate (ancora `your_semrush_api_key_here`)
- ❌ Spazi prima/dopo il simbolo `=`
- ❌ Virgolette intorno alle keys (NON servono)

**Formato corretto:**
```ini
SEMRUSH_API_KEY=abc123def456xyz
OPENAI_API_KEY=sk-proj-xyz789abc123
```

#### Step 3: Controlla Error Log

Se hai accesso SSH:
```bash
cd /path/to/seo-ai-tool/
tail -f error.log
```

Il file `error.log` conterrà i dettagli dell'errore PHP.

---

## 🐛 Cause Comuni

### 1. File .env Mancante o Non Configurato
**Sintomo:** Errore immediatamente dopo click "Processa"  
**Fix:**
```bash
# Via SSH
cd /home/tuousername/public_html/seo-ai-tool
cp .env.example .env
nano .env  # inserisci le API keys
```

**Via FTP:**
1. Scarica `.env.example`
2. Rinominalo in `.env`
3. Apri con editor di testo
4. Inserisci le API keys
5. Ricarica su server

### 2. Errori di Sintassi PHP
**Sintomo:** `Parse error` o `syntax error` nel browser  
**Fix:** Verifica che tutti i file PHP siano stati caricati correttamente e non corrotti

### 3. API Keys Non Valide
**Sintomo:** Errore dopo fase clustering o durante processing  
**Fix:** Verifica le API keys:

**Semrush:**
- Login → https://www.semrush.com/api-analytics/
- Verifica key attiva

**OpenAI:**
- Login → https://platform.openai.com/api-keys
- Verifica key e credito disponibile: https://platform.openai.com/usage

### 4. Timeout PHP
**Sintomo:** Errore dopo 30-60 secondi con molte keyword  
**Fix:** Via `.htaccess` nella cartella del tool:
```apache
php_value max_execution_time 300
php_value memory_limit 256M
```

### 5. JSON Malformato da OpenAI
**Sintomo:** Errore sporadico durante processing  
**Fix:** Il tool gestisce già questo con retry, ma puoi ridurre batch size in `index.php`:
```javascript
const batchSize = 25;  // invece di 50
```

---

## 📋 Checklist Debug

Usa questa checklist per diagnosticare:

- [ ] File `.env` esiste? (non `.env.example`)
- [ ] API keys configurate? (non valori default)
- [ ] PHP >= 7.4?
- [ ] cURL abilitato?
- [ ] File `process.php` caricato correttamente?
- [ ] Permessi file OK? (644 per .php, 600 per .env)
- [ ] `test.php` passa tutti i check?
- [ ] OpenAI ha credito disponibile?
- [ ] Semrush API key attiva?

---

## 🆘 Debug Avanzato

### Abilitare Display Errors (SOLO per debug)

In `process.php`, linea 7:
```php
ini_set('display_errors', 1);  // ⚠️ SOLO per debug locale
```

Poi riprova e vedrai l'errore PHP esatto nel browser.

**⚠️ IMPORTANTE:** Dopo il debug, rimetti:
```php
ini_set('display_errors', 0);
```

### Testare API Manualmente

Crea file `test_api.php`:
```php
<?php
$env = parse_ini_file('.env');

// Test OpenAI
$ch = curl_init('https://api.openai.com/v1/models');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $env['OPENAI_API_KEY']
]);
$response = curl_exec($ch);
echo "OpenAI: " . (curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200 ? '✅' : '❌') . "<br>";
curl_close($ch);

// Test Semrush
$url = 'https://api.semrush.com/?type=phrase_this&key=' . $env['SEMRUSH_API_KEY'] . 
       '&phrase=test&database=it&export_columns=Ph,Nq';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
echo "Semrush: " . (curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200 ? '✅' : '❌') . "<br>";
curl_close($ch);
?>
```

Vai su `http://tuodominio.it/seo-ai-tool/test_api.php`

---

## 📞 Supporto Serverplan

Se il problema persiste:

1. Controlla con `test.php` che tutto sia configurato
2. Verifica `error.log` per errori specifici
3. Contatta supporto Serverplan con questi dettagli:
   - Errore esatto
   - Versione PHP
   - Configurazione cURL
   - Contenuto di `error.log`

---

## ✅ Dopo il Fix

1. Riprova con 2-3 keyword per testare
2. Se funziona, disabilita `display_errors` in `process.php`
3. Elimina `test.php` e `test_api.php` dal server (per sicurezza)

---

**Versione:** 2.1  
**Ultimo aggiornamento:** 14 Novembre 2025
