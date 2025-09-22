<?php
    include_once 'config.php';
?>
<?php

    //put the values from the reg form into variables
    $firstName = htmlspecialchars($_POST['fName']);
    $lastName = htmlspecialchars($_POST['lName']);
    $address = htmlspecialchars($_POST['addrs']);
    $accType = $_POST['accType'];
    $email = strtolower($_POST['email']);
    $phone = $_POST['phone'];
    $pwd = $_POST['pwd'];

    //get all the emails in the user database
    $sqlView = "SELECT email, accType FROM users";

    //run query and store results
    $result = $conn->query($sqlView);

    if($result->num_rows>0){
        while($row = $result->fetch_assoc()){
            if($row['email'] == $email && $row['accType'] == $accType){
                echo "<script>let confirmation = confirm('Account already exists! Login?');
                              if(confirmation){
                                window.location.replace('loginHTML.php');
                              }
                              else{
                                window.location.replace('register.html');
                              }
                    </script>";
                die("Account Exists");    
            }
        }
    }

    //insert values
    $sqlInsert = "INSERT INTO users(email,fName,lName,addrs,accType,phoneNo,password) VALUES('$email','$firstName','$lastName','$address','$accType','$phone','$pwd');";

    if(mysqli_query($conn,$sqlInsert)){
        echo "<script>
                alert('Successfully Registered!');
                window.location.replace('loginHTML.php');
              </script>";
        
    }
    else{
        echo "<script>
                alert('Registration Unsuccessful!');
                window.location.replace('register.html');
              </script>";
    }


    mysqli_close($conn);
?>