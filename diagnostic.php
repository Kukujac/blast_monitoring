<?php
echo "<h1>System Diagnostic</h1>";

// PHP Version
echo "<h2>PHP Version: " . phpversion() . "</h2>";

// Check extensions
$extensions = ['pgsql', 'pdo_pgsql', 'json', 'curl'];
echo "<h3>Extensions:</h3>";
foreach ($extensions as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? '✅' : '❌') . "<br>";
}

// Check config file
echo "<h3>Config File:</h3>";
$configPath = __DIR__ . '/config/supabase.php';
if (file_exists($configPath)) {
    echo "✅ Config file exists at: $configPath<br>";
    echo "File permissions: " . substr(sprintf('%o', fileperms($configPath)), -4) . "<br>";
} else {
    echo "❌ Config file not found at: $configPath<br>";
}

// Check API files
$apiFiles = [
    'api/get_hole_data.php',
    'api/get_hole_data_1b.php',
    'api/get_hole_data_2.php',
    'api/get_blasting_monitoring.php',
    'api/pit_data.php'
];

echo "<h3>API Files:</h3>";
foreach ($apiFiles as $file) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        echo "✅ $file - " . filesize($fullPath) . " bytes<br>";
    } else {
        echo "❌ $file - MISSING<br>";
    }
}

// Test database connection
echo "<h3>Database Connection Test:</h3>";
if (file_exists($configPath)) {
    try {
        require_once $configPath;
        $conn = getDbConnection();
        echo "✅ Database connection successful<br>";
        
        // List tables
        $tables = $conn->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'")->fetchAll(PDO::FETCH_COLUMN);
        echo "Tables found: " . implode(', ', $tables) . "<br>";
        
    } catch (Exception $e) {
        echo "❌ Connection failed: " . $e->getMessage() . "<br>";
    }
}
?>