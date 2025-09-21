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
    $accType = $_SESSION['AccType'];
    
    //put the values from the reg form into variables
    $firstName = htmlspecialchars($_POST['firstname']);
    $lastName = htmlspecialchars($_POST['lastname']);
    $address = htmlspecialchars($_POST['addrs']);
    $phone = $_POST['phonenumber'];
    $pwd = $_POST['pwd'];

    $sql = "SELECT profile FROM users WHERE  email = '$email' AND accType = '$accType'";
    $result = $conn -> query($sql);
    While($row = $result -> fetch_assoc()){
        $target_file = $row['profile'];
    }


    //getting profile value

    $target_dir = "images/Profiles/";
    $imgName = basename($_FILES["dp"]["name"]);
   
    if($imgName != ''){
        if($target_file != "images/user.png"){
            unlink("$target_file");
        }
        $target_file = $target_dir . $email . "_" . $accType . "_" .$imgName;
        move_uploaded_file($_FILES["dp"]["tmp_name"],$target_file);
    }
    
    $_SESSION['profile'] = "$target_file";

    //update values
    $sql2 = "UPDATE users
            SET fName = '$firstName',
                lName = '$lastName',
                addrs = '$address',
                phoneNo = $phone,
                password = '$pwd',
                profile = '$target_file'
            WHERE email = '$email' AND accType = '$accType';";  
            

    if(mysqli_query($conn,$sql2)){
        echo "<script>
                var acctype = '$accType';
                alert('Successfully Updated!');
                window.location.replace(acctype+'Dash.php');
              </script>";
        
    }
    else{
        echo "<script>
                var acctype = '$accType';
                alert('Update Unsuccessful!');
                window.location.replace(acctype+'Dash.php');
              </script>";
    }


    mysqli_close($conn);
?>