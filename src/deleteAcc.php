<?php
    include_once 'error-handler.php';
    include_once 'config.php';
?>
<?php
    $email = $_GET['email'];
    $acc = $_GET['accType'];

    $sql = "SELECT profile FROM users WHERE email = '$email' AND accType = '$acc'";

    $result = $conn -> query($sql);
    if($result->num_rows>0){
        while($row = $result -> fetch_assoc()){
            $dp = $row['profile'];
        }
    }  

    $sql2 = "DELETE FROM users WHERE email = '$email' AND accType = '$acc'";
    $sqlDeleteAprt = "DELETE FROM apartments WHERE sellerMail = '$email'";
    $sqlDeletefav = "DELETE FROM userfavs WHERE email = '$email' AND accType = '$acc'";

    if(mysqli_query($conn,$sql2)){
        if($dp != "images/user.png"){
            unlink("$dp");
        }
        if($acc == 'seller'){
            mysqli_query($conn,$sqlDeleteAprt);
        }
        
        mysqli_query($conn,$sqlDeletefav);

        echo "<script>
                alert('Successfully deleted!');
                window.location.replace('ManageUsers.php');
              </script>";
    }
    else{
        echo "<script>
                let type = '$acc';
                alert('Unsuccessfull!');
                window.location.replace(type+'Dash.php');
              </script>";
    }


    mysqli_close($conn);
?>