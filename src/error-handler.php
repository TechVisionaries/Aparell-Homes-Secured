<?php
    
session_start();

// ADD SECURITY HEADERS HERE - MUST BE BEFORE ANY OUTPUT
// header("Server: ");
// header("X-Powered-By: ");
// header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://apis.google.com; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");


ini_set('display_errors', 'Off');
ini_set('log_errors', 'On');
ini_set('error_log', __DIR__ . '/../logs/php-errors.log');

function customError($errno, $errstr, $errfile, $errline) {
    error_log("[$errno] $errstr in $errfile on line $errline");
    
    // Only redirect if headers haven't been sent yet
    if (!headers_sent()) {
        define('ERROR_HANDLING', true);
        include __DIR__ . '/error.php';
        exit();
    } else {
        // Fallback if headers already sent
        echo "<script>alert('An unexpected error occurred. Please try again later.'); window.location.href='index.php';</script>";
        exit();
    }
}
set_error_handler("customError");
?>