<?php
/**
 * Debug Test File - Run this to check configuration
 * Access: http://yoursite.com/seo-ai-tool/test.php
 */

header('Content-Type: text/html; charset=utf-8');

echo "<h1>🔍 SEO AI Tool - Diagnostic Test</h1>";
echo "<hr>";

// 1. PHP Version
echo "<h2>1. PHP Version</h2>";
echo "PHP Version: " . phpversion() . "<br>";
$required = '7.4.0';
if (version_compare(phpversion(), $required, '>=')) {
    echo "✅ PHP version OK (>= 7.4)<br>";
} else {
    echo "❌ PHP version too old. Required: >= 7.4<br>";
}
echo "<hr>";

// 2. cURL Extension
echo "<h2>2. cURL Extension</h2>";
if (function_exists('curl_version')) {
    $curl = curl_version();
    echo "✅ cURL enabled<br>";
    echo "Version: " . $curl['version'] . "<br>";
    echo "SSL Version: " . $curl['ssl_version'] . "<br>";
} else {
    echo "❌ cURL NOT enabled<br>";
}
echo "<hr>";

// 3. JSON Extension
echo "<h2>3. JSON Extension</h2>";
if (function_exists('json_encode')) {
    echo "✅ JSON enabled<br>";
    $test = json_encode(['test' => 'ok']);
    echo "Test encode: " . $test . "<br>";
} else {
    echo "❌ JSON NOT enabled<br>";
}
echo "<hr>";

// 4. .env File
echo "<h2>4. Configuration File (.env)</h2>";
if (file_exists('.env')) {
    echo "✅ .env file exists<br>";
    
    // Custom parser to handle comments
    function parseEnvFile($filePath) {
        $vars = [];
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Skip comments
            if (empty($line) || $line[0] === '#') {
                continue;
            }
            
            // Parse KEY=VALUE
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remove inline comments
                if (strpos($value, ' #') !== false) {
                    $value = trim(substr($value, 0, strpos($value, ' #')));
                }
                
                // Remove quotes if present
                $value = trim($value, '"\'');
                
                $vars[$key] = $value;
            }
        }
        
        return $vars;
    }
    
    $env = parseEnvFile('.env');
    
    if (isset($env['SEMRUSH_API_KEY']) && !empty($env['SEMRUSH_API_KEY'])) {
        if ($env['SEMRUSH_API_KEY'] === 'your_semrush_api_key_here') {
            echo "⚠️ SEMRUSH_API_KEY not configured (still default value)<br>";
        } else {
            echo "✅ SEMRUSH_API_KEY configured (length: " . strlen($env['SEMRUSH_API_KEY']) . ")<br>";
        }
    } else {
        echo "❌ SEMRUSH_API_KEY missing<br>";
    }
    
    if (isset($env['OPENAI_API_KEY']) && !empty($env['OPENAI_API_KEY'])) {
        if ($env['OPENAI_API_KEY'] === 'your_openai_api_key_here') {
            echo "⚠️ OPENAI_API_KEY not configured (still default value)<br>";
        } else {
            echo "✅ OPENAI_API_KEY configured (starts with: " . substr($env['OPENAI_API_KEY'], 0, 7) . "...)<br>";
        }
    } else {
        echo "❌ OPENAI_API_KEY missing<br>";
    }
} else {
    echo "❌ .env file NOT found<br>";
    echo "📝 Action: Copy .env.example to .env and configure API keys<br>";
}
echo "<hr>";

// 5. File Permissions
echo "<h2>5. File Permissions</h2>";
$files = ['index.php', 'process.php', '.env'];
foreach ($files as $file) {
    if (file_exists($file)) {
        $perms = substr(sprintf('%o', fileperms($file)), -4);
        echo "$file: $perms ";
        if (is_readable($file)) {
            echo "✅ readable<br>";
        } else {
            echo "❌ NOT readable<br>";
        }
    } else {
        echo "$file: ❌ NOT found<br>";
    }
}
echo "<hr>";

// 6. Test JSON Response
echo "<h2>6. Test JSON Response</h2>";
$testData = [
    'success' => true,
    'message' => 'Test response',
    'data' => [
        'keyword' => 'test keyword',
        'volume' => 1000
    ]
];
$json = json_encode($testData);
echo "JSON encode test:<br>";
echo "<pre>" . htmlspecialchars($json) . "</pre>";
if (json_last_error() === JSON_ERROR_NONE) {
    echo "✅ JSON encoding OK<br>";
} else {
    echo "❌ JSON error: " . json_last_error_msg() . "<br>";
}
echo "<hr>";

// 7. Test POST Simulation
echo "<h2>7. Test POST Processing</h2>";
echo "Simulating process.php response...<br>";

$_POST['action'] = 'test';
$_POST['keywords'] = json_encode(['test keyword 1', 'test keyword 2']);

ob_start();
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'test' => 'This is what process.php should return',
    'received_action' => $_POST['action'] ?? 'none',
    'received_keywords' => json_decode($_POST['keywords'] ?? '[]', true)
]);
$output = ob_get_clean();

echo "<strong>Expected output:</strong><br>";
echo "<pre>" . htmlspecialchars($output) . "</pre>";

$decoded = json_decode($output, true);
if (json_last_error() === JSON_ERROR_NONE) {
    echo "✅ JSON response valid<br>";
} else {
    echo "❌ JSON response invalid: " . json_last_error_msg() . "<br>";
}
echo "<hr>";

// 8. Server Info
echo "<h2>8. Server Configuration</h2>";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Path: " . __FILE__ . "<br>";
echo "Max Execution Time: " . ini_get('max_execution_time') . "s<br>";
echo "Memory Limit: " . ini_get('memory_limit') . "<br>";
echo "allow_url_fopen: " . (ini_get('allow_url_fopen') ? 'Yes' : 'No') . "<br>";
echo "<hr>";

// Final Summary
echo "<h2>📊 Summary</h2>";
$checks = [
    'PHP >= 7.4' => version_compare(phpversion(), '7.4.0', '>='),
    'cURL enabled' => function_exists('curl_version'),
    'JSON enabled' => function_exists('json_encode'),
    '.env exists' => file_exists('.env'),
];

// Check API keys with custom parser
$apiKeysOk = false;
if (file_exists('.env')) {
    $env = parseEnvFile('.env');
    $apiKeysOk = !empty($env['SEMRUSH_API_KEY'] ?? '') &&
                 $env['SEMRUSH_API_KEY'] !== 'your_semrush_api_key_here' &&
                 !empty($env['OPENAI_API_KEY'] ?? '') &&
                 $env['OPENAI_API_KEY'] !== 'your_openai_api_key_here';
}

$checks['API keys configured'] = $apiKeysOk;

$passed = 0;
$total = count($checks);

foreach ($checks as $check => $result) {
    if ($result) {
        echo "✅ $check<br>";
        $passed++;
    } else {
        echo "❌ $check<br>";
    }
}

echo "<hr>";
echo "<h3>Score: $passed/$total</h3>";

if ($passed === $total) {
    echo "<p style='color: green; font-weight: bold;'>🎉 All checks passed! The tool should work correctly.</p>";
} else {
    echo "<p style='color: orange; font-weight: bold;'>⚠️ Some checks failed. Please fix the issues above.</p>";
}

echo "<hr>";
echo "<p><a href='index.php'>← Back to Tool</a></p>";
?>
