<?php
/**
 * SEO to AI Prompt Converter - Backend Processor v4.0
 * Multi-market support + Mixed intents
 */

ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
set_time_limit(300);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!file_exists('.env')) {
    die(json_encode(['error' => 'File .env non trovato.']));
}

function parseEnvFile($filePath) {
    $vars = [];
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || $line[0] === '#') continue;
        
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (strpos($value, ' #') !== false) {
                $value = trim(substr($value, 0, strpos($value, ' #')));
            }
            $value = trim($value, '"\'');
            $vars[$key] = $value;
        }
    }
    return $vars;
}

$env = parseEnvFile('.env');
$SEMRUSH_API_KEY = $env['SEMRUSH_API_KEY'] ?? '';
$OPENAI_API_KEY = $env['OPENAI_API_KEY'] ?? '';

if (empty($SEMRUSH_API_KEY) || empty($OPENAI_API_KEY)) {
    die(json_encode(['error' => 'API keys non configurate nel file .env']));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['error' => 'Metodo non consentito']));
}

$action = $_POST['action'] ?? '';

// PHASE 1: Topic Clustering (keywords)
if ($action === 'cluster') {
    $keywords = json_decode($_POST['keywords'] ?? '[]', true);
    $market = $_POST['market'] ?? 'it';

    if (empty($keywords)) {
        die(json_encode(['error' => 'Nessuna keyword fornita per clustering']));
    }

    if (!is_array($keywords)) {
        die(json_encode(['error' => 'Formato keywords non valido']));
    }

    try {
        error_log("Starting clustering for " . count($keywords) . " keywords (market: $market)");
        $topicMapping = performTopicClustering($keywords, $OPENAI_API_KEY, $market);

        echo json_encode([
            'success' => true,
            'topic_mapping' => $topicMapping,
            'clusters_found' => count(array_unique(array_values($topicMapping)))
        ]);
    } catch (Exception $e) {
        error_log("Clustering error: " . $e->getMessage());
        die(json_encode(['error' => 'Errore clustering: ' . $e->getMessage()]));
    }
    exit;
}

// Clustering dei prompt generati
if ($action === 'cluster_prompts') {
    $prompts = json_decode($_POST['prompts'] ?? '[]', true);
    $market = $_POST['market'] ?? 'it';

    if (empty($prompts)) {
        die(json_encode(['error' => 'Nessun prompt fornito per clustering']));
    }

    if (!is_array($prompts)) {
        die(json_encode(['error' => 'Formato prompts non valido']));
    }

    try {
        error_log("Starting prompt clustering for " . count($prompts) . " prompts (market: $market)");
        $topicMapping = performPromptClustering($prompts, $OPENAI_API_KEY, $market);

        echo json_encode([
            'success' => true,
            'topic_mapping' => $topicMapping,
            'clusters_found' => count(array_unique(array_values($topicMapping)))
        ]);
    } catch (Exception $e) {
        error_log("Prompt clustering error: " . $e->getMessage());
        die(json_encode(['error' => 'Errore clustering prompt: ' . $e->getMessage()]));
    }
    exit;
}

