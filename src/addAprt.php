<?php
    include_once 'config.php';
    include 'includes/security-headers.php';
?>
<!-- uploading file -->
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
    if(isset($_SESSION['count'])){

    }
    else{
        $_SESSION['count'] = 1;
    }    

    $id = $_SESSION['count'];

    $target_dir = "images/Apartments/";
    $fileCount = count($_FILES['imgFrm']['name']);
    $target_file = array();

    for($i = 0; $i < $fileCount; $i++){
        $target_file[$i] = $target_dir . $id . "_" .basename($_FILES["imgFrm"]["name"][$i]);
        if(isset($_FILES["imgFrm"])) {
            //check if file exists
            if (file_exists($target_file[$i])) {
                echo "Sorry, file already exists.";
            }
            elseif (move_uploaded_file($_FILES["imgFrm"]["tmp_name"][$i],$target_file[$i])){
                continue;
            }
            else{
                echo "Error while uploading your file.";
            }
        }
        else{
            echo "File not available";
        }
    }    

    $_SESSION['count'] += 1;

    $adType = $_POST['type'];
    $beds = $_POST['beds'];
    $baths = $_POST['baths'];
    $size = $_POST['size'];
    $country = htmlspecialchars($_POST['country']);
    $city = htmlspecialchars($_POST['city']);
    $town = htmlspecialchars($_POST['town']);
    $addrs = htmlspecialchars($_POST['addrs']);
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $price = $_POST['price'];
    $nego = isset($_POST['nego']);
    $sellerMail = $_SESSION['Email'];

    //insert values
    $sql = "INSERT INTO apartments(adType,beds,baths,size,country,city,town,addrs,title,description,price,negotiable,img1,img2,img3,approved,sellerMail) VALUES('$adType','$beds','$baths','$size','$country','$city','$town','$addrs','$title','$description','$price','$nego','$target_file[0]','$target_file[1]','$target_file[2]','NULL','$sellerMail');";

    if(mysqli_query($conn,$sql)){
        echo "<script>
                alert('Successfully Posted!');
                window.location.replace('pendingAprovals.php');
              </script>";
        
    }
    else{
        echo "<script>
                alert('Unsuccessfull!');
                window.location.replace('postAd.php');
              </script>";
    }


    mysqli_close($conn);
?>
