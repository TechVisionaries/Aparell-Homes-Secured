<?php
    include_once 'error-handler.php';
    include_once 'config.php';
?>
<?php

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
            SET fName = ?,
                lName = ?,
                addrs = ?,
                phoneNo = ?,
                password = ?,
                profile = ?
            WHERE email = ? AND accType = ?";

    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("sssssssss", $firstName, $lastName, $address, $phone, $pwd, $target_file, $email, $accType);
    $stmt2->execute();

    //check connection        
    //display sccessful message
    if($stmt2->affected_rows > 0){
        echo "<script>
                var acctype = '$accType';
                alert('Successfully Updated!');
                window.location.replace(acctype+'Dash.php');
              </script>";

    }
    //display unsuccessful message
    else{
        echo "<script>
                var acctype = '$accType';
                alert('Update Unsuccessful!');
                window.location.replace(acctype+'Dash.php');
              </script>";
    }


    mysqli_close($conn);
?>