// PHASE 2: Process keywords
if ($action === 'process') {
    $keywords = json_decode($_POST['keywords'] ?? '[]', true);
    $batch = intval($_POST['batch'] ?? 1);
    $total_batches = intval($_POST['total_batches'] ?? 1);
    $topicMapping = json_decode($_POST['topic_mapping'] ?? '{}', true);
    $customIntents = json_decode($_POST['custom_intents'] ?? '[]', true);
    $generateVariants = ($_POST['generate_variants'] ?? '0') === '1';
    $market = $_POST['market'] ?? 'it';
    
    if (empty($keywords)) {
        die(json_encode(['error' => 'Nessuna keyword fornita']));
    }
    
    if (!is_array($keywords)) {
        die(json_encode(['error' => 'Formato keywords non valido']));
    }
    
    if (!is_array($topicMapping)) {
        die(json_encode(['error' => 'Formato topic mapping non valido']));
    }
    
    error_log("Processing batch $batch/$total_batches with " . count($keywords) . " keywords (market: $market, variants: " . ($generateVariants ? 'yes' : 'no') . ")");
    
    $intentList = !empty($customIntents) && is_array($customIntents) ? $customIntents : 
        ['research', 'comparison', 'support', 'purchase', 'improvement'];
    
    $results = [];
    
    foreach ($keywords as $keyword) {
        try {
            $volume = getSemrushVolume($keyword, $SEMRUSH_API_KEY, $market);
            $topic = $topicMapping[$keyword] ?? 'Uncategorized';
            
            if ($generateVariants) {
                $variants = generateMultiplePromptsWithDifferentIntents($keyword, $intentList, $OPENAI_API_KEY, 3, $market);
                
                $results[] = [
                    'keyword' => $keyword,
                    'volume' => $volume,
                    'topic' => $topic,
                    'intents' => array_column($variants, 'intent'),
                    'prompts' => array_column($variants, 'prompt')
                ];
            } else {
                $variants = generateMultiplePromptsWithDifferentIntents($keyword, $intentList, $OPENAI_API_KEY, 3, $market);
                $bestVariant = $variants[0];
                
                $results[] = [
                    'keyword' => $keyword,
                    'volume' => $volume,
                    'topic' => $topic,
                    'intent' => $bestVariant['intent'],
                    'prompt' => $bestVariant['prompt']
                ];
            }
            
        } catch (Exception $e) {
            error_log("Error processing keyword '$keyword': " . $e->getMessage());
            
            $results[] = [
                'keyword' => $keyword,
                'volume' => 'N/A',
                'topic' => $topicMapping[$keyword] ?? 'N/A',
                'intent' => 'N/A',
                'prompt' => 'Errore durante la generazione del prompt'
            ];
        }
    }
    
    echo json_encode([
        'success' => true,
        'batch' => $batch,
        'total_batches' => $total_batches,
        'results' => $results
    ]);
    exit;
}

die(json_encode(['error' => 'Azione non valida']));

function getLanguageForMarket($market) {
    $languageMap = [
        'it' => 'Italian',
        'us' => 'English',
        'uk' => 'English',
        'es' => 'Spanish',
        'fr' => 'French',
        'de' => 'German'
    ];
    return $languageMap[$market] ?? 'English';
}

function performTopicClustering($keywords, $apiKey, $market = 'it') {
    $url = 'https://api.openai.com/v1/chat/completions';
    $language = getLanguageForMarket($market);
    
    $keywordList = implode("\n", array_slice($keywords, 0, 500));
    
    $systemPrompt = <<<PROMPT
You are an expert SEO topic clustering analyst. Your task: analyze keywords and group them into EXACTLY 5 distinct, meaningful topic clusters.

CRITICAL RULES:
1. Create EXACTLY 5 clusters (no more, no less)
2. Each cluster SPECIFIC and MEANINGFUL - NO generic catch-all clusters like "General", "Other", "Miscellaneous"
3. Each cluster name 2-4 words maximum, descriptive and clear
4. Distribute keywords intelligently - assign to CLOSEST semantic cluster
5. Clusters reflect actual semantic groupings
6. Choose the 5 MOST SIGNIFICANT topic areas represented
7. Cluster names must be in {$language}

OUTPUT FORMAT:
Return ONLY a JSON object mapping each keyword to its cluster name.
{
  "keyword1": "Cluster Name A",
  "keyword2": "Cluster Name B",
  ...
}

Cluster names must be consistent (same spelling/capitalization for same cluster).
PROMPT;

    $userPrompt = "Analyze these keywords and create EXACTLY 5 specific topic clusters:\n\n" . $keywordList;
    
    $payload = [
        'model' => 'gpt-4o',
        'messages' => [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt]
        ],
        'temperature' => 0.3,
        'response_format' => ['type' => 'json_object']
    ];
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 60,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ]
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("Errore cURL OpenAI clustering: $error");
    }
    
    curl_close($ch);
    
    if ($httpCode !== 200) {
        $errorBody = json_decode($response, true);
        $errorMsg = $errorBody['error']['message'] ?? 'Unknown error';
        throw new Exception("OpenAI API error: HTTP $httpCode - $errorMsg");
    }
    
    $data = json_decode($response, true);
    
    if (!isset($data['choices'][0]['message']['content'])) {
        throw new Exception("Invalid OpenAI response format");
    }
    
    $content = $data['choices'][0]['message']['content'];
    $topicMapping = json_decode($content, true);
    
    if (!is_array($topicMapping)) {
        throw new Exception("Invalid clustering response format");
    }
    
    return $topicMapping;
}

