<?php
/**
 * Application Configuration
 * 
 * This file contains application-wide configuration settings
 */

// Application Settings
define('APP_NAME', 'LogiTrack');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/logitech');
define('APP_EMAIL', 'info@logitrack.com');

// Session Settings
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds
define('SESSION_NAME', 'LOGITRACK_SESSION');

// Security Settings
define('CSRF_TOKEN_EXPIRY', 3600); // 1 hour
define('PASSWORD_MIN_LENGTH', 6);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes

// Pagination Settings
define('ORDERS_PER_PAGE', 20);
define('USERS_PER_PAGE', 20);

// File Upload Settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);

// Email Settings (for future use)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('SMTP_FROM_EMAIL', 'noreply@logitrack.com');
define('SMTP_FROM_NAME', 'LogiTrack');

// API Settings
define('API_VERSION', 'v1');
define('API_RATE_LIMIT', 100); // requests per hour

// Timezone
date_default_timezone_set('UTC');

// Error Reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Logging
define('LOG_FILE', __DIR__ . '/../logs/app.log');
define('LOG_LEVEL', 'INFO'); // DEBUG, INFO, WARNING, ERROR

// Feature Flags
define('FEATURE_EMAIL_NOTIFICATIONS', false);
define('FEATURE_SMS_NOTIFICATIONS', false);
define('FEATURE_MOBILE_APP', false);
define('FEATURE_ADVANCED_REPORTING', false);

// Service Settings
define('TRACKING_UPDATE_INTERVAL', 300); // 5 minutes
define('AUTO_CLEANUP_DAYS', 90); // Clean up old tracking entries after 90 days

// Cache Settings
define('CACHE_ENABLED', false);
define('CACHE_TTL', 3600); // 1 hour

// Maintenance Mode
define('MAINTENANCE_MODE', false);
define('MAINTENANCE_MESSAGE', 'The system is currently under maintenance. Please try again later.');

/**
 * Get application setting
 * 
 * @param string $key Setting key
 * @param mixed $default Default value if setting not found
 * @return mixed Setting value
 */
function getAppSetting($key, $default = null) {
    return defined($key) ? constant($key) : $default;
}

/**
 * Check if feature is enabled
 * 
 * @param string $feature Feature name
 * @return bool True if feature is enabled
 */
function isFeatureEnabled($feature) {
    $featureConstant = 'FEATURE_' . strtoupper($feature);
    return defined($featureConstant) ? constant($featureConstant) : false;
}

/**
 * Check if maintenance mode is enabled
 * 
 * @return bool True if maintenance mode is enabled
 */
function isMaintenanceMode() {
    return getAppSetting('MAINTENANCE_MODE', false);
}

/**
 * Get maintenance message
 * 
 * @return string Maintenance message
 */
function getMaintenanceMessage() {
    return getAppSetting('MAINTENANCE_MESSAGE', 'The system is currently under maintenance.');
}

/**
 * Log application message
 * 
 * @param string $message Log message
 * @param string $level Log level
 * @return bool True on success
 */
function logMessage($message, $level = 'INFO') {
    $logFile = getAppSetting('LOG_FILE');
    if (!$logFile) {
        return false;
    }
    
    // Create logs directory if it doesn't exist
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] [$level] $message" . PHP_EOL;
    
    return file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX) !== false;
}

/**
 * Initialize application
 */
function initializeApp() {
    // Start session with custom settings
    if (session_status() === PHP_SESSION_NONE) {
        session_name(getAppSetting('SESSION_NAME', 'LOGITRACK_SESSION'));
        session_start();
    }
    
    // Check maintenance mode
    if (isMaintenanceMode() && !isAdmin()) {
        http_response_code(503);
        die(getMaintenanceMessage());
    }
    
    // Set headers
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    
    // Log application start
    logMessage('Application initialized', 'INFO');
}

// Auto-initialize if not included from another file
if (!defined('APP_INITIALIZED')) {
    define('APP_INITIALIZED', true);
    initializeApp();
}
?>
