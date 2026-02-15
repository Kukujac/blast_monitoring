<?php
// Supabase configuration
define('SUPABASE_URL', 'https://aahrwtlmemaqacubievh.supabase.co');
define('SUPABASE_ANON_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImFhaHJ3dGxtZW1hcWFjdWJpZXZoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzA3ODg5MTgsImV4cCI6MjA4NjM2NDkxOH0.sCQwcgEtEh4VMcIswpF2xA7bHfnc2OiB911YAwZuzvc');
define('SUPABASE_SERVICE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImFhaHJ3dGxtZW1hcWFjdWJpZXZoIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc3MDc4ODkxOCwiZXhwIjoyMDg2MzY0OTE4fQ.qOzkFTIs_YnVmt91I_ODjolhhZ7Bvnp_Dzpo09p_61s');

// CORRECTED CONNECTION SETTINGS
define('DB_HOST', 'db.aahrwtlmemaqacubievh.supabase.co');  // Direct connection (not pooler)
define('DB_PORT', '5432');                                 // Standard PostgreSQL port
define('DB_NAME', 'postgres');
define('DB_USER', 'postgres');                             // Simple username for direct connection
define('DB_PASS', 'Kukujac2468.');                         // Your password
define('DB_SCHEMA', 'public');

// Enable error logging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_log("Supabase connection attempt to: " . DB_HOST);

function getDbConnection() {
    try {
        // Connection string with SSL required
        $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";sslmode=require";
        
        error_log("Connecting with DSN: " . $dsn);
        
        $conn = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 30,
            PDO::ATTR_PERSISTENT => false
        ]);
        
        error_log("Connection successful");
        
        // Set schema
        if (defined('DB_SCHEMA') && DB_SCHEMA) {
            $conn->exec("SET search_path TO " . DB_SCHEMA . ", public");
            error_log("Schema set to: " . DB_SCHEMA);
        }
        
        return $conn;
        
    } catch (PDOException $e) {
        error_log("Supabase connection failed: " . $e->getMessage());
        error_log("Error code: " . $e->getCode());
        throw new Exception("Database connection failed: " . $e->getMessage());
    }
}

// Optional: Test function
function testConnection() {
    try {
        $conn = getDbConnection();
        $result = $conn->query("SELECT 1 as test")->fetch();
        return ["success" => true, "message" => "Connection successful", "data" => $result];
    } catch (Exception $e) {
        return ["success" => false, "message" => $e->getMessage()];
    }
}
?>
