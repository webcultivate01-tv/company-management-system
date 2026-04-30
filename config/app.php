<?php
// Application Configuration
define('APP_NAME', 'WebCultivate Software Solutions');
define('APP_VERSION', '1.0.0');

// Auto-detect BASE_URL — works on localhost, XAMPP, and Hostinger
$scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASE_URL', $scheme . '://' . $host);

define('APP_ENV', 'production'); // production | development

// Session settings
define('SESSION_LIFETIME', 7200); // 2 hours
define('SESSION_NAME', 'cms_session');

// Office settings
define('OFFICE_START_TIME', '09:30'); // HH:MM - after this = late
define('OFFICE_END_TIME', '18:00');

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Error reporting
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
