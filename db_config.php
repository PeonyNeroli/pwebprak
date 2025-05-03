<?php
/**
 * Database Configuration File
 * 
 * This file contains database connection settings for the Campus Arena application.
 */

// Database credentials
define('DB_SERVER', 'localhost');    // Database server (usually localhost)
define('DB_USERNAME', 'root');       // Database username (default for XAMPP/Laragon is 'root')
define('DB_PASSWORD', '');           // Database password (default for XAMPP/Laragon is empty '')
define('DB_NAME', 'campus_arena');   // Database name

/**
 * Function to connect to the database
 * 
 * @return mysqli The database connection object
 */
function connectDB() {
    // Create connection
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

/**
 * Function to close database connection
 * 
 * @param mysqli $conn The database connection to close
 */
function closeDB($conn) {
    $conn->close();
}
?>