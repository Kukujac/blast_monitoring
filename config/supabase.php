<?php
// Supabase configuration
define('SUPABASE_URL', 'https://aahrwtlmemaqacubievh.supabase.co');
define('SUPABASE_ANON_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImFhaHJ3dGxtZW1hcWFjdWJpZXZoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzA3ODg5MTgsImV4cCI6MjA4NjM2NDkxOH0.sCQwcgEtEh4VMcIswpF2xA7bHfnc2OiB911YAwZuzvc');
define('SUPABASE_SERVICE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImFhaHJ3dGxtZW1hcWFjdWJpZXZoIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc3MDc4ODkxOCwiZXhwIjoyMDg2MzY0OTE4fQ.qOzkFTIs_YnVmt91I_ODjolhhZ7Bvnp_Dzpo09p_61s');

// CORRECT CONNECTION POOLER SETTINGS
define('DB_HOST', 'aws-1-eu-west-1.pooler.supabase.com');  // Correct pooler host
define('DB_PORT', '6543');                                 // Pooler port
define('DB_NAME', 'postgres');
define('DB_USER', 'postgres.aahrwtlmemaqacubievh');       // Correct username format
define('DB_PASS', 'Kukujac2468.');                         // Your password
define('DB_SCHEMA', 'public');

function getDbConnection() {
    try {
        $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
        
        $conn = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 10
        ]);
        
        // Set schema
        if (defined('DB_SCHEMA') && DB_SCHEMA) {
            $conn->exec("SET search_path TO " . DB_SCHEMA);
        }
        
        return $conn;
        
    } catch (PDOException $e) {
        error_log("Supabase connection failed: " . $e->getMessage());
        throw $e;
    }
}
?>