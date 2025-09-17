<?php
/**
 * Database Configuration for LogiTrack
 * 
 * This file contains database connection settings and initialization
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'logitrack');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// PDO options
$pdo_options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
];

/**
 * Get database connection
 * 
 * @return PDO Database connection object
 * @throws Exception If connection fails
 */
function getDatabaseConnection() {
    global $pdo_options;
    
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $pdo_options);
        return $pdo;
    } catch (PDOException $e) {
        throw new Exception("Database connection failed: " . $e->getMessage());
    }
}

/**
 * Test database connection
 * 
 * @return bool True if connection successful
 */
function testDatabaseConnection() {
    try {
        $pdo = getDatabaseConnection();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Initialize database tables if they don't exist
 * This function reads and executes the database.sql file
 */
function initializeDatabase() {
    try {
        $pdo = getDatabaseConnection();
        
        // Read the SQL file
        $sql = file_get_contents(__DIR__ . '/../database.sql');
        
        // Split the SQL into individual statements
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        // Execute each statement
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                $pdo->exec($statement);
            }
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Database initialization failed: " . $e->getMessage());
        return false;
    }
}

// Auto-initialize database if needed
if (!testDatabaseConnection()) {
    // Try to create database first
    try {
        $dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $pdo_options);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    } catch (Exception $e) {
        error_log("Failed to create database: " . $e->getMessage());
    }
    
    // Try to initialize tables
    initializeDatabase();
}
?>
