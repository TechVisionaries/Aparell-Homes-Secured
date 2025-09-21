<?php
    include_once 'config.php';
    include 'includes/security-headers.php';
?>
<?php
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
    session_start();
    $email = $_SESSION['Email'];
    $acc = $_SESSION['AccType'];

    $sqlSelect = "SELECT profile FROM users WHERE email = '$email' AND accType = '$acc'";

    $result = $conn -> query($sqlSelect);
    if($result->num_rows>0){
        while($row = $result -> fetch_assoc()){
            $dp = $row['profile'];
        }
    }  

    $sqlDelete = "DELETE FROM users WHERE email = '$email' AND accType = '$acc'";
    $sqlDeleteAprt = "DELETE FROM apartments WHERE sellerMail = '$email'";
    $sqlDeletefav = "DELETE FROM userfavs WHERE email = '$email' AND accType = 'seller'";

    if(mysqli_query($conn,$sqlDelete)){
        if($dp != "images/user.png"){
            unlink("$dp");
        }
        mysqli_query($conn,$sqlDeleteAprt);
        mysqli_query($conn,$sqlDeletefav);

        $_SESSION['SellerSignedIn'] = false;
        $_SESSION['LoginStat'] = false;

        echo "<script>
                alert('Successfully deleted!');
                window.location.replace('index.php');
              </script>";
    }
    else{
        echo "<script>
                let type = '$acc';
                alert('Unsuccessfull!');
                window.location.replace('sellerDash.php');
              </script>";
    }


    mysqli_close($conn);
?>