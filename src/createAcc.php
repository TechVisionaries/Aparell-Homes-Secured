<?php
    include_once 'config.php';
    include 'includes/security-headers.php';
    // Disable display of errors to users
    ini_set('display_errors', 'Off');
    ini_set('display_startup_errors', 'Off');
    error_reporting(E_ALL);

    // Enable logging to file
    ini_set('log_errors', 'On');
    ini_set('error_log', '../logs/php-errors.log');

    // Custom error handler
    set_error_handler(function ($severity, $message, $file, $line) {
        if (error_reporting() & $severity) {
            error_log("[$severity] $message in $file on line $line");
            header('Location: error.php');
            exit();
        }
    });  

    // Handle fatal errors
    register_shutdown_function(function () {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_COMPILE_ERROR, E_USER_ERROR])) {
            error_log("Fatal Error: {$error['message']} in {$error['file']} on line {$error['line']}");
            include 'error.php';
            exit();
        }
    });
?>
<?php

    //put the values from the reg form into variables
    $firstName = htmlspecialchars($_POST['fName']);
    $lastName = htmlspecialchars($_POST['lName']);
    $address = htmlspecialchars($_POST['addrs']);
    $accType = $_POST['accType'];
    $email = strtolower($_POST['email']);
    $phone = $_POST['phone'];
    $pwd = $_POST['pwd'];

    //get all the emails in the user database
    $sqlView = "SELECT email, accType FROM users";

    //run query and store results
    $result = $conn->query($sqlView);

    if($result->num_rows>0){
        while($row = $result->fetch_assoc()){
            if($row['email'] == $email && $row['accType'] == $accType){
                echo "<script>let confirmation = confirm('Account already exists! Login?');
                              if(confirmation){
                                window.location.replace('loginHTML.php');
                              }
                              else{
                                window.location.replace('register.html');
                              }
                    </script>";
                die("Account Exists");    
            }
        }
    }

    //insert values
    $sqlInsert = "INSERT INTO users(email,fName,lName,addrs,accType,phoneNo,password) VALUES('$email','$firstName','$lastName','$address','$accType','$phone','$pwd');";

    if(mysqli_query($conn,$sqlInsert)){
        echo "<script>
                alert('Successfully Registered!');
                window.location.replace('loginHTML.php');
              </script>";
        
    }
    else{
        echo "<script>
                alert('Registration Unsuccessful!');
                window.location.replace('register.html');
              </script>";
    }


    mysqli_close($conn);
?>