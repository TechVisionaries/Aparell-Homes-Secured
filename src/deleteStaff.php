<?php
    include_once 'error-handler.php';
    include_once 'config.php';
?>
<?php
    session_start();
    $email = $_SESSION['Email'];
    $acc = $_SESSION['AccType'];

    $sqlSelectBuyer = "SELECT profile FROM users WHERE email = '$email' AND accType = '$acc'";

    $result = $conn -> query($sqlSelectBuyer);

    if($result->num_rows>0){

        while($row = $result -> fetch_assoc()){
            $dp = $row['profile'];
        }

    }  

    $sqlDeleteBuyer = "DELETE FROM users WHERE email = '$email' AND accType = '$acc'";

    if(mysqli_query($conn,$sqlDeleteBuyer)){

        if($dp != "images/user.png"){
            unlink("$dp");
        }
        $_SESSION['StaffSignedIn'] = false;
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
                window.location.replace('staffDash.php');
              </script>";
              
    }


    mysqli_close($conn);
?>