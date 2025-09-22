<?php
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://apis.google.com 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:");

ini_set('display_errors', 'Off');
ini_set('log_errors', 'On');
ini_set('error_log', __DIR__ . '/../logs/php-errors.log');

function customError($errno, $errstr, $errfile, $errline) {
    error_log("[$errno] $errstr in $errfile on line $errline");
    
    if (!headers_sent()) {
        define('ERROR_HANDLING', true);
        include __DIR__ . '/error.php';
        exit();
    } else {
        // Fallback if headers already sent
        echo "<script>alert('An unexpected error occurred. Please try again later.');</script>";
        echo "<script>window.location.href='index.php';</script>";
        exit();
    }
}
set_error_handler("customError");
?>