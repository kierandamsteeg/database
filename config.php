<?php
/**
 * Database configuration file
 * Establishes secure connection to MySQL database using PDO
 * 
 * @author [Your Name]
 * @version 1.0
 */

// Database credentials - In production, use environment variables
define('DB_HOST', 'localhost');
define('DB_NAME', 'naw_database');
define('DB_USER', 'root');
define('DB_PASS', ''); // Default XAMPP has no password

/**
 * Create PDO database connection
 * 
 * @return PDO|null Returns PDO object or null on failure
 */
function getDBConnection() {
    try {
        // Create DSN (Data Source Name)
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        
        // PDO options for security and error handling
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,        // Throw exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,   // Fetch associative arrays
            PDO::ATTR_EMULATE_PREPARES => false,                // Use real prepared statements
        ];
        
        // Create and return connection
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
        
    } catch (PDOException $e) {
        // Log error (don't show to user in production)
        error_log("Database connection failed: " . $e->getMessage());
        return null;
    }
}
?>