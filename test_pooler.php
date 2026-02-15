<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>✅ Testing Correct Supabase Pooler</h1>";

require_once 'config/supabase.php';

echo "Host: " . DB_HOST . "<br>";
echo "Port: " . DB_PORT . "<br>";
echo "User: " . DB_USER . "<br>";

try {
    $conn = getDbConnection();
    echo "<p style='color: green; font-size: 20px;'>✅ CONNECTION SUCCESSFUL!</p>";
    
    // Get PostgreSQL version
    $version = $conn->query("SELECT version()")->fetchColumn();
    echo "<h3>PostgreSQL Version:</h3>";
    echo "<pre>" . $version . "</pre>";
    
    // List all tables in your schema
    echo "<h3>Tables in your database:</h3>";
    $tables = $conn->query("
        SELECT table_name 
        FROM information_schema.tables 
        WHERE table_schema = 'public'
        ORDER BY table_name
    ")->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "<p>No tables found in public schema</p>";
    } else {
        echo "<ul>";
        foreach ($tables as $table) {
            try {
                $count = $conn->query("SELECT COUNT(*) FROM \"$table\"")->fetchColumn();
                echo "<li><strong>$table</strong>: $count records</li>";
            } catch (Exception $e) {
                echo "<li><strong>$table</strong>: Error - " . $e->getMessage() . "</li>";
            }
        }
        echo "</ul>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red; font-size: 18px;'>❌ Connection failed!</p>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Error code:</strong> " . $e->getCode() . "</p>";
}
?>