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

    $sql2 = "DELETE FROM users WHERE email = ? AND accType = ?";
    $sqlDeleteAprt = "DELETE FROM apartments WHERE sellerMail = ?";
    $sqlDeletefav = "DELETE FROM userfavs WHERE email = ? AND accType = ?";

    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("ss", $email, $acc);
    if($stmt2->execute()){
        if($dp != "images/user.png"){
            unlink("$dp");
        }
        if($acc == 'seller'){
            $stmtDeleteAprt = $conn->prepare($sqlDeleteAprt);
            $stmtDeleteAprt->bind_param("s", $email);
            $stmtDeleteAprt->execute();
        }

        $stmtDeletefav = $conn->prepare($sqlDeletefav);
        $stmtDeletefav->bind_param("ss", $email, $acc);
        $stmtDeletefav->execute();

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