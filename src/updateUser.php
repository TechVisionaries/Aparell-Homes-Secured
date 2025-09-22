<?php
    include_once 'error-handler.php';
    include_once 'config.php';
?>
<?php
   session_start();
    
    //put the values from the reg form into variables 
    $email = $_SESSION['ManageUserMail'];
    $accType = $_SESSION['ManageUseraccType'];
    $firstName = htmlspecialchars($_POST['firstname']);
    $lastName = htmlspecialchars($_POST['lastname']);
    $address = htmlspecialchars($_POST['addrs']);
    $phone = $_POST['phonenumber'];
    $pwd = $_POST['pwd'];


    //update values
    $sql = "UPDATE users
            SET fName = '$firstName',
                lName = '$lastName',
                addrs = '$address',
                phoneNo = $phone,
                password = '$pwd'
            WHERE email = '$email' AND accType = '$accType'";  
            

    if(mysqli_query($conn,$sql)){
        echo "<script>
                alert('Successfully Updated!');
                window.location.replace('ManageUsers.php');
              </script>";
        
    }
    else{
        echo "<script>
                alert('Update Unsuccessful!');
                window.location.replace('ManageUsers.php');
              </script>";
    }


    mysqli_close($conn);
?>