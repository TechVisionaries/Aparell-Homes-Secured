<?php
    session_start();
    include_once 'error-handler.php';
    include_once 'config.php';
?>
<?php
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