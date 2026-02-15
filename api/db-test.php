<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$result = [
    'success' => false,
    'server_time' => date('Y-m-d H:i:s'),
    'php_version' => phpversion(),
    'render_ip' => $_SERVER['SERVER_ADDR'] ?? 'unknown',
    'tests' => []
];

// Test 1: Check if we can resolve Supabase hostname
$host = 'db.aahrwtlmemaqacubievh.supabase.co';
$result['tests'][] = [
    'name' => 'DNS Resolution',
    'command' => "Checking $host",
    'ips' => gethostbynamel($host) ?: ['Failed to resolve']
];

// Test 2: Try to connect using fsockopen
$test2 = [
    'name' => 'Socket Connection',
    'host' => $host,
    'port' => 5432
];
$fp = @fsockopen($host, 5432, $errno, $errstr, 5);
if ($fp) {
    $test2['status'] = 'Connected successfully';
    fclose($fp);
} else {
    $test2['status'] = "Failed: $errstr ($errno)";
}
$result['tests'][] = $test2;

// Test 3: Try PDO connection with current settings
try {
    require_once '../config/supabase.php';
    $conn = getDbConnection();
    $result['tests'][] = [
        'name' => 'PDO Connection',
        'status' => 'Connected successfully'
    ];
    
    // Get PostgreSQL version
    $version = $conn->query('SELECT version()')->fetchColumn();
    $result['tests'][] = [
        'name' => 'PostgreSQL Version',
        'status' => $version
    ];
    
    $result['success'] = true;
} catch (Exception $e) {
    $result['tests'][] = [
        'name' => 'PDO Connection',
        'status' => 'Failed',
        'error' => $e->getMessage()
    ];
}

echo json_encode($result, JSON_PRETTY_PRINT);
?>
