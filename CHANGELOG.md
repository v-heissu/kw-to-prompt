# 📝 Changelog

## Version 2.0 - Topic Clustering & Intent Detection

### 🆕 Nuove Funzionalità

#### 1. Topic Clustering Intelligente
- **Analisi semantica automatica** di tutte le keyword
- Creazione di **esattamente 5 cluster tematici**
- **NO cluster generici** tipo "Other", "General", "Miscellaneous"
- Algoritmo intelligente che identifica i topic più rappresentativi
- Visualizzazione topic nella tabella risultati e nel CSV export

#### 2. Intent Detection Automatico
- **5 categorie di intent predefinite**:
  - `research` - ricerca informazioni
  - `comparison` - confronto opzioni
  - `support` - risoluzione problemi
  - `purchase` - intento d'acquisto
  - `improvement` - ottimizzazione esistente
  
- **Color coding UI** per identificazione visiva rapida
- Intent rilevato automaticamente per ogni prompt generato

#### 3. Custom Intent
- Campo dedicato per **definire intent personalizzati**
- Fallback automatico agli intent di default se lasciato vuoto
- Esempi: `navigational`, `transactional`, `informational`

#### 4. Two-Pass Processing
- **Fase 1**: Topic clustering (1 chiamata OpenAI per tutte le keyword)
- **Fase 2**: Enrichment batch (volume + prompt + intent)
- Progress bar con indicatore fase corrente
- Ottimizzazione costi API

### 🔄 Modifiche Tecniche

#### Frontend (index.php)
- Aggiunto campo "Configurazione Intent"
- Nuova struttura tabella: Keyword | Volume | Topic | Intent | Prompt
- Color coding per intent categories
- Stats aggiornate con "Topic Identificati" e "Intent Diversi"
- CSV export con nuove colonne

#### Backend (process.php)
- Nuova action `cluster` per topic clustering
- Funzione `performTopicClustering()` con prompt ottimizzato
- Funzione `generateAIPromptWithIntent()` con dual-purpose (prompt + intent)
- JSON response format per gestione strutturata
- Gestione custom intent via parametro

### 📊 Output Format

**CSV Export:**
```
Keyword|Volume|Topic|Intent|Prompt
best crm 2024|5400|Business Software|purchase|Can you recommend...
python pandas|8100|Programming|research|How do I filter...
```

**JSON Response (process.php):**
```json
{
  "keyword": "best crm 2024",
  "volume": 5400,
  "topic": "Business Software",
  "intent": "purchase",
  "prompt": "Can you recommend the best CRM systems for 2024?"
}
```

### 💰 Impatto Costi

**Prima (v1):**
- 100 keyword = ~100 chiamate OpenAI = $0.10-0.30

**Ora (v2):**
- 1 chiamata clustering + 100 chiamate enrichment = $0.20-0.45
- Aumento ~50% ma con 3 dati in più (topic + intent + prompt ottimizzato)

### 🎨 UI Improvements

- Intent badges con colori distinti
- Progress bar a 2 fasi (clustering 0-20%, processing 20-100%)
- Topic column visibile nella tabella
- Stats cards aggiornate (5 cards totali)

### ⚙️ Backward Compatibility

- **100% compatibile** con deployment esistenti
- Nessuna modifica richiesta a `.env`
- API keys rimangono identiche
- Struttura file invariata

### 🐛 Bug Fixes

- Gestione errori migliorata nel two-pass processing
- Timeout handling per batch grandi
- Fallback intelligente se clustering fallisce

---

## Version 1.0 - Release Iniziale

### Funzionalità Base
- Input keyword (textarea + CSV upload)
- Integrazione Semrush API
- Generazione prompt via OpenAI GPT-4o
- Batch processing automatico
- Export CSV risultati

---

**Data Release v2**: 14 Novembre 2025  
**Breaking Changes**: Nessuno  
**Migration Required**: No
