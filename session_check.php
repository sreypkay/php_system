<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Store the requested URL for redirect after login (except for login.php)
    if (!strpos($_SERVER['PHP_SELF'], 'login.php')) {
        $_SESSION['redirect_url'] = $_SERVER['PHP_SELF'];
    }
    
    // Redirect to login page
    header("Location: login.php");
    exit();
}
?> 