<?php
// Supabase API configuration (alternative to direct PostgreSQL)
define('SUPABASE_URL', 'https://aahrwtlmemaqacubievh.supabase.co');
define('SUPABASE_ANON_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImFhaHJ3dGxtZW1hcWFjdWJpZXZoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzA3ODg5MTgsImV4cCI6MjA4NjM2NDkxOH0.sCQwcgEtEh4VMcIswpF2xA7bHfnc2OiB911YAwZuzvc');
define('SUPABASE_SERVICE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImFhaHJ3dGxtZW1hcWFjdWJpZXZoIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc3MDc4ODkxOCwiZXhwIjoyMDg2MzY0OTE4fQ.qOzkFTIs_YnVmt91I_ODjolhhZ7Bvnp_Dzpo09p_61s');

function supabaseApiRequest($table, $params = []) {
    $url = SUPABASE_URL . '/rest/v1/' . $table;
    
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . SUPABASE_ANON_KEY,
        'Authorization: Bearer ' . SUPABASE_ANON_KEY,
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode >= 400) {
        throw new Exception("API request failed with status $httpCode: $response");
    }
    
    return json_decode($response, true);
}
?>