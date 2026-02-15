<?php
header('Content-Type: application/json');

require_once '../config/supabase.php';

$result = [
    'timestamp' => date('Y-m-d H:i:s'),
    'tests' => []
];

try {
    // Test 1: Check if PostgreSQL extension is loaded
    $result['tests'][] = [
        'name' => 'PDO PostgreSQL Extension',
        'status' => extension_loaded('pdo_pgsql') ? '✅ Loaded' : '❌ Not loaded'
    ];
    
    // Test 2: Attempt connection
    $conn = getDbConnection();
    $result['tests'][] = [
        'name' => 'Database Connection',
        'status' => '✅ Connected'
    ];
    
    // Test 3: Check current schema
    $stmt = $conn->query("SHOW search_path");
    $schema = $stmt->fetchColumn();
    $result['tests'][] = [
        'name' => 'Search Path',
        'status' => $schema
    ];
    
    // Test 4: List all schemas
    $stmt = $conn->query("SELECT schema_name FROM information_schema.schemata ORDER BY schema_name");
    $schemas = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $result['tests'][] = [
        'name' => 'Available Schemas',
        'status' => implode(', ', $schemas)
    ];
    
    // Test 5: Check if blast_and_drill schema exists
    $stmt = $conn->query("SELECT schema_name FROM information_schema.schemata WHERE schema_name = 'blast_and_drill'");
    $hasSchema = $stmt->fetch();
    $result['tests'][] = [
        'name' => 'blast_and_drill Schema',
        'status' => $hasSchema ? '✅ Exists' : '❌ Not found'
    ];
    
    // Test 6: List tables in public schema
    $stmt = $conn->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $result['tests'][] = [
        'name' => 'Tables in public',
        'status' => implode(', ', $tables) ?: 'None'
    ];
    
    // Test 7: If blast_and_drill exists, list its tables
    if ($hasSchema) {
        $stmt = $conn->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'blast_and_drill' ORDER BY table_name");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $result['tests'][] = [
            'name' => 'Tables in blast_and_drill',
            'status' => implode(', ', $tables) ?: 'None'
        ];
    }
    
    // Test 8: Try a simple query on hole_data
    try {
        $stmt = $conn->query("SELECT COUNT(*) as count FROM hole_data");
        $count = $stmt->fetchColumn();
        $result['tests'][] = [
            'name' => 'hole_data count',
            'status' => $count . ' rows'
        ];
    } catch (Exception $e) {
        $result['tests'][] = [
            'name' => 'hole_data query',
            'status' => '❌ ' . $e->getMessage()
        ];
    }
    
    $result['success'] = true;
    
} catch (Exception $e) {
    $result['success'] = false;
    $result['error'] = $e->getMessage();
}

echo json_encode($result, JSON_PRETTY_PRINT);
?>
