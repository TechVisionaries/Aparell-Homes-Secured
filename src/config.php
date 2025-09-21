<?php
    // Disable error display to public
    ini_set('display_errors', 'Off');
    ini_set('display_startup_errors', 'Off');
    error_reporting(E_ALL);

    // Log errors only
    ini_set('log_errors', 'On');
    ini_set('error_log', '../logs/php-errors.log');

    //declare variables
    $server = "localhost";
    $username = "root";
    $password = "root";
    $db = "apartment sales system";

    //create connection
    $conn = new mysqli($server,$username,$password,$db);

    //check connection
    if($conn->connect_error){
        die("Connection Failed: ".$conn->connect_error);
    }

?>