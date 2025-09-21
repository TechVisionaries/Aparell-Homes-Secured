<?php
// Prevent MIME sniffing
header('X-Content-Type-Options: nosniff');

// Prevent clickjacking
header('X-Frame-Options: DENY');

// Enable XSS filter in browsers
header('X-XSS-Protection: 1; mode=block');

// Disallow untrusted content
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://apis.google.com");
?>