function performPromptClustering($prompts, $apiKey, $market = 'it') {
    $url = 'https://api.openai.com/v1/chat/completions';
    $language = getLanguageForMarket($market);

    // Crea lista numerata dei prompt per il clustering
    $promptList = "";
    foreach (array_slice($prompts, 0, 500) as $index => $prompt) {
        $promptList .= ($index + 1) . ". " . $prompt . "\n";
    }

    $systemPrompt = <<<PROMPT
You are an expert topic clustering analyst. Your task: analyze conversational AI prompts and group them into EXACTLY 5 distinct, meaningful topic clusters based on the SUBJECT MATTER and TOPIC of each prompt.

CRITICAL RULES:
1. Create EXACTLY 5 clusters (no more, no less)
2. Each cluster must be SPECIFIC and MEANINGFUL - NO generic catch-all clusters like "General", "Other", "Miscellaneous"
3. Each cluster name should be 2-4 words maximum, descriptive and clear
4. Distribute prompts intelligently - assign to CLOSEST semantic cluster based on the TOPIC discussed
5. Focus on the SUBJECT of the prompt, not the intent (research, purchase, comparison, etc.)
6. Clusters should reflect actual semantic topic groupings
7. Choose the 5 MOST SIGNIFICANT topic areas represented
8. Cluster names must be in {$language}

OUTPUT FORMAT:
Return ONLY a JSON object mapping each prompt INDEX (1-based) to its cluster name.
{
  "1": "Cluster Name A",
  "2": "Cluster Name B",
  ...
}

Cluster names must be consistent (same spelling/capitalization for same cluster).
PROMPT;

    $userPrompt = "Analyze these prompts and create EXACTLY 5 specific topic clusters based on their subject matter:\n\n" . $promptList;

    $payload = [
        'model' => 'gpt-4o',
        'messages' => [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt]
        ],
        'temperature' => 0.3,
        'response_format' => ['type' => 'json_object']
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 60,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ]
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("Errore cURL OpenAI prompt clustering: $error");
    }

    curl_close($ch);

    if ($httpCode !== 200) {
        $errorBody = json_decode($response, true);
        $errorMsg = $errorBody['error']['message'] ?? 'Unknown error';
        throw new Exception("OpenAI API error: HTTP $httpCode - $errorMsg");
    }

    $data = json_decode($response, true);

    if (!isset($data['choices'][0]['message']['content'])) {
        throw new Exception("Invalid OpenAI response format");
    }

    $content = $data['choices'][0]['message']['content'];
    $indexMapping = json_decode($content, true);

    if (!is_array($indexMapping)) {
        throw new Exception("Invalid prompt clustering response format");
    }

    // Converti il mapping da indice a prompt
    $topicMapping = [];
    foreach ($indexMapping as $index => $topic) {
        $idx = intval($index) - 1; // Converti da 1-based a 0-based
        if (isset($prompts[$idx])) {
            $topicMapping[$prompts[$idx]] = $topic;
        }
    }

    return $topicMapping;
}

function getSemrushVolume($keyword, $apiKey, $database = 'it') {
    $url = 'https://api.semrush.com/';
    
    $params = [
        'type' => 'phrase_this',
        'key' => $apiKey,
        'phrase' => $keyword,
        'database' => $database,
        'export_columns' => 'Ph,Nq'
    ];
    
    $url .= '?' . http_build_query($params);
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_FOLLOWLOCATION => true
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("Errore cURL Semrush: $error");
    }
    
    curl_close($ch);
    
    if ($httpCode !== 200) {
        throw new Exception("Semrush API error: HTTP $httpCode");
    }
    
    $lines = explode("\n", trim($response));
    
    if (count($lines) < 2) {
        return 'N/A';
    }
    
    $data = str_getcsv($lines[1], ';');
    $volume = isset($data[1]) ? intval($data[1]) : 0;
    
    return $volume;
}

