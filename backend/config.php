<?php
// Database Configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'dry');

// Session Configuration
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds
define('APP_NAME', 'Dairy Management System');
define('APP_VERSION', '1.0.0');

// Create connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}

// Set charset to UTF8
$conn->set_charset("utf8");
?>
