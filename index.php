<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEO to AI Prompt Converter</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .content {
            padding: 40px;
        }
        
        .input-section {
            margin-bottom: 30px;
        }
        
        .input-section h2 {
            font-size: 18px;
            color: #333;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .input-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .tab {
            padding: 10px 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            color: #666;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
            font-weight: 600;
        }
        
        .tab:hover {
            color: #667eea;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        textarea {
            width: 100%;
            min-height: 200px;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            resize: vertical;
            transition: border-color 0.3s;
        }
        
        textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .file-upload {
            border: 2px dashed #e0e0e0;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .file-upload:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }
        
        .file-upload.dragover {
            border-color: #667eea;
            background: #f0f2ff;
        }
        
        .file-upload input[type="file"] {
            display: none;
        }
        
        .file-upload-icon {
            font-size: 48px;
            margin-bottom: 10px;
            color: #667eea;
        }
        
        .file-name {
            margin-top: 10px;
            color: #667eea;
            font-weight: 600;
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .progress-container {
            display: none;
            margin: 30px 0;
            padding: 20px;
            background: #f8f9ff;
            border-radius: 8px;
        }
        
        .progress-container.active {
            display: block;
        }
        
        .progress-bar {
            width: 100%;
            height: 30px;
            background: #e0e0e0;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            width: 0%;
            transition: width 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: 600;
        }
        
        .progress-text {
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .results-section {
            display: none;
            margin-top: 40px;
        }
        
        .results-section.active {
            display: block;
        }
        
        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .results-header h2 {
            font-size: 18px;
            color: #333;
        }
        
        .btn-download {
            background: #10b981;
            padding: 10px 20px;
            font-size: 14px;
        }
        
        .results-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .results-table thead {
            background: #f8f9ff;
        }
        
        .results-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #667eea;
            border-bottom: 2px solid #667eea;
        }
        
        .results-table td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .results-table tbody tr:hover {
            background: #f8f9ff;
        }
        
        .keyword-cell {
            font-weight: 600;
            color: #333;
        }
        
        .volume-cell {
            color: #667eea;
            font-weight: 600;
            text-align: center;
        }
        
        .prompt-cell {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .variant-indicator {
            text-align: center;
            font-weight: 600;
            color: #667eea;
            font-size: 13px;
        }
        
        .variant-badge {
            display: inline-block;
            padding: 3px 8px;
            background: #667eea;
            color: white;
            border-radius: 10px;
            font-size: 11px;
        }
        
        .row-checkbox {
            text-align: center;
        }
        
        .row-checkbox input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
        
        tbody tr.selected {
            background: #f0f2ff !important;
        }

        .clustering-option:has(input:checked) {
            border-color: #667eea;
            background: #f8f9ff;
        }

        .clustering-option:hover {
            border-color: #667eea;
        }
        
        .error-message {
            background: #fee;
            color: #c33;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            display: none;
        }
        
        .error-message.active {
            display: block;
        }
        
        .info-box {
            background: #f0f9ff;
            border-left: 4px solid #0ea5e9;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #0c4a6e;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8ebff 100%);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔄 SEO to AI Prompt Converter</h1>
            <p>Trasforma keyword SEO in prompt conversazionali per AI Assistant • Powered by Semrush + OpenAI</p>
        </div>
        
        <div class="content">
            <div class="info-box">
                <strong>ℹ️ Come funziona:</strong> Inserisci le tue keyword SEO (da textarea o CSV). 
                Il tool recupererà il volume di ricerca da Semrush e genererà prompt conversazionali ottimizzati per ChatGPT.
            </div>
            
            <div class="input-section">
                <div class="input-section">
                <h2>🌍 Mercato di Riferimento</h2>
                <p style="color: #666; font-size: 14px; margin-bottom: 15px;">
                    Seleziona il database Semrush e la lingua per i prompt generati
                </p>
                <select id="market-select" style="
                    width: 100%;
                    padding: 12px;
                    border: 2px solid #e0e0e0;
                    border-radius: 8px;
                    font-size: 15px;
                    cursor: pointer;
                    background: white;
                ">
                    <option value="it" selected>🇮🇹 Italia (Italiano)</option>
                    <option value="us">🇺🇸 United States (English)</option>
                    <option value="uk">🇬🇧 United Kingdom (English)</option>
                    <option value="es">🇪🇸 España (Español)</option>
                    <option value="fr">🇫🇷 France (Français)</option>
                    <option value="de">🇩🇪 Deutschland (Deutsch)</option>
                </select>
            </div>
                <h2>⚙️ Opzioni Generazione</h2>

                <div style="display: flex; gap: 30px; margin-bottom: 20px; flex-wrap: wrap;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" id="generate-variants" style="width: 18px; height: 18px; cursor: pointer;">
                        <span style="font-size: 15px;">
                            <strong>Genera 3 varianti per ogni keyword</strong>
                            <br>
                            <small style="color: #666;">
                                ⚠️ Aumenta tempo e costi x3 (~$0.60-1.35 per 100 kw invece di $0.20-0.45)
                            </small>
                        </span>
                    </label>
                </div>

                <div style="margin-bottom: 20px;">
                    <p style="font-size: 15px; margin-bottom: 10px;"><strong>Tipo di Clustering:</strong></p>
                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px 15px; border: 2px solid #e0e0e0; border-radius: 8px; transition: all 0.2s;" class="clustering-option">
                            <input type="radio" name="clustering-type" value="keywords" checked style="width: 18px; height: 18px; cursor: pointer;">
                            <span>
                                <strong>Solo Keywords</strong>
                                <br>
                                <small style="color: #666;">Topic basato sulle keyword</small>
                            </span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px 15px; border: 2px solid #e0e0e0; border-radius: 8px; transition: all 0.2s;" class="clustering-option">
                            <input type="radio" name="clustering-type" value="prompts" style="width: 18px; height: 18px; cursor: pointer;">
                            <span>
                                <strong>Solo Prompt</strong>
                                <br>
                                <small style="color: #666;">Topic basato sui prompt generati</small>
                            </span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px 15px; border: 2px solid #e0e0e0; border-radius: 8px; transition: all 0.2s;" class="clustering-option">
                            <input type="radio" name="clustering-type" value="both" style="width: 18px; height: 18px; cursor: pointer;">
                            <span>
                                <strong>Entrambi</strong>
                                <br>
                                <small style="color: #666;">Due colonne: Topic KW + Topic Prompt</small>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="input-section">
                <h2>🎯 Configurazione Intent (Opzionale)</h2>
                <p style="color: #666; font-size: 14px; margin-bottom: 15px;">
                    Aggiungi intent personalizzati. Se lasciato vuoto, verranno usati gli intent di default: 
                    <strong>research, comparison, support, purchase, improvement</strong>
                </p>
                <textarea id="custom-intents" 
                          placeholder="Inserisci intent personalizzati, uno per riga (opzionale)&#10;&#10;Esempio:&#10;navigational&#10;transactional&#10;informational"
                          style="min-height: 100px;"></textarea>
            </div>
            
            <div class="input-section">
                <h2>📝 Input Keywords</h2>
                
                <div class="input-tabs">
                    <button class="tab active" data-tab="textarea">Inserimento Manuale</button>
                    <button class="tab" data-tab="csv">Upload CSV</button>
                </div>
                
                <div id="textarea-tab" class="tab-content active">
                    <textarea id="keywords-input" placeholder="Inserisci le keyword, una per riga&#10;&#10;Esempio:&#10;best crm 2024&#10;python pandas filter&#10;migliori ristoranti milano"></textarea>
                </div>
                
                <div id="csv-tab" class="tab-content">
                    <div class="file-upload" id="file-upload">
                        <div class="file-upload-icon">📄</div>
                        <p><strong>Trascina qui il file CSV</strong> o clicca per selezionare</p>
                        <p style="font-size: 12px; color: #999; margin-top: 10px;">Formato: una keyword per riga (con o senza header)</p>
                        <input type="file" id="csv-file" accept=".csv,.txt">
                        <div class="file-name" id="file-name"></div>
                    </div>
                </div>
            </div>
            
            <button class="btn" id="process-btn">
                <span id="btn-text">🚀 Processa Keywords</span>
                <span class="spinner" id="btn-spinner" style="display: none;"></span>
            </button>
            
            <div class="error-message" id="error-message"></div>
            
            <div class="progress-container" id="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill" id="progress-fill">0%</div>
                </div>
                <div class="progress-text" id="progress-text">Inizializzazione...</div>
            </div>
            
            <div class="results-section" id="results-section">
                <div class="stats" id="stats"></div>
                
                <div class="results-header">
                    <h2>📊 Risultati</h2>
                    <div style="display: flex; gap: 10px;">
                        <button class="btn" id="select-all-btn" style="padding: 10px 20px; font-size: 14px; background: #6b7280;">
                            ✓ Seleziona Tutto
                        </button>
                        <button class="btn btn-download" id="download-selected-btn">
                            💾 Scarica Selezionati
                        </button>
                        <button class="btn btn-download" id="download-all-btn">
                            💾 Scarica Tutto
                        </button>
                    </div>
                </div>
                
                <table class="results-table">
                    <thead id="results-thead">
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="header-checkbox" style="width: 16px; height: 16px; cursor: pointer;">
                            </th>
                            <th style="width: 180px;">Keyword</th>
                            <th style="width: 80px;">Volume</th>
                            <th style="width: 120px;">Topic</th>
                            <th style="width: 100px;">Intent</th>
                            <th>Prompt Conversazionale</th>
                            <th style="width: 60px;">Var.</th>
                        </tr>
                    </thead>
                    <tbody id="results-body">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        // Tab switching
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                this.classList.add('active');
                document.getElementById(targetTab + '-tab').classList.add('active');
            });
        });
        
        // File upload handling
        const fileUpload = document.getElementById('file-upload');
        const fileInput = document.getElementById('csv-file');
        const fileName = document.getElementById('file-name');
        
        fileUpload.addEventListener('click', () => fileInput.click());
        
        fileUpload.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUpload.classList.add('dragover');
        });
        
        fileUpload.addEventListener('dragleave', () => {
            fileUpload.classList.remove('dragover');
        });
        
        fileUpload.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUpload.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                fileName.textContent = '✓ ' + files[0].name;
            }
        });
        
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                fileName.textContent = '✓ ' + e.target.files[0].name;
            }
        });
        
        // Process button
        const processBtn = document.getElementById('process-btn');
        const btnText = document.getElementById('btn-text');
        const btnSpinner = document.getElementById('btn-spinner');
        const progressContainer = document.getElementById('progress-container');
        const progressFill = document.getElementById('progress-fill');
        const progressText = document.getElementById('progress-text');
        const resultsSection = document.getElementById('results-section');
        const resultsBody = document.getElementById('results-body');
        const errorMessage = document.getElementById('error-message');
        const statsContainer = document.getElementById('stats');
        
        let allResults = [];
        
        processBtn.addEventListener('click', async () => {
            // Get keywords
            let keywords = [];
            const activeTab = document.querySelector('.tab.active').getAttribute('data-tab');
            
            if (activeTab === 'textarea') {
                const text = document.getElementById('keywords-input').value.trim();
                if (!text) {
                    showError('Inserisci almeno una keyword');
                    return;
                }
                keywords = text.split('\n').map(k => k.trim()).filter(k => k);
            } else {
                const file = fileInput.files[0];
                if (!file) {
                    showError('Seleziona un file CSV');
                    return;
                }
                
                const text = await file.text();
                keywords = text.split('\n').map(k => k.trim()).filter(k => k);
                
                // Remove header if present (contains typical header words)
                if (keywords[0] && (keywords[0].toLowerCase().includes('keyword') || 
                    keywords[0].toLowerCase().includes('query'))) {
                    keywords.shift();
                }
            }
            
            if (keywords.length === 0) {
                showError('Nessuna keyword valida trovata');
                return;
            }
            
            // Get custom intents
            const customIntentsText = document.getElementById('custom-intents').value.trim();
            const customIntents = customIntentsText ? 
                customIntentsText.split('\n').map(i => i.trim()).filter(i => i) : [];
            
            // Get generate variants option
            const generateVariants = document.getElementById('generate-variants').checked;

            // Get market selection
            const market = document.getElementById('market-select').value;

            // Get clustering type
            const clusteringType = document.querySelector('input[name="clustering-type"]:checked').value;
            
            // Start processing
            processBtn.disabled = true;
            btnText.textContent = 'Elaborazione in corso...';
            btnSpinner.style.display = 'inline-block';
            progressContainer.classList.add('active');
            resultsSection.classList.remove('active');
            errorMessage.classList.remove('active');
            allResults = [];
            
            try {
                await processKeywords(keywords, customIntents, generateVariants, market, clusteringType);
            } catch (error) {
                showError('Errore durante l\'elaborazione: ' + error.message);
            } finally {
                processBtn.disabled = false;
                btnText.textContent = '🚀 Processa Keywords';
                btnSpinner.style.display = 'none';
            }
        });
        
        // Store clustering type globally for updateResults
        let currentClusteringType = 'keywords';

        async function processKeywords(keywords, customIntents, generateVariants, market, clusteringType) {
            currentClusteringType = clusteringType;

            if (clusteringType === 'keywords') {
                // FLUSSO 1: Clustering delle keyword (comportamento originale)
                await processWithKeywordClustering(keywords, customIntents, generateVariants, market);
            } else if (clusteringType === 'prompts') {
                // FLUSSO 2: Clustering dei prompt generati
                await processWithPromptClustering(keywords, customIntents, generateVariants, market);
            } else {
                // FLUSSO 3: Entrambi - doppio clustering
                await processWithBothClustering(keywords, customIntents, generateVariants, market);
            }
        }

        async function processWithKeywordClustering(keywords, customIntents, generateVariants, market) {
            // PHASE 1: Topic Clustering (single call for all keywords)
            progressText.textContent = 'Fase 1/2: Analisi topic clustering keywords...';
            progressFill.style.width = '10%';
            progressFill.textContent = '10%';

            const clusteringFormData = new FormData();
            clusteringFormData.append('action', 'cluster');
            clusteringFormData.append('keywords', JSON.stringify(keywords));
            clusteringFormData.append('market', market);

            const clusterResponse = await fetch('process.php', {
                method: 'POST',
                body: clusteringFormData
            });

            // Check if response is JSON
            let contentType = clusterResponse.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const textResponse = await clusterResponse.text();
                console.error('Non-JSON response received:', textResponse);
                throw new Error('Server returned non-JSON response. Check test.php for diagnostics.');
            }

            if (!clusterResponse.ok) {
                throw new Error(`HTTP error! status: ${clusterResponse.status}`);
            }

            const clusterData = await clusterResponse.json();

            if (clusterData.error) {
                throw new Error(clusterData.error);
            }

            const topicMapping = clusterData.topic_mapping;

            progressFill.style.width = '20%';
            progressFill.textContent = '20%';

            // PHASE 2: Batch Processing (volume + prompt + intent)
            const batchSize = 50;
            const totalBatches = Math.ceil(keywords.length / batchSize);

            for (let i = 0; i < totalBatches; i++) {
                const batch = keywords.slice(i * batchSize, (i + 1) * batchSize);
                const batchNum = i + 1;

                progressText.textContent = `Fase 2/2: Batch ${batchNum}/${totalBatches} - Elaborazione ${batch.length} keywords...`;

                const formData = new FormData();
                formData.append('action', 'process');
                formData.append('keywords', JSON.stringify(batch));
                formData.append('batch', batchNum);
                formData.append('total_batches', totalBatches);
                formData.append('topic_mapping', JSON.stringify(topicMapping));
                formData.append('custom_intents', JSON.stringify(customIntents));
                formData.append('generate_variants', generateVariants ? '1' : '0');
                formData.append('market', market);

                const response = await fetch('process.php', {
                    method: 'POST',
                    body: formData
                });

                // Check if response is JSON
                contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const textResponse = await response.text();
                    console.error('Non-JSON response received:', textResponse);
                    throw new Error('Server error during processing. Check test.php for diagnostics.');
                }

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.error);
                }

                // Update progress (20% + 80% for processing)
                const progress = 20 + Math.round(((i + 1) / totalBatches) * 80);
                progressFill.style.width = progress + '%';
                progressFill.textContent = progress + '%';

                // Add results
                allResults = allResults.concat(data.results);

                // Update results display
                updateResults();
            }

            progressText.textContent = '✓ Completato!';
            setTimeout(() => {
                progressContainer.classList.remove('active');
            }, 2000);
        }

        async function processWithPromptClustering(keywords, customIntents, generateVariants, market) {
            // PHASE 1: Genera i prompt senza clustering
            progressText.textContent = 'Fase 1/2: Generazione prompt...';
            progressFill.style.width = '5%';
            progressFill.textContent = '5%';

            const batchSize = 50;
            const totalBatches = Math.ceil(keywords.length / batchSize);

            // Process all keywords first (without topic mapping)
            for (let i = 0; i < totalBatches; i++) {
                const batch = keywords.slice(i * batchSize, (i + 1) * batchSize);
                const batchNum = i + 1;

                progressText.textContent = `Fase 1/2: Batch ${batchNum}/${totalBatches} - Generazione prompt per ${batch.length} keywords...`;

                const formData = new FormData();
                formData.append('action', 'process');
                formData.append('keywords', JSON.stringify(batch));
                formData.append('batch', batchNum);
                formData.append('total_batches', totalBatches);
                formData.append('topic_mapping', JSON.stringify({})); // Empty - no topics yet
                formData.append('custom_intents', JSON.stringify(customIntents));
                formData.append('generate_variants', generateVariants ? '1' : '0');
                formData.append('market', market);

                const response = await fetch('process.php', {
                    method: 'POST',
                    body: formData
                });

                // Check if response is JSON
                let contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const textResponse = await response.text();
                    console.error('Non-JSON response received:', textResponse);
                    throw new Error('Server error during processing. Check test.php for diagnostics.');
                }

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.error);
                }

                // Update progress (5% + 65% for prompt generation)
                const progress = 5 + Math.round(((i + 1) / totalBatches) * 65);
                progressFill.style.width = progress + '%';
                progressFill.textContent = progress + '%';

                // Add results
                allResults = allResults.concat(data.results);
            }

            // PHASE 2: Cluster the generated prompts
            progressText.textContent = 'Fase 2/2: Analisi topic clustering prompt...';
            progressFill.style.width = '75%';
            progressFill.textContent = '75%';

            // Collect all prompts for clustering
            const allPrompts = [];
            const promptToResultIndex = []; // Maps prompt index to result index and variant index

            allResults.forEach((result, resultIndex) => {
                const prompts = Array.isArray(result.prompts) ? result.prompts : [result.prompt];
                prompts.forEach((prompt, variantIndex) => {
                    allPrompts.push(prompt);
                    promptToResultIndex.push({ resultIndex, variantIndex });
                });
            });

            const clusteringFormData = new FormData();
            clusteringFormData.append('action', 'cluster_prompts');
            clusteringFormData.append('prompts', JSON.stringify(allPrompts));
            clusteringFormData.append('market', market);

            const clusterResponse = await fetch('process.php', {
                method: 'POST',
                body: clusteringFormData
            });

            // Check if response is JSON
            let contentType = clusterResponse.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const textResponse = await clusterResponse.text();
                console.error('Non-JSON response received:', textResponse);
                throw new Error('Server returned non-JSON response during prompt clustering.');
            }

            if (!clusterResponse.ok) {
                throw new Error(`HTTP error! status: ${clusterResponse.status}`);
            }

            const clusterData = await clusterResponse.json();

            if (clusterData.error) {
                throw new Error(clusterData.error);
            }

            const topicMapping = clusterData.topic_mapping;

            progressFill.style.width = '90%';
            progressFill.textContent = '90%';

            // Apply topic to each result based on the prompt clustering
            allPrompts.forEach((prompt, promptIndex) => {
                const topic = topicMapping[prompt] || 'Uncategorized';
                const { resultIndex, variantIndex } = promptToResultIndex[promptIndex];

                // For results with variants, we assign topic to the whole result (first prompt's topic)
                // Or we could track topics per variant - let's use the first prompt's topic for simplicity
                if (variantIndex === 0 || !allResults[resultIndex].topic || allResults[resultIndex].topic === 'Uncategorized') {
                    allResults[resultIndex].topic = topic;
                }

                // Also store topics per variant if needed
                if (!allResults[resultIndex].topics) {
                    allResults[resultIndex].topics = [];
                }
                allResults[resultIndex].topics[variantIndex] = topic;
            });

            progressFill.style.width = '100%';
            progressFill.textContent = '100%';

            // Update results display
            updateResults();

            progressText.textContent = '✓ Completato!';
            setTimeout(() => {
                progressContainer.classList.remove('active');
            }, 2000);
        }

        async function processWithBothClustering(keywords, customIntents, generateVariants, market) {
            // PHASE 1: Clustering delle keyword
            progressText.textContent = 'Fase 1/3: Analisi topic clustering keywords...';
            progressFill.style.width = '5%';
            progressFill.textContent = '5%';

            const clusteringFormData = new FormData();
            clusteringFormData.append('action', 'cluster');
            clusteringFormData.append('keywords', JSON.stringify(keywords));
            clusteringFormData.append('market', market);

            const clusterResponse = await fetch('process.php', {
                method: 'POST',
                body: clusteringFormData
            });

            let contentType = clusterResponse.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const textResponse = await clusterResponse.text();
                console.error('Non-JSON response received:', textResponse);
                throw new Error('Server returned non-JSON response during keyword clustering.');
            }

            if (!clusterResponse.ok) {
                throw new Error(`HTTP error! status: ${clusterResponse.status}`);
            }

            const clusterData = await clusterResponse.json();

            if (clusterData.error) {
                throw new Error(clusterData.error);
            }

            const keywordTopicMapping = clusterData.topic_mapping;

            progressFill.style.width = '15%';
            progressFill.textContent = '15%';

            // PHASE 2: Generazione prompt con topic keyword
            const batchSize = 50;
            const totalBatches = Math.ceil(keywords.length / batchSize);

            for (let i = 0; i < totalBatches; i++) {
                const batch = keywords.slice(i * batchSize, (i + 1) * batchSize);
                const batchNum = i + 1;

                progressText.textContent = `Fase 2/3: Batch ${batchNum}/${totalBatches} - Generazione prompt...`;

                const formData = new FormData();
                formData.append('action', 'process');
                formData.append('keywords', JSON.stringify(batch));
                formData.append('batch', batchNum);
                formData.append('total_batches', totalBatches);
                formData.append('topic_mapping', JSON.stringify(keywordTopicMapping));
                formData.append('custom_intents', JSON.stringify(customIntents));
                formData.append('generate_variants', generateVariants ? '1' : '0');
                formData.append('market', market);

                const response = await fetch('process.php', {
                    method: 'POST',
                    body: formData
                });

                contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const textResponse = await response.text();
                    console.error('Non-JSON response received:', textResponse);
                    throw new Error('Server error during processing.');
                }

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.error);
                }

                // Update progress (15% + 55% for prompt generation)
                const progress = 15 + Math.round(((i + 1) / totalBatches) * 55);
                progressFill.style.width = progress + '%';
                progressFill.textContent = progress + '%';

                // Add results - store keyword topic as topicKeyword
                data.results.forEach(result => {
                    result.topicKeyword = result.topic;
                });
                allResults = allResults.concat(data.results);
            }

            // PHASE 3: Clustering dei prompt generati
            progressText.textContent = 'Fase 3/3: Analisi topic clustering prompt...';
            progressFill.style.width = '75%';
            progressFill.textContent = '75%';

            // Collect all prompts for clustering
            const allPrompts = [];
            const promptToResultIndex = [];

            allResults.forEach((result, resultIndex) => {
                const prompts = Array.isArray(result.prompts) ? result.prompts : [result.prompt];
                prompts.forEach((prompt, variantIndex) => {
                    allPrompts.push(prompt);
                    promptToResultIndex.push({ resultIndex, variantIndex });
                });
            });

            const promptClusteringFormData = new FormData();
            promptClusteringFormData.append('action', 'cluster_prompts');
            promptClusteringFormData.append('prompts', JSON.stringify(allPrompts));
            promptClusteringFormData.append('market', market);

            const promptClusterResponse = await fetch('process.php', {
                method: 'POST',
                body: promptClusteringFormData
            });

            contentType = promptClusterResponse.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const textResponse = await promptClusterResponse.text();
                console.error('Non-JSON response received:', textResponse);
                throw new Error('Server returned non-JSON response during prompt clustering.');
            }

            if (!promptClusterResponse.ok) {
                throw new Error(`HTTP error! status: ${promptClusterResponse.status}`);
            }

            const promptClusterData = await promptClusterResponse.json();

            if (promptClusterData.error) {
                throw new Error(promptClusterData.error);
            }

            const promptTopicMapping = promptClusterData.topic_mapping;

            progressFill.style.width = '90%';
            progressFill.textContent = '90%';

            // Apply prompt topic to each result
            allPrompts.forEach((prompt, promptIndex) => {
                const promptTopic = promptTopicMapping[prompt] || 'Uncategorized';
                const { resultIndex, variantIndex } = promptToResultIndex[promptIndex];

                // Set topicPrompt for the result
                if (variantIndex === 0 || !allResults[resultIndex].topicPrompt || allResults[resultIndex].topicPrompt === 'Uncategorized') {
                    allResults[resultIndex].topicPrompt = promptTopic;
                }

                // Store topics per variant
                if (!allResults[resultIndex].topicsPrompt) {
                    allResults[resultIndex].topicsPrompt = [];
                }
                allResults[resultIndex].topicsPrompt[variantIndex] = promptTopic;
            });

            progressFill.style.width = '100%';
            progressFill.textContent = '100%';

            // Update results display
            updateResults();

            progressText.textContent = '✓ Completato!';
            setTimeout(() => {
                progressContainer.classList.remove('active');
            }, 2000);
        }

        function updateResults() {
            resultsBody.innerHTML = '';

            // Update table header based on clustering type
            const thead = document.getElementById('results-thead');
            if (currentClusteringType === 'both') {
                thead.innerHTML = `
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="header-checkbox" style="width: 16px; height: 16px; cursor: pointer;">
                        </th>
                        <th style="width: 160px;">Keyword</th>
                        <th style="width: 70px;">Volume</th>
                        <th style="width: 110px;">Topic (KW)</th>
                        <th style="width: 110px;">Topic (Prompt)</th>
                        <th style="width: 90px;">Intent</th>
                        <th>Prompt Conversazionale</th>
                        <th style="width: 50px;">Var.</th>
                    </tr>
                `;
            } else {
                thead.innerHTML = `
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="header-checkbox" style="width: 16px; height: 16px; cursor: pointer;">
                        </th>
                        <th style="width: 180px;">Keyword</th>
                        <th style="width: 80px;">Volume</th>
                        <th style="width: 120px;">Topic</th>
                        <th style="width: 100px;">Intent</th>
                        <th>Prompt Conversazionale</th>
                        <th style="width: 60px;">Var.</th>
                    </tr>
                `;
            }

            allResults.forEach((result, index) => {
                // Handle both single prompt and variants array
                const prompts = Array.isArray(result.prompts) ? result.prompts : [result.prompt];
                const intents = Array.isArray(result.intents) ? result.intents : [result.intent];
                const variantCount = prompts.length;

                // For multiple variants, create one row per variant
                prompts.forEach((prompt, variantIndex) => {
                    const row = document.createElement('tr');
                    row.setAttribute('data-keyword', result.keyword);
                    row.setAttribute('data-variant', variantIndex);

                    // Intent color coding
                    const intentColors = {
                        'research': '#3b82f6',
                        'comparison': '#8b5cf6',
                        'support': '#10b981',
                        'purchase': '#f59e0b',
                        'improvement': '#ec4899'
                    };
                    const currentIntent = intents[variantIndex] || intents[0] || 'N/A';
                    const intentColor = intentColors[currentIntent.toLowerCase()] || '#6b7280';

                    // Show keyword/volume/topic only on first variant
                    const showMainData = variantIndex === 0;

                    // Build topic cells based on clustering type
                    let topicCells = '';
                    if (currentClusteringType === 'both') {
                        const topicKw = result.topicKeyword || result.topic || 'N/A';
                        const topicPrompt = result.topicPrompt || 'N/A';
                        topicCells = `
                            <td style="color: #667eea; font-weight: 600; font-size: 12px;">${showMainData ? escapeHtml(topicKw) : ''}</td>
                            <td style="color: #10b981; font-weight: 600; font-size: 12px;">${showMainData ? escapeHtml(topicPrompt) : ''}</td>
                        `;
                    } else {
                        topicCells = `
                            <td style="color: #667eea; font-weight: 600; font-size: 13px;">${showMainData ? escapeHtml(result.topic || 'N/A') : ''}</td>
                        `;
                    }

                    row.innerHTML = `
                        <td class="row-checkbox">
                            <input type="checkbox" class="result-checkbox" data-index="${index}" data-variant="${variantIndex}">
                        </td>
                        <td class="keyword-cell">${showMainData ? escapeHtml(result.keyword) : ''}</td>
                        <td class="volume-cell">${showMainData ? formatNumber(result.volume) : ''}</td>
                        ${topicCells}
                        <td>
                            <span style="
                                display: inline-block;
                                padding: 4px 12px;
                                background: ${intentColor}15;
                                color: ${intentColor};
                                border-radius: 12px;
                                font-size: 12px;
                                font-weight: 600;
                                text-transform: capitalize;
                            ">${escapeHtml(currentIntent)}</span>
                        </td>
                        <td class="prompt-cell">${escapeHtml(prompt)}</td>
                        <td class="variant-indicator">
                            ${variantCount > 1 ? `<span class="variant-badge">${variantIndex + 1}/${variantCount}</span>` : ''}
                        </td>
                    `;

                    resultsBody.appendChild(row);
                });
            });
            
            // Update stats
            const totalVolume = allResults.reduce((sum, r) => sum + (parseInt(r.volume) || 0), 0);
            const avgVolume = Math.round(totalVolume / allResults.length);
            
            // Count unique topics and intents
            const uniqueTopics = [...new Set(allResults.map(r => r.topic).filter(Boolean))].length;
            
            // Count unique intents (across all variants)
            const allIntents = allResults.flatMap(r => {
                if (Array.isArray(r.intents)) return r.intents;
                return [r.intent];
            }).filter(Boolean);
            const uniqueIntents = [...new Set(allIntents)].length;
            
            // Count total prompts (including variants)
            const totalPrompts = allResults.reduce((sum, r) => {
                if (Array.isArray(r.prompts)) return sum + r.prompts.length;
                return sum + 1;
            }, 0);
            
            statsContainer.innerHTML = `
                <div class="stat-card">
                    <div class="stat-value">${allResults.length}</div>
                    <div class="stat-label">Keywords Processate</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${totalPrompts}</div>
                    <div class="stat-label">Prompt Generati</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${formatNumber(totalVolume)}</div>
                    <div class="stat-label">Volume Totale</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${uniqueTopics}</div>
                    <div class="stat-label">Topic Identificati</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${uniqueIntents}</div>
                    <div class="stat-label">Intent Diversi</div>
                </div>
            `;
            
            resultsSection.classList.add('active');
            
            // Add checkbox listeners
            attachCheckboxListeners();
        }
        
        function showError(message) {
            errorMessage.textContent = '⚠️ ' + message;
            errorMessage.classList.add('active');
            setTimeout(() => {
                errorMessage.classList.remove('active');
            }, 5000);
        }
        
        function formatNumber(num) {
            if (num === null || num === undefined || num === 'N/A') return 'N/A';
            return parseInt(num).toLocaleString('it-IT');
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Checkbox management
        function attachCheckboxListeners() {
            // Header checkbox (select all)
            const headerCheckbox = document.getElementById('header-checkbox');
            headerCheckbox.addEventListener('change', (e) => {
                const checkboxes = document.querySelectorAll('.result-checkbox');
                checkboxes.forEach(cb => {
                    cb.checked = e.target.checked;
                    updateRowHighlight(cb);
                });
            });
            
            // Individual checkboxes
            document.querySelectorAll('.result-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', (e) => {
                    updateRowHighlight(e.target);
                    
                    // Update header checkbox state
                    const allCheckboxes = document.querySelectorAll('.result-checkbox');
                    const checkedCount = document.querySelectorAll('.result-checkbox:checked').length;
                    headerCheckbox.checked = checkedCount === allCheckboxes.length;
                    headerCheckbox.indeterminate = checkedCount > 0 && checkedCount < allCheckboxes.length;
                });
            });
        }
        
        function updateRowHighlight(checkbox) {
            const row = checkbox.closest('tr');
            if (checkbox.checked) {
                row.classList.add('selected');
            } else {
                row.classList.remove('selected');
            }
        }
        
        // Select all button
        document.getElementById('select-all-btn').addEventListener('click', () => {
            const headerCheckbox = document.getElementById('header-checkbox');
            headerCheckbox.checked = !headerCheckbox.checked;
            headerCheckbox.dispatchEvent(new Event('change'));
        });
        
        // Download selected
        document.getElementById('download-selected-btn').addEventListener('click', () => {
            const selectedCheckboxes = document.querySelectorAll('.result-checkbox:checked');

            if (selectedCheckboxes.length === 0) {
                showError('Seleziona almeno una riga da scaricare');
                return;
            }

            const selectedResults = [];
            selectedCheckboxes.forEach(checkbox => {
                const index = parseInt(checkbox.getAttribute('data-index'));
                const variantIndex = parseInt(checkbox.getAttribute('data-variant'));
                const result = allResults[index];

                // Handle both single and multiple variants
                const prompts = Array.isArray(result.prompts) ? result.prompts : [result.prompt];
                const intents = Array.isArray(result.intents) ? result.intents : [result.intent];

                const rowData = {
                    keyword: result.keyword,
                    volume: result.volume,
                    intent: intents[variantIndex] || intents[0] || 'N/A',
                    prompt: prompts[variantIndex]
                };

                if (currentClusteringType === 'both') {
                    rowData.topicKeyword = result.topicKeyword || result.topic || 'N/A';
                    rowData.topicPrompt = result.topicPrompt || 'N/A';
                } else {
                    rowData.topic = result.topic || 'N/A';
                }

                selectedResults.push(rowData);
            });

            downloadCSV(selectedResults, `seo_to_ai_selected_${Date.now()}.csv`);
        });

        // Download all
        document.getElementById('download-all-btn').addEventListener('click', () => {
            if (allResults.length === 0) return;

            const allRows = [];
            allResults.forEach(result => {
                const prompts = Array.isArray(result.prompts) ? result.prompts : [result.prompt];
                const intents = Array.isArray(result.intents) ? result.intents : [result.intent];

                prompts.forEach((prompt, variantIndex) => {
                    const rowData = {
                        keyword: result.keyword,
                        volume: result.volume,
                        intent: intents[variantIndex] || intents[0] || 'N/A',
                        prompt: prompt
                    };

                    if (currentClusteringType === 'both') {
                        rowData.topicKeyword = result.topicKeyword || result.topic || 'N/A';
                        rowData.topicPrompt = result.topicPrompt || 'N/A';
                    } else {
                        rowData.topic = result.topic || 'N/A';
                    }

                    allRows.push(rowData);
                });
            });

            downloadCSV(allRows, `seo_to_ai_all_${Date.now()}.csv`);
        });

        function downloadCSV(data, filename) {
            let csv;

            if (currentClusteringType === 'both') {
                csv = 'Keyword|Volume|Topic (KW)|Topic (Prompt)|Intent|Prompt\n';
                data.forEach(row => {
                    const keyword = row.keyword.replace(/\|/g, '');
                    const topicKw = (row.topicKeyword || 'N/A').replace(/\|/g, '');
                    const topicPrompt = (row.topicPrompt || 'N/A').replace(/\|/g, '');
                    const intent = (row.intent || 'N/A').replace(/\|/g, '');
                    const prompt = row.prompt.replace(/\|/g, '').replace(/\n/g, ' ');
                    csv += `${keyword}|${row.volume}|${topicKw}|${topicPrompt}|${intent}|${prompt}\n`;
                });
            } else {
                csv = 'Keyword|Volume|Topic|Intent|Prompt\n';
                data.forEach(row => {
                    const keyword = row.keyword.replace(/\|/g, '');
                    const topic = (row.topic || 'N/A').replace(/\|/g, '');
                    const intent = (row.intent || 'N/A').replace(/\|/g, '');
                    const prompt = row.prompt.replace(/\|/g, '').replace(/\n/g, ' ');
                    csv += `${keyword}|${row.volume}|${topic}|${intent}|${prompt}\n`;
                });
            }

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);

            link.setAttribute('href', url);
            link.setAttribute('download', filename);
            link.style.visibility = 'hidden';

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>
