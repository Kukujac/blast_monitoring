<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

echo json_encode([
    'status' => 'ok',
    'message' => 'API is working',
    'timestamp' => date('Y-m-d H:i:s'),
    'files' => [
        'get_hole_data.php' => file_exists(__DIR__ . '/get_hole_data.php') ? '✅ Found' : '❌ Not found',
        'get_hole_data_1b.php' => file_exists(__DIR__ . '/get_hole_data_1b.php') ? '✅ Found' : '❌ Not found',
        'get_hole_data_2.php' => file_exists(__DIR__ . '/get_hole_data_2.php') ? '✅ Found' : '❌ Not found',
        'get_blasting_monitoring.php' => file_exists(__DIR__ . '/get_blasting_monitoring.php') ? '✅ Found' : '❌ Not found',
        'pit_data.php' => file_exists(__DIR__ . '/pit_data.php') ? '✅ Found' : '❌ Not found',
        '../config/supabase.php' => file_exists(__DIR__ . '/../config/supabase.php') ? '✅ Found' : '❌ Not found'
    ]
]);
