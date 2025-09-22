<?php

    if (!isset($_SESSION['profile'])) {
    $_SESSION['profile'] = 'images/user.png';
    }

    if (!isset($_SESSION['LoginStat'])) {
    $_SESSION['LoginStat'] = false;
    }

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