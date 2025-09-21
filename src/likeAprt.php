<?php
    require_once "config.php";
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

$email = $_GET['email'];
$acc = $_GET['accType'];
$id = $_GET['id'];
$url = $_GET['bkpg'];

$sqlFav = "SELECT aprtID FROM userfavs WHERE email = '$email' AND accType = '$acc' AND aprtID = '$id'";
$favResult = $conn -> query($sqlFav);
if($favResult -> num_rows >0){
    while($favRow = $favResult -> fetch_assoc()){
        $favId = $favRow['aprtID'];
    
                $sqlDeleteLike = "DELETE FROM userfavs WHERE email = '$email' AND accType = '$acc' AND aprtID = $id";
    
                mysqli_query($conn,$sqlDeleteLike);
    
                echo "<script>
                        var linkid = '$url'+'.php#Ad'+$id;
                        window.location.replace(linkid);
                    </script>";
             
    }
}
else{
        $sqlAddLike = "INSERT INTO userfavs(email,accType,aprtID) VALUES('$email','$acc','$id')";

        mysqli_query($conn,$sqlAddLike);

        echo "<script>
                var linkid = '$url'+'.php#Ad'+$id;
                window.location.replace(linkid);
            </script>";                    
    
} 

?>