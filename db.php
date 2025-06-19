<?php
$server = "localhost";
$db = "library_db";
$user = "root";
$password = "";

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $conn = new mysqli($server, $user, $password, $db);
    
    if ($conn->connect_error) {
        throw new Exception("Connection Error: " . $conn->connect_error);
    }
    
    // Set charset to ensure proper encoding
    if (!$conn->set_charset("utf8mb4")) {
        throw new Exception("Error setting charset: " . $conn->error);
    }
    
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Database connection failed: " . $e->getMessage());
}
// echo("Connected successfully!!");
?>