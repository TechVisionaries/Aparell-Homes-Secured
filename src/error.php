<?php
// Prevent direct access
if (!defined('ERROR_HANDLING')) {
    header("HTTP/1.1 403 Forbidden");
    exit("Direct access not allowed");
}

// Set proper status code
http_response_code(500);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
</head>
<body style="font-family: Arial, sans-serif; text-align: center; margin-top: 50px;">
    <h1>Something went wrong</h1>
    <p>We're sorry, but an error occurred. Please try again later.</p>
    <a href="index.php">Return to homepage</a>
</body>
</html>