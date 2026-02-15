<?php
// Supabase configuration - Copy this file to supabase.php and fill in your details

// Get these from your Supabase project settings
// Project URL: https://app.supabase.com/project/[YOUR-PROJECT-ID]/settings/api
define('SUPABASE_URL', 'https://your-project-id.supabase.co');
define('SUPABASE_ANON_KEY', 'your-anon-key-here');
define('SUPABASE_SERVICE_KEY', 'your-service-role-key-here');

// Database connection parameters (from Supabase connection pooling)
// Find these in: Project Settings -> Database -> Connection string
define('DB_HOST', 'aws-0-ap-southeast-1.pooler.supabase.com');
define('DB_PORT', '6543');
define('DB_NAME', 'postgres');
define('DB_USER', 'postgres.your-project-id');
define('DB_PASS', 'your-database-password');
define('DB_SCHEMA', 'public'); // Change to your schema if different
?>