function generateMultiplePromptsWithDifferentIntents($keyword, $intentList, $apiKey, $count = 3, $market = 'it') {
    $url = 'https://api.openai.com/v1/chat/completions';
    $language = getLanguageForMarket($market);
    $intentListStr = implode(', ', $intentList);
    
    $systemPrompt = <<<PROMPT
You are an expert at analyzing search intent and creating diverse conversational AI prompts.

Your task: For a given keyword, generate {$count} DIFFERENT conversational prompts, each reflecting a DIFFERENT user intent.

INTENT CATEGORIES AVAILABLE: {$intentListStr}

INTENT DEFINITIONS:
- research: User wants to learn, understand, or gather information
- comparison: User wants to compare options or evaluate differences  
- support: User needs help solving a problem or troubleshooting
- purchase: User is ready to buy or looking for recommendations
- improvement: User wants to optimize or improve something they have

CRITICAL RULES:
1. Generate {$count} prompts with DIFFERENT intents (NO duplicates)
2. Each prompt naturally conversational and realistic
3. Same keyword topic but different intent angles
4. Output prompts MUST be in {$language}
5. Sound like real native {$language} speakers talking to AI assistant
6. Use natural {$language} expressions and conversational markers
7. Mimic natural human language and behaviour in interactions with AI

OUTPUT FORMAT (JSON):
{
  "prompts": [
    {"intent": "intent1", "prompt": "conversational prompt 1 in {$language}"},
    {"intent": "intent2", "prompt": "conversational prompt 2 in {$language}"},
    {"intent": "intent3", "prompt": "conversational prompt 3 in {$language}"}
  ]
}

EXAMPLE for Italian (market: it):
Input: "crm migliore"
Output:
{
  "prompts": [
    {"intent": "purchase", "prompt": "Mi serve un CRM nuovo per la mia azienda. Quali sono i migliori sul mercato nel 2025?"},
    {"intent": "comparison", "prompt": "Sto valutando diversi CRM. Puoi aiutarmi a confrontare le opzioni principali e capire quale fa al caso mio?"},
    {"intent": "research", "prompt": "Vorrei capire meglio come funzionano i CRM moderni. Quali sono le caratteristiche fondamentali che dovrei cercare?"}
  ]
}

EXAMPLE for English (market: us/uk):
Input: "best crm"
Output:
{
  "prompts": [
    {"intent": "purchase", "prompt": "I need a new CRM for my business. What are the best options available in 2025?"},
    {"intent": "comparison", "prompt": "I'm evaluating different CRM platforms. Can you help me compare the main options and figure out which one suits my needs?"},
    {"intent": "research", "prompt": "I'd like to better understand how modern CRMs work. What are the key features I should be looking for?"}
  ]
}

EXAMPLE for Spanish (market: es):
Input: "mejor crm"
Output:
{
  "prompts": [
    {"intent": "purchase", "prompt": "Necesito un CRM nuevo para mi empresa. ¿Cuáles son las mejores opciones disponibles en 2025?"},
    {"intent": "comparison", "prompt": "Estoy evaluando diferentes plataformas de CRM. ¿Puedes ayudarme a comparar las principales opciones?"},
    {"intent": "research", "prompt": "Me gustaría entender mejor cómo funcionan los CRM modernos. ¿Cuáles son las características clave que debería buscar?"}
  ]
}

IMPORTANT: Always generate {$count} prompts with DIFFERENT intents. Never repeat the same intent twice.
All prompts MUST be in {$language}.
PROMPT;

    $payload = [
        'model' => 'gpt-4o',
        'messages' => [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $keyword]
        ],
        'temperature' => 0.85,
        'response_format' => ['type' => 'json_object']
    ];
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ]
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("Errore cURL OpenAI: $error");
    }
    
    curl_close($ch);
    
    if ($httpCode !== 200) {
        $errorBody = json_decode($response, true);
        $errorMsg = $errorBody['error']['message'] ?? 'Unknown error';
        throw new Exception("OpenAI API error: HTTP $httpCode - $errorMsg");
    }
    
    $data = json_decode($response, true);
    
    if (!isset($data['choices'][0]['message']['content'])) {
        throw new Exception("Invalid OpenAI response format");
    }
    
    $content = $data['choices'][0]['message']['content'];
    $result = json_decode($content, true);
    
    if (!isset($result['prompts']) || !is_array($result['prompts'])) {
        throw new Exception("Invalid AI response structure - missing prompts array");
    }
    
    return array_slice($result['prompts'], 0, $count);
}
