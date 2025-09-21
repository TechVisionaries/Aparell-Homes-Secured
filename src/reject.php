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
    $id = $_GET['aprtID'];
    $sql = "UPDATE apartments 
            SET approved = '0'
            WHERE aprtID = $id";

    if(mysqli_query($conn,$sql)){
        echo "<script>
                alert('Successfully reejected!');
                window.location.replace('toApprove.php');
            </script>";
        
    }
    else{
        echo "<script>
                alert('Unsuccessfull!');
                window.location.replace('toApprove.php');
            </script>";
    }


    mysqli_close($conn);


?>