<?php
    ob_start();
    session_start();
    include_once 'error-handler.php';
    include_once 'config.php';
    header("Content-Security-Policy: default-src 'self'; script-src 'self' https://apis.google.com 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; frame-ancestors 'none'; base-uri 'self'; form-action 'self'");
?>
<?php
    $email = strtolower($_POST['email'] ?? '');
    $pwd = $_POST['pwd'] ?? '';
    $accType = $_POST['accType'] ?? '';

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$pwd' AND accType='$accType'";

    $result = $conn->query($sql);

    $row = $result->fetch_assoc();

    if($result->num_rows>0){

        $_SESSION['Email'] = $email;
        $_SESSION['Pwd'] = $pwd;
        $_SESSION['AccType'] = $accType;
        $_SESSION['fName'] = $row['fName'];
        $_SESSION['lName'] = $row['lName'];
        $_SESSION['addrs'] = $row['addrs'];
        $_SESSION['mobile'] = $row['phoneNo'];
        $_SESSION['profile'] = $row['profile'];
        $_SESSION['LoginStat'] = true;

        if($row['accType'] == 'buyer'){
            $_SESSION['BuyerSignedIn'] = true;
            header("Location: buyerDash.php");
            exit();
        }
        elseif($row['accType'] == 'seller'){
            $_SESSION['SellerSignedIn'] = true;
            header("Location: sellerDash.php");
            exit();
        }
        elseif($row['accType'] == 'staff'){
            $_SESSION['StaffSignedIn'] = true;
            header("Location: staffDash.php");
            exit();
        }
    }
    else{

        $_SESSION['LoginStat'] = false;
        $_SESSION['BuyerSignedIn'] = false;
        $_SESSION['SellerSignedIn'] = false;
        $_SESSION['StaffSignedIn'] = false;
        echo "<script>
                alert('Invalid email/password!');
                window.location.replace('loginHTML.php');
                </script>";
    }

    mysqli_close($conn);
    ob_end_flush();
?>