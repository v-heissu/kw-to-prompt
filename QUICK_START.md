# 🚀 Quick Start - Deploy su Serverplan

## Step 1: Upload Files (5 minuti)

### Via FTP (FileZilla/Cyberduck)
1. Connetti al tuo FTP Serverplan:
   - Host: `ftp.tuodominio.it`
   - Username: [tuo username]
   - Password: [tua password]
   - Porta: 21

2. Naviga in `/public_html/`

3. Crea cartella: `seo-ai-tool`

4. Upload questi file:
   ```
   ✓ index.php
   ✓ process.php
   ✓ .env.example
   ✓ .gitignore
   ✓ README.md
   ```

## Step 2: Configura API Keys (2 minuti)

1. Scarica `.env.example` dal server

2. Rinominalo in `.env`

3. Apri con editor di testo e modifica:
   ```ini
   SEMRUSH_API_KEY=metti_qui_la_tua_key
   OPENAI_API_KEY=sk-proj-metti_qui_la_tua_key
   ```

4. Ricarica il file `.env` sul server (stessa cartella)

### Dove trovare le API Keys?

**Semrush:**
- Login → https://www.semrush.com/api-analytics/
- Clicca "Get API Key" o "Manage API Keys"
- Copia la key (formato: `xxxxxxxxxxxxxx`)

**OpenAI:**
- Login → https://platform.openai.com/api-keys
- Clicca "Create new secret key"
- Copia la key (formato: `sk-proj-xxxxx`)
- ⚠️ Salvala subito, non la rivedrai più!

## Step 3: Test (1 minuto)

1. Apri browser e vai su:
   ```
   https://tuodominio.it/seo-ai-tool/
   ```

2. Dovresti vedere l'interfaccia della web app

3. Prova con 2-3 keyword per testare:
   ```
   best crm 2024
   migliori ristoranti milano
   ```

## ✅ Fatto!

La web app è pronta all'uso.

---

## 🆘 Problemi Comuni

### Non vedo l'interfaccia
- Verifica URL: `https://tuodominio.it/seo-ai-tool/index.php`
- Controlla che i file siano stati caricati correttamente

### Errore ".env non trovato"
- Hai rinominato `.env.example` in `.env`?
- Il file `.env` è nella stessa cartella di `index.php`?

### Errore API keys
- Controlla che non ci siano spazi nelle API keys
- Formato corretto:
  ```ini
  SEMRUSH_API_KEY=abc123
  OPENAI_API_KEY=sk-proj-xyz789
  ```

### Nessun risultato / sempre N/A
- Verifica che le API keys siano valide
- Controlla credito OpenAI su https://platform.openai.com/usage
- Semrush: verifica piano include accessi API

---

## 📞 Serve Aiuto?

1. Controlla README.md completo per troubleshooting dettagliato
2. Verifica log errori PHP via cPanel
3. Contatta supporto Serverplan per problemi server
